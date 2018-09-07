<?php 

namespace Classes;

class Autoloader {

    /**
     * Permet d'appeler notre fonction autoloader()
     *
     * @return void
     */
    static function register(){

        /**
         * Il y a deux arguments: la classe actuelle et la fonction que l'on souhaite appeler ()
         */
        spl_autoload_register(array(__CLASS__,'autoload'));
    }

    /**
     * Permet de charger dynamiquement les différentes classes
     *
     * @param string $class
     * @return string
     */
    static function autoload($class){

        if(strpos($class,__NAMESPACE__.'\\') === 0){
            $class = str_replace(__NAMESPACE__.'\\','', $class);
            $class = str_replace('\\','/',$class);
            require __DIR__.'/'.$class.'.php';
        }
    }

}

