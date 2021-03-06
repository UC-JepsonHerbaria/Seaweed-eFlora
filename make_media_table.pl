#!usr/bin/perl
use warnings;
use strict;

#declare input files
my $taxon_id_file = "inputs/seaweed_taxon_ids.txt";
my @treatment_files = ("RED_species_pages.txt", "BROWN_species_pages.txt", "KELP_species_pages.txt", "GREEN_species_pages.txt");
my $cspace_specimen_file = "inputs/4solr_algae.csv";
my $cspace_media_file = "inputs/4solr.ucjeps.media.txt";

#declare hashes
my %TID;
my %BLOB_ID;
my %IMG_CREATOR;
my %IMG_COPYRIGHT;
my %IMG_NAME;
my %IMG_LOCALITY;

#generate taxonID hash
open(IN, $taxon_id_file) || die "couldn't find taxon id file $taxon_id_file\n";
while(<IN>){
	chomp;	
	my ($code,$name,$name_with_author)=split(/\t/);
	$TID{$name}=$code;
}
close(IN);

#generate specimen image blob id hash
open (IN, $cspace_specimen_file) || die "couldn't find cspace specimen file $cspace_specimen_file\n";
while(<IN>){
	chomp;
	my (@fields)=split(/\t/);
	my $accession_number=$fields[2];
	my $blob=$fields[59]; ###NOTE! These field declarations are fragile, since the 4solr files can get changed
	$BLOB_ID{$accession_number}=$blob;
}
close(IN);

#generate hashes from cspace media datastore
open (IN, $cspace_media_file) || die "couldn't find cspace media file $cspace_media_file\n";
while(<IN>){
	chomp;
	my (@fields)=split(/\t/);
	my $DP_number=$fields[9];
	my $photographer=$fields[5];
	my $blob=$fields[7];
	my $name=$fields[4]; 
	my $locality=$fields[22];
	foreach ($photographer){ s/'/''/g; } #Escaping single quotes for SQL insert
	foreach ($locality){ s/'/''/g; }
	
	$BLOB_ID{$DP_number}=$blob; #use same hash as specimen images, since no overlap between UC and DP numbers
	$IMG_CREATOR{$DP_number}=$photographer;
	$IMG_NAME{$DP_number}=$name; #not used right now, but loading it in the database just in case.
	$IMG_LOCALITY{$DP_number}=$locality;
}


#this function indicates the record delimiter. In this case, an empty line
#as such, it needs to be designated after the other files are processed
$/="";

#open output file
my $output_file = "outputs/load_media_table.sql";
open(OUT, ">outputs/load_media_table.sql") || die;

#process each treatment file to pull out all media references
foreach my $filename (@treatment_files) {
	warn "now processing file $filename";
	open(IN, "inputs/$filename") || die "couldn't find content file $filename\n";
	while(<IN>){
		next if m/^#/;
		
		#Escape single quotes for SQL insert
		s/'/''/g;
		
		#get all values from the paragraph
		my $scientific_name=&get_taxon_name($_);
		
		my @photo_array=&get_cspace_array($_, "PHOTO"); #get the array of DP numbers
		my @illustration_array=&get_array($_, "ILLUSTRATION");
		my @audio_array=&get_array($_, "OGG");
		my @specimen_array=&get_cspace_array($_, "SPEC");

		my $taxon_id = $TID{$scientific_name};
		unless ($taxon_id){
			warn "no taxon id for scientific name $scientific_name\t-----\tadd $scientific_name to seaweed_taxon_ids.txt\n";
			next;
		} 
		
		foreach my $element (@photo_array){ ####Media rank is handled by sorting by ID, since that's the order it goes in
			#warn "printing photo record for element $element\n";
			print OUT "INSERT INTO eflora_media(TaxonID, FileName, MediaType, MediaURL, ThumbURL, Creator, Locality)\n";
			print OUT "VALUES($taxon_id, \'$element\', 'Photo', \'https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/$BLOB_ID{$element}/derivatives/OriginalJpeg/content\', \'https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/$BLOB_ID{$element}/derivatives/Medium/content\', \'$IMG_CREATOR{$element}\', \'$IMG_LOCALITY{$element}\')\n";
			print OUT ";\n";
		}
		foreach my $element (@illustration_array){
			#check if is DeCew
			my $IsDecew;
			if ($element=~/(^C-|^P-|^R_).*gif$/) { #illustrations in this format are from DeCew's Guide so get the DeCew citation on the website
				$IsDecew=1;
			}
			else {$IsDecew=0;}
			
			print OUT "INSERT INTO eflora_media(TaxonID, FileName, MediaType, IsDecew)\n";
			print OUT "VALUES($taxon_id, \'$element\', 'Illustration', $IsDecew)\n";
			print OUT ";\n";
		}
		foreach my $element (@audio_array){
			print OUT "INSERT INTO eflora_media(TaxonID, FileName, MediaType)\n";
			print OUT "VALUES($taxon_id, \'$element\', 'Audio')\n";
			print OUT ";\n";
		}
		foreach my $element (@specimen_array){ #for each specimen ID, insert the UC number as FileName and the constructed image URL as MediaURL
			print OUT "INSERT INTO eflora_media(TaxonID, FileName, MediaType, MediaURL, ThumbURL)\n";
			print OUT "VALUES($taxon_id, \'$element\', 'Specimen', \'https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/$BLOB_ID{$element}/derivatives/OriginalJpeg/content\', \'https://webapps.cspace.berkeley.edu/ucjeps/imageserver/blobs/$BLOB_ID{$element}/derivatives/Medium/content\')\n";
			print OUT ";\n";
		}
	}
	close(IN);
}
close(OUT);

sub get_array {
	my($paragraph, $tag) = @_;
	my @lines = split (/\n/,$paragraph);
	my @output_array;
	foreach my $line (@lines){
		$line=~s/ *$//;
		if ($line =~ /$tag[0-9]?: *(.*)/){
			my $content=$1;
			unless ($content eq ""){
				push (@output_array, $content);
			}
		}
	}
	return @output_array;
}

sub get_cspace_array {
#same as get_array, but checks if the blob_id exists in the CSpace %BLOB_ID hash
#if no DP number exists, log it
	my($paragraph, $tag) = @_;
	my @lines = split (/\n/,$paragraph);
	my @output_array;
	foreach my $line (@lines){
		$line=~s/ *$//;
		if ($line =~ /$tag[0-9]?: *(.*)/){
			my $content=$1;
			unless ($1 eq ""){
				if ($BLOB_ID{$content}) {
					push (@output_array, $content);
				}
				else { &log_issue("image for '$content' apparently not in database"); }
			}
		}
	}
	return @output_array;
}

sub get_taxon_name {
	#NOTE: name is returned without SQL quotes, so it can be matched to the taxon ID file. Quotes are added after
    my $par = shift; #each paragraph is separated by a blank line
    my @lines=split(/\n/,$par); #the array of lines within a paragraph are values separated by a new line
    if( $lines[0]=~/^NATIVE|NON-NATIVE|CRYPTOGENIC|\[NATIVE STATUS\]/){ #if the first line starts with...
        return $lines[1]; #the name is the contents of the second line
    }
    else{
        return $lines[0]; 
    }
}

sub log_issue {
#prints the inputted message to the designated log file
	my $log_file = 'outputs/log.txt';
	no strict "refs";
	open($log_file, '>>', $log_file);
	print $log_file "logging: @_\n";
	close $log_file;
}