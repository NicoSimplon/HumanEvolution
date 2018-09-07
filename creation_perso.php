<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    use Classes\Autoloader;
    use Classes\Personnage;
    use Classes\Partie;
    

    require 'Classes/Autoloader.php';
    Autoloader::register();

    $partie = new Partie;
    
    $persos = [];

    switch($_POST['case']){ 
        
        case 'persos':

            $nbPerso = intval($_POST['nbPerso']);

            $partie->createPartie();
            
            for($i = 0; $i < $nbPerso; $i++ ){

                $personnage = new Personnage($i);
        
                $personnage->registerPersonnage();
                
                $personnage->registerPartiePerso();
        
                $tabPerso = [];
        
                $tabPerso[] = $personnage->getTailleNaissance();
                $tabPerso[] = $personnage->getEsperanceVie();
                $tabPerso[] = $personnage->getCroissance();
                $tabPerso[] = $personnage->getEmplacement();
                $tabPerso[] = $personnage->getSexe();
        
                $persos[] = $tabPerso;
        
            }
            
            $data = json_encode($persos);
            
            echo $data;

            break;

        case 'stats':

            $partie->setStats();

            $stats = $partie->getStatGlobal();

            echo $stats;

            break;

        case 'csv':

            $partie->getCSV();

            break;
    }