ALTER TABLE llx_demandestock ADD UNIQUE KEY `uk_demandestock_ref` (`ref`);
ALTER TABLE llx_demandestock ADD KEY `idx_demandestock_fk_project` (`fk_project`);
ALTER TABLE llx_demandestock ADD KEY `idx_demandestock_fk_user_author` (`fk_user_author`);
ALTER TABLE llx_demandestock ADD KEY `idx_demandestock_fk_user_modif` (`fk_user_modif`);
ALTER TABLE llx_demandestock ADD KEY `idx_demandestock_fk_user_valid` (`fk_user_valid`);

ALTER TABLE llx_demandestock ADD CONSTRAINT `fk_demandestock_fk_project` FOREIGN KEY (`fk_project`) REFERENCES `llx_projet` (`rowid`);
ALTER TABLE llx_demandestock ADD CONSTRAINT `fk_demandestock_fk_user_author` FOREIGN KEY (`fk_user_author`) REFERENCES `llx_user` (`rowid`);
ALTER TABLE llx_demandestock ADD CONSTRAINT `fk_demandestock_fk_user_modif` FOREIGN KEY (`fk_user_modif`) REFERENCES `llx_user` (`rowid`);
ALTER TABLE llx_demandestock ADD CONSTRAINT `fk_demandestock_fk_user_valid` FOREIGN KEY (`fk_user_valid`) REFERENCES `llx_user` (`rowid`);
