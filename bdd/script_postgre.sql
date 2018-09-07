------------------------------------------------------------
--        Script Postgre 
------------------------------------------------------------



------------------------------------------------------------
-- Table: partie
------------------------------------------------------------
CREATE TABLE public.partie(
	id_partie   SERIAL NOT NULL ,
	date_partie DATE  NOT NULL ,
	CONSTRAINT prk_constraint_partie PRIMARY KEY (id_partie)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: personnage
------------------------------------------------------------
CREATE TABLE public.personnage(
	id_perso  SERIAL NOT NULL ,
	lifespan  INT  NOT NULL ,
	growth    FLOAT  NOT NULL ,
	birthsize FLOAT  NOT NULL ,
	men       BOOL  NOT NULL ,
	location  INT  NOT NULL ,
	CONSTRAINT prk_constraint_personnage PRIMARY KEY (id_perso)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: partie_perso
------------------------------------------------------------
CREATE TABLE public.partie_perso(
	id_partie INT  NOT NULL ,
	id_perso  INT  NOT NULL ,
	CONSTRAINT prk_constraint_partie_perso PRIMARY KEY (id_partie,id_perso)
)WITHOUT OIDS;



ALTER TABLE public.partie_perso ADD CONSTRAINT FK_partie_perso_id_partie FOREIGN KEY (id_partie) REFERENCES public.partie(id_partie);
ALTER TABLE public.partie_perso ADD CONSTRAINT FK_partie_perso_id_perso FOREIGN KEY (id_perso) REFERENCES public.personnage(id_perso);
