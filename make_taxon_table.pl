#!usr/bin/perl
use warnings;
use strict;
use Text::CSV;

#declare input files
my $taxon_id_file = "inputs/seaweed_taxon_ids.txt";
my $synonymy_file = "inputs/names_synonyms.csv";
my @content_files = ("KELP_species_pages.txt", "GREEN_species_pages.txt");

#declare taxonID hash
my %TID;

#generate taxon id hash %TID
open(IN, $taxon_id_file) || die "couldn't find taxon id file $taxon_id_file\n";
while(<IN>){
	chomp;
	my ($code,$name,$name_with_author)=split(/\t/);
	$TID{$name}=$code;
}
close(IN);

#parse synonymy file
#declare hashes
my %MAJGRP;
my %DATE;
my %ADDITIONS;

#parse synonymy csv file
my $csv = Text::CSV->new({ sep_char => ',', quote_char => '"'});
open(IN, $synonymy_file) || die "couldn't find synonymy file file $synonymy_file\n";
my $header_line=<IN>;   #skip header line
while(<IN>){
	chomp;
	if ($csv->parse($_)) {
    	my @columns = $csv->fields();
		unless($#columns==7){
			warn "$#columns bad field number $_\n";
			&log_issue("$#columns bad field number $_\n");
		}
		my ($syn_major_group,
		$syn_scientific_name_author,
		$syn_date,
		$syn_name_status,
		$syn_accepted_name,
		$syn_MAC_name,
		$syn_additions,
		$syn_non_native)=@columns;
		
		#skip all non-accepted names
		next unless $syn_name_status=~/accepted/i;
		
		$syn_scientific_name_author=~s/ *$//;
		my $syn_scientific_name=&strip_name($syn_scientific_name_author);
		
		#checks that all names in the synonymy file are represented in the taxon id file
		#this may be superfluous as only accepted names are processed in this script
		my $syn_taxon_id = $TID{$syn_scientific_name};
		unless ($syn_taxon_id){
			warn "no taxon id for scientific name $syn_scientific_name\n add $syn_scientific_name to seaweed_taxon_ids.txt\n";
			&log_issue("no taxon id\t$syn_scientific_name\t$syn_scientific_name_author");
			next;
		} 
	
		#assign values to hashes
		$MAJGRP{$syn_scientific_name}=$syn_major_group;
		$DATE{$syn_scientific_name}=$syn_date;
		$ADDITIONS{$syn_scientific_name}=$syn_additions;
	}
	else{ warn "synonymy file parsing error $_\n"; }
}

#this function indicates the record delimiter. In this case, an empty line
#as such, it needs to be designated after the other files are processed
$/="";

#open output file
my $output_file = "outputs/load_taxon_table.sql";
open(OUT, ">outputs/load_taxon_table.sql") || die;

#This file is made here and subsequently used by the synonymy script
open(FILE, ">outputs/treated_names_list.txt") || die;

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

		#assign additional values from synonym file
		my $major_group = my $date = my $additions = "";
		if ($MAJGRP{$scientific_name}) { $major_group = "'$MAJGRP{$scientific_name}'"; }
		else { $major_group = "NULL"; }
		if ($DATE{$scientific_name}) { $date = "'$DATE{$scientific_name}'"; }
		else { $date = "NULL"; }
		if ($ADDITIONS{$scientific_name}) { $additions = "'$ADDITIONS{$scientific_name}'"; }
		else { $additions = "NULL"; }

		#assign taxon id
		my $taxon_id = $TID{$scientific_name};
		unless ($taxon_id){
			warn "no taxon id for scientific name $scientific_name\n add $scientific_name to seaweed_taxon_ids.txt\n";
			&log_issue("no taxon id for scientific name $scientific_name\n add $scientific_name to seaweed_taxon_ids.txt");
			next;
		} 

		#print out the plain matched taxon names to treated_names file used by synonymy script
		print FILE "$scientific_name\n";

		$scientific_name = "'$scientific_name'";

		#all names with a treatment paragraph are accepted names
		my $name_status="'accepted name'";

		#print SQL insert statement to output
		print OUT "INSERT INTO eflora_taxa(TaxonID, ScientificName, TaxonAuthor, NativeStatus, KeyCharacteristics, Status, Habitat, LifeHistory, Conservation, DistributionNotes, MACDescription, MACNotes, VerticalDistribution, Frequency, Substrate, Associates, Epiphytes, TypeLocality, NameStatus, DescriptionDate, MajorGroup, Additions)\n";
		print OUT "VALUES($taxon_id, $scientific_name, $taxon_author, $native_status, $key_characteristics, $status, $habitat, $life_history, $conservation, $distribution_notes, $MAC_description, $MAC_notes, $vertical_distribution, $frequency, $substrate, $associates, $epiphytes, $type_locality, $name_status, $date, $major_group, $additions)\n";
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

sub strip_name{
#subroutine adopted from CCH.pm, that removes the authority from a botanical name
#e.g. converts "Acanthocladia muricata (Endlicher) Ruprecht" to "Acanthocladia muricata"
	local($_) = @_;
	s/× /X /g;
	s/'//g;
	s/`//g;
	s/\?//g;
	s/^ *//;
	s/ *$//;
	s/  +/ /g;
	s/ spp\./ subsp./;
	s/ssp\./subsp./;
	s/ ssp / subsp. /;
	s/ subsp / subsp. /;
	s/ var / var. /;
	s/ var. $//;
	s/ sp\..*//;
	s/ sp .*//;
	s/ [Uu]ndet.*//;
	s/ x / X /;
	s/ . / X /;
	s/ *$//;

	s/ aff\. / /;
	s/ cf\. / /;

	s/Hook\. f./Hook./g;
	s/Rech\. f./Rech./g;
	s/Schult\. f./Schult./g;
	s/Schultes f./Schultes/g;
	s/Hallier f\./Hallier/g;
	s/^([A-Z][A-Za-z]+) (X?[-a-z]+).*? (subvar\.|subsp\.|ssp\.|var\.|f\.) ([-a-z]+).*/$1 $2 $3 $4/ ||
	s/^([A-Z][A-Za-z]+) X ([-a-z]+) .+/$1 X $2/||
	s/^([A-Z][A-Za-z]+) × ?([-a-z]+) .+/$1 X $2/||
	s/^([A-Z][A-Za-z]+) × ?([-a-z]+)/$1 X $2/||
	s/^([A-Z][A-Za-z]+) (X?[-a-z]+) .+/$1 $2/||
	s/^([A-Z][A-Za-z]+) (indet\.|sp\.)/$1 indet./||
	s/^([A-Z][A-Za-z]+) (X?[-a-z]+)/$1 $2/||
	s/^([A-Z][A-Za-z]+) (X [-a-z]+)/$1 $2/||
	s/^X (A-Z][a-z]+) ([-a-z]+) (.+)/X $2 $3/||
	s/^([A-Z][A-Za-z]+) (.+)/$1/;
	s/ssp\./subsp./;
	s/ +$//;
	
	#print "$_ \n";
	$_;
}