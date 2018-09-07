<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="evolution.css">
    <title>Human Evolution</title>
</head>
    <body>
        <div>
            <h1>HUMAN EVOLUTION</h1>
            <div class="stats">
                <hr>
                <table id="statistiques"></table>
                <hr>
                <button onclick="csv()" type="button" class="btn">CSV de la partie</button>
            </div>
        </div>
        <br>
        <input id="nbperso" type="range" name="nbperso" max="100">
        <span id="affichageNB">50</span>

        <div class="chrono row">
            1900
        </div>

        <div class="wrapper">
            
        </div>

        <button id="demarrer" type="button" class="btn">Démarrer</button>
    
        <script
            src="js/jQuery.js"></script>
        <script src="js/chrono.js"></script>
    </body>
</html>