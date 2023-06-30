CREATE TABLE tx_mycocarto_domain_model_ecology
(
    name varchar(255) NOT NULL
);

CREATE TABLE tx_mycocarto_domain_model_tree
(
    name varchar(255) NOT NULL,
    scientific_name varchar(255) NOT NULL
);

CREATE TABLE tx_mycocarto_domain_model_species
(
    genus varchar(255) NOT NULL,
    species varchar(255) NOT NULL,
    author varchar(255),
    family int(11) unsigned NOT NULL,
    KEY index_family (family)
);

CREATE TABLE tx_mycocarto_domain_model_taxon
(
    scientific_name varchar(255) NOT NULL,
    parent_taxon int(11) unsigned,
    taxon_level int(11) unsigned NOT NULL,
    KEY index_parent_taxon (parent_taxon),
    KEY index_taxon_level (taxon_level)
);

CREATE TABLE tx_mycocarto_domain_model_taxonlevel
(
    name varchar(255) NOT NULL
);

CREATE TABLE tx_mycocarto_domain_model_taxon_observation
(
    date date NOT NULL,
    latitude float,
    longitude float,
    ecology int(11) unsigned NOT NULL,
    species int(11) unsigned NOT NULL,
    KEY index_ecology (ecology),
    KEY index_species (species)
);

CREATE TABLE tx_mycocarto_domain_observation_tree
(
    observation int(11) unsigned NOT NULL,
    tree int(11) unsigned NOT NULL,
    KEY index_observation (observation),
    KEY index_tree (tree)
);
