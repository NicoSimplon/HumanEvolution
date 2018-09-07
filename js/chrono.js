tabPersonnage = [];

function evolution(n) {

    // Affichage de l'âge
    $(".age" + n).html(tabPersonnage[n][0] + ' ans / ');
    
    tabPersonnage[n][0]++;
    
    // Affichage de la taille
    $(".taille" + n).html(tabPersonnage[n][1].toFixed(1) + ' cm');


    if (tabPersonnage[n][0] < tabPersonnage[n][4] ) {
        if (tabPersonnage[n][0] <= 3) {
            tabPersonnage[n][1] = tabPersonnage[n][1] + (20*tabPersonnage[n][3]);
            $(".img" + n).attr('src', 'img/bebe.svg');
        } else if (tabPersonnage[n][0] <= 12) {
            tabPersonnage[n][1] = tabPersonnage[n][1] + (5*tabPersonnage[n][3]);
            $(".img" + n).attr('src', 'img/enfant.svg');
        } else if (tabPersonnage[n][0] <= 17) {
            tabPersonnage[n][1] = tabPersonnage[n][1] + 2;
            $(".img" + n).attr('src', 'img/' + tabPersonnage[n][2] + 'A.svg');
        } else if (tabPersonnage[n][0] <= 50) {
            $(".img" + n).attr('src', 'img/' + tabPersonnage[n][2] + 'J.svg');
        }   else if (tabPersonnage[n][0] <= 70) {
            $(".img" + n).attr('src', 'img/' + tabPersonnage[n][2] + 'M.svg');
        } else {
            tabPersonnage[n][1] = tabPersonnage[n][1] - 0.1;
            $(".img" + n).attr('src', 'img/' + tabPersonnage[n][2] + 'V.svg');
        };
    } else {
        $(".img" + n).attr('src', 'img/rip.svg');
        tabPersonnage[n][0] = tabPersonnage[n][4];
    }
};

function creationCellule(n) {
    // Ajoute une div avec le personnage et toutes ses informations
    $('.wrapper').append("<div class='grid_col'><div class='row'><img class='img img"+ n +"' src='img/bebe.svg'/></div><div class='row responsive_row'><span class='sexe"+ n +"'></span><span class='age"+ n +"'></span><span class='taille"+ n +"'></span></div></div>");
}

function chrono() {

    var nbperso = $("#nbperso").val();

    $.ajax({

        type: 'POST',
        
        url: 'creation_perso.php',
        
        timeout: 3000,

        data:{
            case: 'persos',
            nbPerso: nbperso,
        },

        success:function(data){
            
            genererCases(data); 

            afficherStats();

        },
        
    });
    
    var year = parseInt($(".chrono").html());

    var chrono = setInterval(function() {
        
        $(".chrono").html(year);

        for (var n = 0; n < $("#nbperso").val(); n++) {
            evolution(n);
        }
        
        year++;
        
        if (year > 2000) {
            clearInterval(chrono);
        }

    }, 100);
}

$("#demarrer").click(chrono);

// Me permet d'afficher le tableau des stats
function afficherStats(){
    $.ajax({

        type: 'POST',
        
        url: 'creation_perso.php',
        
        timeout: 3000,

        data:{
            case: 'stats',
        },

        success: function(data){
            $("#statistiques").html(data);

            changeCss();
        }

    });
}

// Gestion du nombre de personnages
function setNb(){
    var nb = $("#nbperso").val();
    
    $("#affichageNB").text(nb);
}

$("#nbperso").change(function(){
    setNb();
});

// Gestion du "+" dans le tableau
function changeCss(){
    $('.stat').each(function(){
        var valeur = $(this).text();

        if(parseFloat(valeur) > 0){
            $(this).prepend('+ ');
            $(this).css("color", "green");
        }

    });
}

// Cette fonction me permet de remplir le tableau tabPersonnage avec le JSON des persos
function genererCases(data){
    var personnages = JSON.parse(data);

    $.each(personnages, function(key, value){
            
        creationCellule(key);

        $(".sexe" + key).text(value[4] + ' /');
        $(".age" + key).text('0');
        $(".taille" + key).text(value[0]);
        
        var age = parseInt($(".age" + key).html());
        var taille = parseFloat(value[0]);
        var sexe = value[4];
        var croissance = parseFloat(value[2]);
        var esperance = parseInt(value[1]);
            
        var all = [age, taille, sexe, croissance, esperance];
            
        tabPersonnage.push(all); 
    });
}

// Fonction me permettant de générer le fichier CSV de la partie
function csv(){

    $.ajax({
        type: 'POST',

        url: 'creation_perso.php',
        
        timeout: 3000,
        
        data: {
            case: 'csv'
        }
    });

}