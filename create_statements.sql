--Fields needed for new eFlora page design
DROP TABLE eflora_taxa;

CREATE TABLE eflora_taxa (
	ID INTEGER PRIMARY KEY,
	TaxonID INTEGER,
	ScientificName TEXT,
	TaxonAuthor TEXT,
	CommonName TEXT,
	NativeStatus TEXT,
	KeyCharacteristics TEXT,
	Status TEXT,
	Habitat TEXT,
	Life History TEXT,
	Conservation TEXT,
	DistributionNotes TEXT,
	MACDescription TEXT,
	MACNotes TEXT,
	VerticalDistribution TEXT,
	Frequency TEXT,
	Substrate TEXT,
	Associates TEXT,
	Epiphytes TEXT,
	TypeLocality TEXT
);

DROP TABLE eflora_illustrations;

CREATE TABLE eflora_illustrations (
	ID INTEGER PRIMARY KEY,
	TaxonID INTEGER,
	FileName TEXT,
	ImageType TEXT
);

/*DROP TABLE eflora_taxonomy;

CREATE TABLE eflora_taxonomy (
	ID INTEGER PRIMARY KEY,
	TaxonID INTEGER,
	FamilyID INTEGER,
	GenusID INTEGER,
	SpeciesID INTEGER
);

DROP TABLE eflora_distributions;

CREATE TABLE eflora_distributions (
	ID INTEGER PRIMARY KEY,
	TaxonID INTEGER,
	CCo INTEGER,
	CaRF INTEGER,
	CaRH INTEGER,
	nChI INTEGER,
	sChI INTEGER,
	DMojexcDMtns INTEGER,
	DMtns INTEGER,
	DSon INTEGER,
	KR INTEGER,
	MPexcWrn INTEGER,
	NCo INTEGER,
	NCoRH INTEGER,
	NCoRI INTEGER,
	NCoRO INTEGER,
	PRexcSnJt INTEGER,
	SCo INTEGER,
	SCoRI INTEGER,
	SCoRO INTEGER,
	SNEexcWaI INTEGER,
	nSNF INTEGER,
	cSNF INTEGER,
	sSNF INTEGER,
	nSNH INTEGER,
	cSNH INTEGER,
	sSNH INTEGER,
	ScV INTEGER,
	SnBr INTEGER,
	SnFrB INTEGER,
	SnGb INTEGER,
	SnJV INTEGER,
	SnJt INTEGER,
	Teh INTEGER,
	WTR INTEGER,
	WaI INTEGER,
	Wrn INTEGER
);
*/

--these tables are CREATED in seaweedflora.db