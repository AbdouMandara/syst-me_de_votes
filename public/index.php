<?php
session_start(); //  Toujours ici tout en haut, une seule fois
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion & Inscription | Dashboard </title>
     <link rel="stylesheet" href="./login/style.css">
    <script src="./login/script.js" defer></script>
    <script src="../app/view/user/src/script.js" defer></script>
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <!-- <script src="./sweetAlert.min-v-2.12.js" defer></script> -->
    <script src="./htmx.min-v-2.0.6.js" defer></script>
</head>
<body>
<?php
require_once __DIR__ . '/../app/controller/user_controller.php';
$user_controller = new UserController(); //Pour le user
$admin_controller = new AdminController(); //Pour l'admin
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //sert à prendre l'url de la barre de recherche en PHP

// ? pour la connexion
if ($uri==='/systeme_de_votes/public/index.php') {
    $user_controller->afficherFormulaire();
    //donc j'affiche l'url en appelant la méthode 'afficherFormulaire()' si l'url correspond à '/systeme_de_votes/...'
}

/**
 * ici là ça sera utiliser par les input pour la vérif à travers htmx
 * Je récupères le contenu des inputs et je vérifie leur état à travers la méthode 'validation_champs()'
 */
else if ($uri==='/systeme_de_votes/public/index.php/login_validation') {
    $email_user_connexion= $_POST["email_user_connexion"];
    $password_user_connexion= $_POST["password_user_connexion"];
    $user_controller->validation_champs($email_user_connexion, $password_user_connexion);

    /**
     * Ce sont les variables qui vont apparaitre pour les différentes erreurs
     */
    if (isset($_SESSION['mauvais_email'])) :
        //C'est pour si l'email n'a pas une forme conforme
        echo '<p class="erreur_dans_login">' .  $_SESSION['mauvais_email'] . '</p>';
        unset($_SESSION['mauvais_email']);
    endif;
    if (isset($_SESSION['mauvais_longueur_password'])) :
        //C'est pour la longueur du mot de passe qui doit être > à 5
        echo '<p class="erreur_dans_login">' .  $_SESSION['mauvais_longueur_password'] . '</p>';
        unset($_SESSION['mauvais_longueur_password']);
    endif;
}

/**
 * C'est pour la soumission du contenu des inputs lors de la 'connexion'
 */
