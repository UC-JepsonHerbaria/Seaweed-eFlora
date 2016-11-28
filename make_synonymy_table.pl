#This script processes all lines from the names_synonyms.csv synonymy file
#That are not accepted names, and prints out SQL to load them into the main table
#With the AcceptedNameTID in the AcceptedNameTID field of the database
#NOTE that the current workflow only processes names with treatments and not-accepted names
#meaning that accepted names that do not have a species page treatment are not indexed
#As of Nov 2016, the seaweedflora index was indexing taxon without species pages, so the code was 
#not working as intended.  Additions were made to correct this problem.  As species pages are created, new names are added 
#to treated_names_list text file, the synonyms for those taxa will also be displayed as a result of the added code.

#!usr/bin/perl
use warnings;
use strict;
use Text::CSV;

#declare input files
my $taxon_id_file = "inputs/seaweed_taxon_ids.txt";
my $synonymy_file = "inputs/names_synonyms.csv";

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

#prepare list of treated names. This is used to check if an accepted name has a treatment
#if it doesn't, an entry needs to be created in this script
#this creates a $treated_name_list variable which is used later
my $file = "outputs/treated_names_list.txt";
open(FILE, "< $file") or die "Can't open $file for read: $!";
my @names;
while (<FILE>) {
	push (@names, $_);
}
my $treated_names_list = join ('$|^',@names);
$treated_names_list =~ s/\n//g;
close FILE or die "Cannot close $file: $!";

#open output file
my $output_file = "outputs/load_synonymy_table.sql";
open(OUT, ">outputs/load_synonymy_table.sql") || die;

#parse synonymy csv file
my $csv = Text::CSV->new({ sep_char => ',', quote_char => '"'});
open(IN, $synonymy_file) || die "couldn't find synonymy file file $synonymy_file\n";
my $header_line=<IN>;   #skip header line
while(<IN>){
	chomp;
	
	#Escape single quotes for SQL insert
	s/'/''/g;
	
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
		
#		#skip all accepted names
#		next if $syn_name_status=~/accepted/i;

		
		#clean up name status
		$syn_name_status=~s/ *$//;
		$syn_name_status=~s/s$//;
		
		#get and format scientificname
		$syn_scientific_name_author=~s/ *$//;
		my $syn_scientific_name=&strip_name($syn_scientific_name_author);
		#$syn_accepted_name=&strip_name($syn_accepted_name);
		$syn_accepted_name=~s/  +/ /;
		$syn_accepted_name=~s/^ *//;
		$syn_accepted_name=~s/ *$//;
		
		#check if accepted names already have database entries from make_taxon_table.pl
		#if they do, then skip them
		if ($syn_name_status=~/accepted/i) {
			if ($syn_scientific_name =~ /^$treated_names_list$/){
				warn "name $syn_scientific_name already has entry. No skeletal entry created\n";
				next;
			}
		}
				
		#check if accepted names have a species page
		#if they do, then allow the filter in eflora_index.php to display it on index and searches
		my $HasSpeciesPage;
			if ($syn_accepted_name =~ /^$treated_names_list$/){
				$HasSpeciesPage = "'Y'";
				&log_issue("$syn_scientific_name----$HasSpeciesPage HasSpeciesPage added\n");
			}

			
			else {
				$HasSpeciesPage = "NULL";
				warn "$HasSpeciesPage-----$syn_accepted_name, accepted name for $syn_scientific_name, does not have a species page. It will be not displayed in searches yet\n";
				&log_issue("$HasSpeciesPage-----$syn_accepted_name, accepted name for $syn_scientific_name, does not have a species page. It will be not displayed in searches yet\n");
			}
		#for synonyms, get $AcceptedNameTID and confirm that it exists
		#for others (accepted names and misapplications), AcceptedNameTID is null
		my $AcceptedNameTID;
		if ($syn_name_status =~ /synonym/i) {
			$AcceptedNameTID = $TID{$syn_accepted_name};
			unless ($AcceptedNameTID){
				warn "no taxon id for accepted name $syn_accepted_name\tfor synonym $syn_scientific_name\t$syn_name_status\t-----\tadd $syn_accepted_name to seaweed_taxon_ids.txt\n";
				&log_issue("no taxon id for accepted name '$syn_accepted_name'\t$syn_scientific_name\t$syn_name_status\n");
				next;
			}
		}
		else {
			$AcceptedNameTID="NULL";
		}
		
		#format other fields to be printed to SQL insert statement
		$syn_major_group=&format_for_SQL($syn_major_group);
		$syn_name_status=&format_for_SQL($syn_name_status);
		$syn_date=&format_for_SQL($syn_date);
		$syn_additions=&format_for_SQL($syn_additions);
		
		#record native status as it is recorded for accepted names
		my $native_status;
		if ($syn_non_native) {
			$native_status = "'NON-NATIVE'";
		}
		else { $native_status = "NULL"; }
		
		#format scientific name for SQL before printing
		$syn_scientific_name = "'$syn_scientific_name'";

		
		#print SQL insert statement to output
		print OUT "INSERT INTO eflora_taxa(ScientificName, NativeStatus, NameStatus, DescriptionDate, MajorGroup, Additions, AcceptedNameTID, HasSpeciesPage)\n";
		print OUT "VALUES($syn_scientific_name, $native_status, $syn_name_status, $syn_date, $syn_major_group, $syn_additions, $AcceptedNameTID, $HasSpeciesPage)\n";
		print OUT ";\n";
	}
	
	else { 
		warn "synonymy file parsing error $_\n";
	}
}

############################
sub strip_name {
	local($_) = @_;
	s/^([A-Z][-a-z]+) (X?[-a-z]+).*(subsp\.|ssp\.|var\.|f\.) ([-a-z]+).*/$1 $2 $3 $4/ ||
	s/^([A-Z][a-z]+) ([x ]*[-a-z]+).*/$1 $2/;
	s/ssp\./subsp./;

	s/^ *//;
	s/ *$//;
	s/  */ /;
	return ($_);
}


sub format_for_SQL {
	my($input) = @_;
	if ($input=~/.+/){
		my $content = $input;
		$content=~s/ *$//;
		return "\'$content\'";
	}
	else {
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