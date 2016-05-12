--Fields needed for new eFlora page design
DROP TABLE eflora_taxa;

CREATE TABLE eflora_taxa (
	ID INTEGER PRIMARY KEY,
	TaxonID INTEGER,
	ScientificName TEXT,
	TaxonAuthor TEXT,
	NativeStatus TEXT,
	KeyCharacteristics TEXT,
	Status TEXT,
	Habitat TEXT,
	LifeHistory TEXT,
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

DROP TABLE eflora_media;

CREATE TABLE eflora_media (
	ID INTEGER PRIMARY KEY,
	TaxonID INTEGER,
	FileName TEXT,
	MediaType TEXT,
	MediaURL TEXT,
	ThumbURL,
	Title TEXT,
	Locality TEXT,
	Creator TEXT,
	CopyrightHolder TEXT
);