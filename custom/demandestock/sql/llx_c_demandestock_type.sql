CREATE TABLE llx_c_demandestock_type (
    rowid integer AUTO_INCREMENT PRIMARY KEY NOT NULL ,
    code varchar(10) NOT NULL ,
    label varchar(128) NOT NULL,
    active tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
