CREATE TABLE tx_mycocarto_domain_model_ecology
(
    name varchar(255) NOT NULL,
    UNIQUE KEY unique_ecology_name (name, deleted)
);

CREATE TABLE tx_mycocarto_domain_model_tree
(
    name            varchar(255) NOT NULL,
    scientific_name varchar(255) NOT NULL,
    UNIQUE KEY unique_tree_name (name, deleted),
    UNIQUE KEY unique_tree_scientific_name (scientific_name, deleted)
);

CREATE TABLE tx_mycocarto_domain_model_species
(
    genus   varchar(255)     NOT NULL,
    species varchar(255)     NOT NULL,
    author  varchar(255),
    family  int(11) unsigned NOT NULL,
    UNIQUE KEY unique_species_genus_species (genus, species, deleted),
    KEY index_family (family)
);

CREATE TABLE tx_mycocarto_domain_model_taxon
(
    scientific_name varchar(255)     NOT NULL,
    parent_taxon    int(11) unsigned,
    taxon_level     int(11) unsigned NOT NULL,
    UNIQUE KEY unique_taxon_scientific_name_level (scientific_name, taxon_level, deleted),
    KEY index_parent_taxon (parent_taxon),
    KEY index_taxon_level (taxon_level)
);

CREATE TABLE tx_mycocarto_domain_model_taxonlevel
(
    name varchar(255) NOT NULL,
    UNIQUE KEY unique_taxon_level_name (name, deleted)
);

CREATE TABLE tx_mycocarto_domain_model_observation
(
    date      int(11)          NOT NULL,
    latitude  float            NOT NULL,
    longitude float            NOT NULL,
    ecology   int(11) unsigned NOT NULL,
    species   int(11) unsigned NOT NULL,
    UNIQUE KEY unique_observation (date, latitude, longitude, ecology, species, deleted),
    KEY index_ecology (ecology),
    KEY index_species (species)
);
