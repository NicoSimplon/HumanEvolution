#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: partie
#------------------------------------------------------------

CREATE TABLE partie(
        id_partie   int (11) Auto_increment  NOT NULL ,
        date_partie Date NOT NULL ,
        PRIMARY KEY (id_partie )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: personnage
#------------------------------------------------------------

CREATE TABLE personnage(
        id_perso  int (11) Auto_increment  NOT NULL ,
        lifespan  Int NOT NULL ,
        growth    Float NOT NULL ,
        birthsize Float NOT NULL ,
        men       Bool NOT NULL ,
        location  Int NOT NULL ,
        PRIMARY KEY (id_perso )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: partie_perso
#------------------------------------------------------------

CREATE TABLE partie_perso(
        id_partie Int NOT NULL ,
        id_perso  Int NOT NULL ,
        PRIMARY KEY (id_partie ,id_perso )
)ENGINE=InnoDB;

ALTER TABLE partie_perso ADD CONSTRAINT FK_partie_perso_id_partie FOREIGN KEY (id_partie) REFERENCES partie(id_partie);
ALTER TABLE partie_perso ADD CONSTRAINT FK_partie_perso_id_perso FOREIGN KEY (id_perso) REFERENCES personnage(id_perso);
