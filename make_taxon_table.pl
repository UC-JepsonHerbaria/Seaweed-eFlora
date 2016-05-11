#!usr/bin/perl
use warnings;
use strict;

#declare input files
my $taxon_id_file = "inputs/seaweed_taxon_ids.txt";
my @content_files = ("KELP_species_pages.txt", "GREEN_species_pages.txt");

#declare taxonID hash
my %TID;

#generate hash
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
my $output_file = "outputs/load_taxon_table.sql";
open(OUT, ">outputs/load_taxon_table.sql") || die;

foreach my $filename (@content_files) {
warn "now processing file $filename";
	open(IN, "inputs/$filename") || die "couldn't find content file $filename\n";
	while(<IN>){
		next if m/^#/;
		
		#Escape single quotes for SQL insert
		s/'/''/g;
		
		#get all values from the paragraph
		my $scientific_name=&get_taxon_name($_);
		my $taxon_author=&basic_get($_, "TAXON AUTHOR");
		my $native_status=&get_native_status($_);
		my $key_characteristics=&basic_get($_, "KEY CHARACTERISTICS");
		my $status=&basic_get($_, "STATUS");
		my $habitat=&basic_get($_, "HABITAT");
		my $life_history=&basic_get($_, "LIFE HISTORY");
		my $conservation=&basic_get($_, "CONSERVATION");
		my $distribution_notes=&basic_get($_, "DISTRIBUTION NOTES");
		my $MAC_description=&basic_get($_, "MAC DESCRIPTION");
		my $MAC_notes=&basic_get($_, "MAC NOTES");
		my $vertical_distribution=&basic_get($_, "VERTICAL DISTRIBUTION");
		my $frequency=&basic_get($_, "FREQUENCY");
		my $substrate=&basic_get($_, "SUBSTRATE");
		my $associates=&basic_get($_, "ASSOCIATES");
		my $epiphytes=&basic_get($_, "EPIPHYTES");
		my $type_locality=&basic_get($_, "TYPE LOCALITY");

		my $taxon_id = $TID{$scientific_name};
		unless ($taxon_id){
			warn "no taxon id for scientific name $scientific_name\n add $scientific_name to seaweed_taxon_ids.txt\n";
			&log_issue("no taxon id for scientific name $scientific_name\n add $scientific_name to seaweed_taxon_ids.txt");
			next;
		} 

		$scientific_name = "'$scientific_name'";

		print OUT "INSERT INTO eflora_taxa(TaxonID, ScientificName, TaxonAuthor, NativeStatus, KeyCharacteristics, Status, Habitat, LifeHistory, Conservation, DistributionNotes, MACDescription, MACNotes, VerticalDistribution, Frequency, Substrate, Associates, Epiphytes, TypeLocality)\n";
		print OUT "VALUES($taxon_id, $scientific_name, $taxon_author, $native_status, $key_characteristics, $status, $habitat, $life_history, $conservation, $distribution_notes, $MAC_description, $MAC_notes, $vertical_distribution, $frequency, $substrate, $associates, $epiphytes, $type_locality)\n";
		print OUT ";\n";
	}
}

close(IN);
close(OUT);



sub basic_get {
		my($paragraph, $tag) = @_;
		if($paragraph =~ /$tag: *(.*)/){
			my $content=$1;
			$content=~s/ *$//;
			return "\'$content\'";
		}
		else {
			return "NULL";
		}
}

sub get_native_status {
    my $par = shift; 
    my @lines=split(/\n/,$par); 
    if( $lines[0]=~/^NATIVE|NON-NATIVE|CRYPTOGENIC|\[NATIVE STATUS\]|\[\?\]/){ 
        return "\'$lines[0]\'"; 
    }
    else {
    	warn "no/strange native status for paragraph starting with $lines[0]\n$lines[1]\n";
    	&log_issue("no/strange native status for paragraph starting with $lines[0]\n$lines[1]");
    	return "NULL";
    }
}

sub get_taxon_name {
	#NOTE: name is returned without SQL quotes, so it can be matched to the taxon ID file. Quotes are added after
    my $par = shift; #each paragraph is separated by a blank line
    my @lines=split(/\n/,$par); #the array of lines within a paragraph are values separated by a new line
    if( $lines[0]=~/^NATIVE|NON-NATIVE|CRYPTOGENIC|\[NATIVE STATUS\]|\[\?\]/){ #if the first line starts with...
        return $lines[1]; #the name is the contents of the second line
    }
    else{
        return "NULL"; 
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