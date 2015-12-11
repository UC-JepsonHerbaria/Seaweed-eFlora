echo "drop and recreate tables"
sqlite3 outputs/seaweedflora.db < create_statements.sql
echo "refresh insert statements"
perl make_taxon_table.pl
echo "insert taxa into main table"
sqlite3 outputs/seaweedflora.db < outputs/load_taxon_table.sql
echo "create database indexes"
sqlite3 outputs/seaweedflora.db < create_indexes.sql
