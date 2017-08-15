echo "create algae-only specimen file to get around duplicate accession numbers"
grep "\tAlgae\t" inputs/4solr.ucjeps.public.txt > inputs/4solr_algae.csv
echo "create new log file"
rm outputs/log.txt
touch outputs/log.txt

echo "drop and recreate tables"
sqlite3 outputs/seaweedflora.db < create_statements.sql
echo "refresh insert statements"
perl make_taxon_table.pl
perl make_media_table.pl
perl make_synonymy_table.pl
echo "insert synonyms into main table"
sqlite3 outputs/seaweedflora.db < outputs/load_synonymy_table.sql
echo "insert taxa into main table"
sqlite3 outputs/seaweedflora.db < outputs/load_taxon_table.sql
echo "insert media into media table"
sqlite3 outputs/seaweedflora.db < outputs/load_media_table.sql
#echo "insert synonyms into main table"
#sqlite3 outputs/seaweedflora.db < outputs/load_synonymy_table.sql
echo "create database indexes"
sqlite3 outputs/seaweedflora.db < create_indexes.sql
echo "clean up intermediate files"
rm inputs/4solr_algae.csv
rm outputs/treated_names_list.txt
