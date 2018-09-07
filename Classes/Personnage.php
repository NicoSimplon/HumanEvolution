<?php

namespace Classes;

use Classes\Database;

/**
 * class Personnage
 * 
 * Permet de créer un nouveau personnage
 */
class Personnage {

    /**
     * Fixe la taille de départ du personnage
     *
     * @var float (42-57)
     */
    protected $_tailleNaissance;
    
    /**
     * Espérance de vie du personnage
     *
     * @var int (0-100)
     */
    protected $_vie;
    
    /**
     * Taux de croissance appliqué à la taille du personnage
     *
     * @var float (0.8-1.2)
     */
    protected $_croissance;

    /**
     * Sexe du personnage (Homme ou pas)
     *
     * @var string
     */
    protected $_sexe;

    /**
     * Indique le numéro de la case du personnage
     *
     * @var int
     */
    protected $_emplacement;

    /**
     * Stocke l'id du personnage
     *
     * @var int
     */
    private $_id;

    public function __construct($emplacement){

        $this->_tailleNaissance = mt_rand(420, 570)/10;
        $this->_vie = mt_rand(0, 100);
        $this->_croissance = mt_rand(8, 12)/10;
        $this->_emplacement = $emplacement;
        
        $proba_sexe = mt_rand(0, 100);
        
        if($proba_sexe<50){
            $this->_sexe = 'Homme';
        }
        else {
            $this->_sexe = 'Femme';
        }
    }

    public function getTailleNaissance(){
        return $this->_tailleNaissance;
    }

    public function getEsperanceVie(){
        return $this->_vie;
    }

    public function getCroissance(){
        return $this->_croissance;
    }

    public function getEmplacement(){
        return $this->_emplacement;
    }

    public function getSexe(){
        return $this->_sexe;
    }

    /**
     * Permet de vérifier si le personnage généré existe déjà dans la bdd et, s'il n'existe pas, de l'y enregistrer
     *
     * @return void
     */
    public function registerPersonnage(){
        
        // Connexion à la base de données
        $pdo = new Database;

        $connect = $pdo->getPdo();

        // Vérification
        $req = $connect->prepare('SELECT * FROM personnage WHERE lifespan = ? AND growth = ? AND birthsize = ? AND men = ? AND location = ?');
        
        $req->bindParam(1, $this->_vie);
        $req->bindParam(2, $this->_croissance);
        $req->bindParam(3, $this->_tailleNaissance);
        
        // Transformation en booléen
        if($this->_sexe === 'Homme'){
            $sexe = True;
        } else {
            $sexe = 0;
        }
        
        $req->bindParam(4, $sexe);
        $req->bindParam(5, $this->_emplacement);
        
        $req->execute();
        $res = $req->fetch();

        if($res){
            $this->_id = $res[0];
        } else {

            // Si le personnage n'existe pas, on l'enregistre
            $insert = $connect->prepare('INSERT INTO personnage (lifespan, growth, birthsize, men, location) VALUES(?, ?, ?, ?, ?)');

            $insert->bindParam(1, $this->_vie);
            $insert->bindParam(2, $this->_croissance);
            $insert->bindParam(3, $this->_tailleNaissance);
            if($this->_sexe === 'Homme'){
                $sexe = True;
            } else {
                $sexe = 0;
            }
            $insert->bindParam(4, $sexe);
            $insert->bindParam(5, $this->_emplacement);

            $insert->execute();


            // Puis on récupère son id
            $getId = $connect->prepare('SELECT id_perso FROM personnage WHERE lifespan = ? AND growth = ? AND birthsize = ? AND men = ? AND location = ?');

            $getId->bindParam(1, $this->_vie);
            $getId->bindParam(2, $this->_croissance);
            $getId->bindParam(3, $this->_tailleNaissance);
            if($this->_sexe === 'Homme'){
                $sexe = True;
            } else {
                $sexe = 0;
            }
            $getId->bindParam(4, $sexe);
            $getId->bindParam(5, $this->_emplacement);

            $getId->execute();
            $id = $getId->fetch();

            $this->_id = $id[0];

        }

    }

    public function registerPartiePerso(){

        $pdo = new Database;

        $connect = $pdo->getPdo();

        $reqPartie = $connect->query('SELECT max(id_partie) FROM partie');

        $resPartie = $reqPartie->fetch();

        $id_partie = $resPartie[0];

        $insert = $connect->prepare('INSERT INTO partie_perso VALUES(?, ?)');

        $insert->bindParam(1, $id_partie);
        $insert->bindParam(2, $this->_id);

        $insert->execute();

    }

}