else if($uri==='/systeme_de_votes/public/index.php/login_submit'){
    $email_user_connexion= $_POST["email_user_connexion"];
    $password_user_connexion= $_POST["password_user_connexion"];
    
    /** 
     * Donc si l'email et le passwword de connexion est dispo et non vide alors j'appelle la méthode connexion_user()
     */
     if ($email_user_connexion!== '' && $email_user_connexion !==' ' && $password_user_connexion && $password_user_connexion!== '' || $password_user_connexion !==' ') {
         $user_controller->connexion_user($email_user_connexion, $password_user_connexion);
     }
/**
 * Pour les erreurs lors de la soumission du formulaire de connexion
 */
    if (isset($_SESSION['mot_de_passe_incorrect'])) {
        /**
         * si le mot de passe enré est incorrect alors il y a erreur
         */
        echo '<p class="erreur_dans_login">' .  $_SESSION['mot_de_passe_incorrect'] . '</p>';
        unset($_SESSION['mot_de_passe_incorrect']);
    }
    if (isset($_SESSION['email_user_non_trouvé'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['email_user_non_trouvé'] . '</p>';
        unset($_SESSION['email_user_non_trouvé']);
    }
    if (isset($_SESSION['email_user_connexion_vide'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['email_user_connexion_vide'] . '</p>';
        unset($_SESSION['email_user_connexion_vide']);
    }
    if (isset($_SESSION['password_user_connexion_vide'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['password_user_connexion_vide'] . '</p>';
        unset($_SESSION['password_user_connexion_vide']);
    }
}

// ? pour l'inscription
else if ($uri==='/systeme_de_votes/public/index.php/inscription_validation') {
     $password1 = $_POST['password1'];
    $email =$_POST['email'];
    $user_controller->validation_champs($email, $password1);

    if (isset($_SESSION['mauvais_email'])) :
        echo '<p class="erreur_dans_login">' .  $_SESSION['mauvais_email'] . '</p>';
        unset($_SESSION['mauvais_email']);
    endif;
    if (isset($_SESSION['mauvais_longueur_password'])) :
        echo '<p class="erreur_dans_login">' .  $_SESSION['mauvais_longueur_password'] . '</p>';
        unset($_SESSION['mauvais_longueur_password']);
    endif;
}

else if($uri==='/systeme_de_votes/public/index.php/inscription_submit'){
    $nom =$_POST['nom'] ;
    $password1 = $_POST['password1'];
    $email =$_POST['email'];
    if ($nom!== '' && $nom !==' ' && $email!==' ' && $email!== '' && $password1 !==' ' && $password1 !=='') {
        $user_controller->inscription_user($nom, $password1, $email);
     }
    if (isset($_SESSION['mot_de_passe_incorrect'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['mot_de_passe_incorrect'] . '</p>';
        unset($_SESSION['mot_de_passe_incorrect']);
    }
    if (isset($_SESSION['email_user_non_trouvé'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['email_user_non_trouvé'] . '</p>';
        unset($_SESSION['email_user_non_trouvé']);
    }
       if (isset($_SESSION['erreur_mot_de_passe'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['erreur_mot_de_passe'] . '</p>';
        unset($_SESSION['erreur_mot_de_passe']);
    }
    if (isset($_SESSION['nom_vide'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['nom_vide'] . '</p>';
        unset($_SESSION['nom_vide']);
    }
    if (isset($_SESSION['email_vide'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['email_vide'] . '</p>';
        unset($_SESSION['email_vide']);
    }
    if (isset($_SESSION['mauvais_email'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['mauvais_email'] . '</p>';
        unset($_SESSION['mauvais_email']);
    }
    if (isset($_SESSION['password_vide'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['password_vide'] . '</p>';
        unset($_SESSION['password_vide']);
    }
    if (isset($_SESSION['mauvais_longueur_password'])) {
        echo '<p class="erreur_dans_login">' .  $_SESSION['mauvais_longueur_password'] . '</p>';
        unset($_SESSION['mauvais_longueur_password']);
    }
}
else if($uri ==='/systeme_de_votes/public/index.php/deconnexion'){
    $user_controller->deconnexion();
}

else if($uri ==='/systeme_de_votes/public/index.php/soumission_vote'){
    if (isset($_POST['option_vote_id'])) {
        $user_id = $_POST['user_id'];
        $vote_id = $_POST['vote_id'];
        $option_vote_id = $_POST['option_vote_id'];
        $user_controller->soumission_vote($user_id,$vote_id, $option_vote_id);
    }else{
        $_SESSION['vote_non_choisi'] = 'Veuillez chosir une option pour pouvoir soumettre votre vote !';
        echo '<p class="erreur_dans_login">' .  $_SESSION['vote_non_choisi'] . '</p>';
        unset($_SESSION['vote_non_choisi']);
    }
    
}
else if ($uri === '/systeme_de_votes/public/index.php/success_message') {
    echo '<section class="bloc_après_vote_enregistré" ><img src="../img/icone_confirmation.svg" alt=""><h2>Vote enregistré avec succès !</h2></section>';
}
else if ($uri === '/systeme_de_votes/public/index.php/supprimer_vote') {
    $id_vote = $_POST['id_vote'];
    $admin_controller->supprimer_vote($id_vote);
}
else if ($uri === '/systeme_de_votes/public/index.php/ajout_vote') {

    if (!empty($_POST['option_3_du_vote']))  {
        $titre_du_vote = $_POST['titre_du_vote'];
        $description_du_vote = $_POST['description_du_vote'];
        $option_1_du_vote = $_POST['option_1_du_vote'];
        $option_2_du_vote = $_POST['option_2_du_vote'];
        $option_3_du_vote = $_POST['option_3_du_vote'];   
        $date_et_heure_fin_vote = $_POST['date_et_heure_fin_vote'];
        
        $admin_controller->ajouter_vote_avec_option_3($titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $option_3_du_vote);
        
        if (isset($_SESSION['ajout_réussi'])) {
            echo '<p class="succès">' .  $_SESSION['ajout_réussi'] . '</p>';
            unset($_SESSION['ajout_réussi']);
        }
    }else{
            $titre_du_vote = $_POST['titre_du_vote'];
            $description_du_vote = $_POST['description_du_vote'];
            $option_1_du_vote = $_POST['option_1_du_vote'];
            $option_2_du_vote = $_POST['option_2_du_vote'];
            $date_et_heure_fin_vote = $_POST['date_et_heure_fin_vote'];
            $admin_controller->ajouter_vote_sans_option_3($titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote);
            
            if (isset($_SESSION['ajout_réussi'])) {
                echo '<p class="succès">' .  $_SESSION['ajout_réussi'] . '</p>';
                unset($_SESSION['ajout_réussi']);
            }
        }
    }
//Parser l'url pour la modif des votes
else if ($uri === '/systeme_de_votes/public/index.php/modif_vote') {

    if (!empty($_POST['option_3_du_vote']))  {
        $id_du_vote = $_POST['id_du_vote'];
        $titre_du_vote = $_POST['titre_du_vote'];
        $description_du_vote = $_POST['description_du_vote'];
        
        $option_1_du_vote = $_POST['option_1_du_vote'];
        $option_2_du_vote = $_POST['option_2_du_vote'];
        $option_3_du_vote = $_POST['option_3_du_vote'];   

        $id_option_1_du_vote = $_POST['id_option_1_du_vote'];
        $id_option_2_du_vote = $_POST['id_option_2_du_vote'];
        $id_option_3_du_vote = $_POST['id_option_3_du_vote'];
        $date_et_heure_fin_vote = $_POST['date_et_heure_fin_vote'];
        
        $admin_controller->modifier_vote_avec_option_3($id_du_vote,$titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $option_3_du_vote, $id_option_1_du_vote, $id_option_2_du_vote, $id_option_3_du_vote);
        
        if (isset($_SESSION['modif_réussi'])) {
            echo '<p class="succès">' .  $_SESSION['modif_réussi'] . '</p>';
            unset($_SESSION['modif_réussi']);
        }
    }else{
            $id_du_vote = $_POST['id_du_vote'];
            $titre_du_vote = $_POST['titre_du_vote'];
            $description_du_vote = $_POST['description_du_vote'];
            $option_1_du_vote = $_POST['option_1_du_vote'];
            $option_2_du_vote = $_POST['option_2_du_vote'];
            $id_option_1_du_vote = $_POST['id_option_1_du_vote'];
            $id_option_2_du_vote = $_POST['id_option_2_du_vote'];
            $date_et_heure_fin_vote = $_POST['date_et_heure_fin_vote'];
            $admin_controller->modifier_vote_sans_option_3($id_du_vote, $titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $id_option_1_du_vote, $id_option_2_du_vote);
            
            if (isset($_SESSION['modif_réussi'])) {
                echo '<p class="succès">' .  $_SESSION['modif_réussi'] . '</p>';
                unset($_SESSION['modif_réussi']);
            }
        }
    }


else{
        http_response_code(404);
        echo 'Page introuvable';
    }

//_____________________________________

?>
</body>
</html>