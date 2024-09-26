CREATE TABLE llx_demandestock (
    rowid integer AUTO_INCREMENT PRIMARY KEY NOT NULL,
    ref varchar(30) NOT NULL,
    fk_project integer NOT NULL,
    object_demande text,
    date_demande datetime,
    desired_date datetime,
    date_creation datetime,
    fk_user_author integer,
    fk_user_modif integer,
    fk_user_valid integer,
    date_modif datetime,
    date_valid datetime,
    fk_statut integer,
    tms timestamp,
    fk_warehouse integer,
    note_private text,
    note_public text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
