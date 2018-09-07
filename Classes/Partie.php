<?php

namespace Classes;

use Classes\Database;

/**
 * class Partie
 * 
 * Permet de créer une partie 
 */
class Partie {
    /**
     * Stocke la date qui est générée dans le constructeur
     *
     * @var string
     */
    private $_datePartie;

    /**
     * id de la partie en cours
     *
     * @var string
     */
    private $_idPartie;

    /**
     * Stocke les stats globales
     *
     * @var array
     */
    private $_statGlobale;

    /**
     * Stocke les stats de la partie
     *
     * @var array
     */
    private $_statPartie;

    /**
     * Ratio d'homme dans la partie
     *
     * @var float
     */
    private $_ratioHP;

    /**
     * Ratio d'homme au global
     *
     * @var float
     */
    private $_ratioHG;

    public function __construct()
    {
        $this->_datePartie = date('Y-m-d H:i:s');
    }

    public function createPartie(){

        $pdo = new Database;

        $connect = $pdo->getPdo();

        $insert = $connect->prepare('INSERT INTO partie (date_partie) VALUES (?)'); 
        $insert->bindParam(1, $this->_datePartie);

        $insert->execute();
        
        $req = $connect->query('SELECT max(id_partie) FROM partie');
        $res = $req->fetch();
        $this->_idPartie = $res[0];
    }

    public function setStats(){
        $pdo = new Database;

        $connect = $pdo->getPdo();

        $moyennePartie = $connect->prepare('SELECT(SELECT COUNT(id_perso) FROM partie_perso WHERE id_partie = (SELECT max(id_partie) FROM partie)), AVG(lifespan), AVG(growth), AVG(birthsize) FROM personnage INNER JOIN partie_perso ON personnage.id_perso = partie_perso.id_perso WHERE id_partie = (SELECT max(id_partie) FROM partie)');

        // $moyennePartie->bindParam(1,$this->_idPartie);
        // $moyennePartie->bindParam(2,$this->_idPartie);

        $moyennePartie->execute();
        $res = $moyennePartie->fetchAll();
        $this->_statPartie = $res;

        $moyenneGlobale = $connect->prepare('SELECT(SELECT COUNT(id_perso) FROM personnage), AVG(lifespan), AVG(growth), AVG(birthsize) FROM personnage');

        $moyenneGlobale->execute();
        $resGlobal = $moyenneGlobale->fetchAll();
        $this->_statGlobale = $resGlobal;

        $pourcentageHF = $connect->prepare('SELECT 
        (SELECT COUNT(personnage.id_perso) 
            AS homme 
            FROM personnage 
            INNER JOIN partie_perso 
            ON personnage.id_perso = partie_perso.id_perso 
            WHERE men=true AND id_partie = (SELECT max(id_partie) FROM partie)
        ),
        (SELECT COUNT(personnage.id_perso) 
            AS total 
            FROM personnage 
            INNER JOIN partie_perso 
            ON personnage.id_perso = partie_perso.id_perso 
            WHERE id_partie = (SELECT max(id_partie) FROM partie))'
        );

        // $pourcentageHF->bindParam(1,$this->_idPartie);
        // $pourcentageHF->bindParam(2,$this->_idPartie);
        
        $pourcentageHF->execute();

        $rezHF = $pourcentageHF->fetchAll();

        $calcHomme = $rezHF[0]['homme'] * 100 / $rezHF[0]['total'];
        $this->_ratioHP = $calcHomme;


        $pourcentageHFG = $connect->prepare('SELECT 
        (SELECT COUNT(personnage.id_perso) 
            AS homme 
            FROM personnage 
            WHERE men=true 
        ),
        (SELECT COUNT(personnage.id_perso) 
            AS total 
            FROM personnage)'
        );

        
        $pourcentageHFG->execute();

        $rezHFG = $pourcentageHFG->fetchAll();

        $calcHommeG = $rezHFG[0]['homme'] * 100 / $rezHFG[0]['total'];
        $this->_ratioHG = $calcHommeG;
    }

    public function getStatGlobal(){
        return '
            <tr>
                <th></th>
                <th>Nombre de personnages</th>
                <th>Espérance de vie moyenne</th>
                <th>Taux de croissance moyen</th>
                <th>Taille de naissance moyenne</th>
                <th>Ratio d\'homme (%)</th>
            </tr>
            <tr>
                <td><b>Au global</b></td>
                <td>'.$this->_statGlobale[0][0].'</td>
                <td>'.round($this->_statGlobale[0][1], 2).'</td>
                <td>'.round($this->_statGlobale[0][2], 2).'</td>
                <td>'.round($this->_statGlobale[0][3], 2).'</td>
                <td>'.round($this->_ratioHG, 2).'</td>
            </tr>
            <tr>
                <td><b>Votre partie</b></td>
                <td>'.$this->_statPartie[0][0].'</td>
                <td>'.round($this->_statPartie[0][1], 2).' (<span class="stat">'.round($this->_statPartie[0][1] - $this->_statGlobale[0][1], 2).'</span>)</td>
                <td>'.round($this->_statPartie[0][2], 2).' (<span class="stat">'.round($this->_statPartie[0][2] - $this->_statGlobale[0][2], 2).'</span>)</td>
                <td>'.round($this->_statPartie[0][3], 2).' (<span class="stat">'.round($this->_statPartie[0][3] - $this->_statGlobale[0][3], 2).'</span>)</td>
                <td>'.round($this->_ratioHP, 2).'</td>
            </tr>
            ';          
    }  

    public function getCSV(){
        
        $pdo = new Database;

        $connect = $pdo->getPdo();

        $req = "COPY (SELECT * FROM personnage INNER JOIN partie_perso 
        ON personnage.id_perso = partie_perso.id_perso 
        WHERE id_partie = (SELECT max(id_partie) FROM partie)) TO '/var/www/html/HumanEvolution(POO)/dump.csv' DELIMITER ',' CSV HEADER";

        $getCsv = $connect->prepare($req);
        
        $getCsv->execute();

    }

}

