#!usr/bin/perl
use warnings;
use strict;

use BerkeleyDB::Hash; #I don't think I need for anything

#declare input files
my $taxon_id_file = "inputs/seaweed_taxon_ids.txt";
my $content_file = "inputs/CALIFORNIA_SEAWEEDS.txt";
my $specimen_gallery_file = "inputs/specimen_gallery_ids.txt";

#declare taxonID hash
my %TID;

#generate taxonID hash
open(IN, $taxon_id_file) || die "couldn't find taxon id file $taxon_id_file\n";
while(<IN>){
	chomp;	
	my ($code,$name,$name_with_author)=split(/\t/);
	$TID{$name}=$code;
}
close(IN);

#this function indicates the record delimiter. In this case, an empty line
#as such, it needs to be designated after the other files are processed
$/="";

#open output file
my $output_file = "outputs/load_media_table.sql";
open(OUT, ">outputs/load_media_table.sql") || die;

open(IN, $content_file) || die "couldn't find content file $content_file\n";
while(<IN>){
	next if m/^#/;
	
	#Escape single quotes for SQL insert
	s/'/''/g;
	
	#get all values from the paragraph
	my $scientific_name=&get_taxon_name($_);
	
	my @photo_array=&get_array($_, "PHOTO");
	my @illustration_array=&get_array($_, "ILLUSTRATION");
	my @audio_array=&get_array($_, "OGG");

	my $taxon_id = $TID{$scientific_name};
	unless ($taxon_id){
		warn "no taxon id for scientific name $scientific_name\n add $scientific_name to seaweed_taxon_ids.txt\n";
		next;
	} 
	
	foreach my $element (@photo_array){ ####How to handle media rank? Maybe we just sort by ID because that's the order it goes in
		print OUT "INSERT INTO eflora_media(TaxonID, FileName, MediaType)\n";
		print OUT "VALUES($taxon_id, $element, 'Photo')\n";
		print OUT ";\n";
	}
	foreach my $element (@illustration_array){ ####How to handle media rank? Maybe we just sort by ID because that's the order it goes in
		print OUT "INSERT INTO eflora_media(TaxonID, FileName, MediaType)\n";
		print OUT "VALUES($taxon_id, $element, 'Illustration')\n";
		print OUT ";\n";
	}
	foreach my $element (@audio_array){
		print OUT "INSERT INTO eflora_media(TaxonID, FileName, MediaType)\n";
		print OUT "VALUES($taxon_id, $element, 'Audio')\n";
		print OUT ";\n";
	}
}
close(IN);

#process specimen IDs for specimen gallery file
#images must be named after the specimen ID, with the suffix ".JPG";
open(IN, $specimen_gallery_file) || die "couldn't find specimen id file $specimen_gallery_file\n";
while(<IN>){
	next if m/^#/;
	
	my $scientific_name=&get_taxon_name($_);
	
	my @specimen_array=&get_specimens($_);
	
	my $taxon_id = $TID{$scientific_name};
	unless ($taxon_id){
		warn "no taxon id for scientific name $scientific_name\n add $scientific_name to seaweed_taxon_ids.txt\n";
		next;
	}
	foreach my $element (@specimen_array){
		print OUT "INSERT INTO eflora_media(TaxonID, FileName, MediaType)\n";
		print OUT "VALUES($taxon_id, $element, 'Specimen')\n";
		print OUT ";\n";
	}
}

close(IN);
close(OUT);


sub get_array {
	my($paragraph, $tag) = @_;
	my @lines = split (/\n/,$paragraph);
	my @output_array;
	foreach my $line (@lines){
		if ($line =~ /$tag[0-9]?: *(.*)/){
			push (@output_array, "\'$1\'");
		}
	}
	return @output_array;
}

sub get_specimens {
	my($paragraph) = @_;
	my @lines = split (/\n/,$paragraph);
	my @output_array;
	foreach my $line (@lines[1 .. $#lines]){ #foreach line except the first one in the paragraph, which is the taxon name
		push (@output_array, "\'$line.JPG'");
	}
	return @output_array;
}

sub get_taxon_name {
	#NOTE: name is returned without SQL quotes, so it can be matched to the taxon ID file. Quotes are added after
    my $par = shift; #each paragraph is separated by a blank line
    my @lines=split(/\n/,$par); #the array of lines within a paragraph are values separated by a new line
    if( $lines[0]=~/^NATIVE|NON-NATIVE/){ #if the first line starts with...
        return $lines[1]; #the name is the contents of the second line
    }
    else{
        return $lines[0]; 
    }
}