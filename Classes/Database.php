<?php

namespace Classes;

class Database{

    /**
     * nom de l'hôte
     *
     * @var string
     */
    private $_host;

    /**
     * numéro du port
     *
     * @var string
     */
    private $_port;

    /**
     * nom de la base de données
     *
     * @var string
     */
    private $_dbName;

    /**
     * identifiant base de données
     *
     * @var string
     */
    private $_user;

    /**
     * mot de passe base de données
     *
     * @var string
     */
    private $_pass;

    /**
     * Contient l'objet PDO
     *
     * @var void
     */
    private $_pdo;

    public function __construct(){

        $this->_host = 'localhost';
        $this->_port = '5432';
        $this->_dbName = 'human_evolution';
        $this->_user = 'admin';
        $this->_pass = 'admin';
    }

    /**
     * Permet de créer un objet PDO pour se connecter à la base de données
     *
     * @return void
     */
    public function getPdo(){
        if($this->_pdo === null){
            $_pdo = new \PDO('pgsql:host='.$this->_host.'; port='.$this->_port.'; dbname='.$this->_dbName, $this->_user, $this->_pass);

            $this->_pdo = $_pdo;
        }
        
        return $this->_pdo;
    }

}
