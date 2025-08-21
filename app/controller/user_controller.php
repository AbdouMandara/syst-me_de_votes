<?php
require_once __DIR__ . '/../model/user_model.php';
// S'assurer que la session est démarrée pour pouvoir stocker les votes
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
 class UserController{
     private $nouveau_user;
     
     public function __construct(){
        $user = new User();
        $this -> nouveau_user= $user;
    }

    public function afficherFormulaire(){
        require_once __DIR__ . '/../../public/login/login.php';
    }

    public function inscription_user($nom, $password1, $email){
         if (!empty($email)&& !empty($password1) && !empty($nom)) {
            $longueur_min_password =5;
            if (mb_strlen($password1, 'UTF-8') < $longueur_min_password){
                $_SESSION['mauvais_longueur_password']='Votre mot de passe doit excéder ' .$longueur_min_password.  ' caractères';
                return $_SESSION['mauvais_longueur_password'];
            }else{
                $this->nouveau_user->inscription($nom, $password1,$email);
                $_SESSION['user'] =[
                    'nom'=>$nom,
                    'email'=>$email
                ];
                header('HX-Redirect:/systeme_de_votes/app/view/user/src/index_user.php');
                exit();
            }
    }else{
         if ($nom=='' || $nom==' ') :
            $_SESSION['nom_vide'] = 'Veuillez entrer votre nom !';
            return $_SESSION['nom_vide'];
        endif;
          if ($email=='' || $email==' ') :
            $_SESSION['email_vide'] = 'Veuillez entrer votre email !';
            return $_SESSION['email_vide'];
        endif;
        if ($password1=='' || $password1==' ' ) :
            $_SESSION['password_vide'] = 'Veuillez entrer votre mot de passe !';
            return $_SESSION['password_vide'];
        endif;
    }
    }

     public function validation_champs($email_user_connexion, $password_user_connexion){
        if (!filter_var($email_user_connexion, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mauvais_email']='Veuillez entrer un email conforme';
            return $_SESSION['mauvais_email'];
        }
        $longueur_min_password =5;
        if (mb_strlen($password_user_connexion, 'UTF-8') < $longueur_min_password){
            $_SESSION['mauvais_longueur_password']='Votre mot de passe doit excéder ' .$longueur_min_password.  ' caractères';
            return $_SESSION['mauvais_longueur_password'];
        }
    }
    public function connexion_user($email_user_connexion, $password_user_connexion){
        if (!empty($email_user_connexion)&& !empty($password_user_connexion)) {
            $this->nouveau_user->connexion($email_user_connexion, $password_user_connexion);
            $user_trouvé = $this->nouveau_user->connexion($email_user_connexion, $password_user_connexion);
        // Pour voir si on a trouvé un résultat et il n'est pas vide car avec isset j'ai un tableau vide car ça sert à voir si la variable existe or empty sert à voir si ce n'est pas vide
            if ($user_trouvé) {
                if(password_verify($password_user_connexion, $user_trouvé["mot_de_passe"])){     
                    if ($user_trouvé['role']==='admin') {
                $_SESSION['admin'] =[
                        'nom'=>$user_trouvé['nom'],
                        'email'=>$user_trouvé['email']
                    ];
                    header('HX-Redirect:/systeme_de_votes/app/view/admin/src/index_admin.php');
                    exit();
                }
                // On stocke également l'ID pour pouvoir charger/associer les votes
                $_SESSION['user'] =[
                    'id' => $user_trouvé['id'],
                    'nom'=>$user_trouvé['nom'],
                    'email'=>$user_trouvé['email']
                ];

                // Charger les votes déjà effectués par cet utilisateur et les stocker en session
                /**
                 * On récupère l'id du user connecté on envoie à la méthode votes_par_utilisateur pour prendre ce qu'il a voté
                 * 
                */
                $votes_user = $this->nouveau_user->votes_par_utilisateur($user_trouvé['id']);
                if (!empty($votes_user) && is_array($votes_user)) {
                    // s'assurer que ce sont des valeurs simples (ids) 
                    // On stocke les votes user dans une variable de SESSION pour s'en souvenir pour son utilisation
                    $_SESSION['votes'] = array_values($votes_user);
                } else {
                    $_SESSION['votes'] = [];
                }
                // Si rien ne marche il raffraichit la page
                header('HX-Redirect:/systeme_de_votes/app/view/user/src/index_user.php');
                }else{
                    return $_SESSION['mot_de_passe_incorrect'] = 'mot de passe incorrect';
                }
            }else{
                $_SESSION['email_user_non_trouvé'] = 'Aucun utilisateur ne correspond à cet e-mail !';
                return $_SESSION['email_user_non_trouvé'];
            }
    }else{
           if ($email_user_connexion==='' || $email_user_connexion===' ') {
            $_SESSION['email_user_connexion_vide'] = 'Veuillez entrer votre adresse e-mail !';
            return $_SESSION['email_user_connexion_vide'];
        }
           if ($password_user_connexion==='' || $password_user_connexion===' ') {
            $_SESSION['password_user_connexion_vide'] = 'Veuillez entrer votre mot de passe !';
            return $_SESSION['password_user_connexion_vide'];
        }
    }
}

public function deconnexion(){
    // Quand je me déconnectes je vides la session de mes infos
    $_SESSION['votes']=[];
    session_destroy();
    header('HX-Redirect:/systeme_de_votes/public/index.php');
}
#__________________________________________

    public function soumission_vote($user_id, $vote_id, $option_vote_id){
        $this->nouveau_user->soumission_du_vote($user_id, $vote_id, $option_vote_id);
        // Mettre à jour la session pour marquer ce vote comme déjà effectué
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Si la variable de session n'existe pas encore et c'est pas un tableau on l'initialise 
        // C'est lors de la soumission du 1er vote
        if (!isset($_SESSION['votes']) || !is_array($_SESSION['votes'])) {
            $_SESSION['votes'] = [];
        }
        // Si le array de session pour les votes est déjà initialisé alors on y stocke l'id du nouveau vote soumis
        $vote_id_int = is_numeric($vote_id) ? (int)$vote_id : $vote_id;
        if (!in_array($vote_id_int, $_SESSION['votes'], true)) {
            $_SESSION['votes'][] = $vote_id_int;
        }

        // Retour HTMX / message et bouton "déjà voté"
        echo "<script>
                        htmx.ajax('GET', '/systeme_de_votes/public/index.php/success_message', '#success-container')
                  </script>";
        echo ' <button class="bouton_déjà_voté"  disabled>Dejà voté ✅</button>';
        die();
    }

    public function déja_voté($id_vote, $id_user){
        // Vérifier d'abord en session (plus rapide et isolé par session)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        //Lors du chargement de la page On verifie si id du vote est déjà dans le array SESSION des votes si c'est le cas on renvoie TRUE 
        if (isset($_SESSION['votes']) && is_array($_SESSION['votes'])) {
            if (in_array($id_vote, $_SESSION['votes'], true)) {
                return true;
            }
        }

        // Sinon, vérifier en base de données on stocke le resultat obtenu
        $result = $this->nouveau_user->déjà_voté($id_vote, $id_user);
        if ($result) {
            // stocker en session pour appels suivants
            // on vérifie d'ab si le tableau des votes SESSION existe si et s'il est un array si c'est pas le cas on lui initialise un array 
            if (!isset($_SESSION['votes']) || !is_array($_SESSION['votes'])) {
                $_SESSION['votes'] = [];
            }
            // on vérifie si il est un tableau et retourne TRUE si oui on y stocke l'ID
            if (!in_array($id_vote, $_SESSION['votes'], true)) {
                $_SESSION['votes'][] = $id_vote;
            }
            return true;
        }
        return false;

    }
}

class AdminController{
    private $nouveau_admin;
     
     public function __construct(){
        $admin = new Admin();
        $this -> nouveau_admin= $admin;
    }

    public function supprimer_vote($id_du_vote_envoyé){
        $this->nouveau_admin->suppression_du_vote($id_du_vote_envoyé);
    }

    public function ajouter_vote_avec_option_3($titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $option_3_du_vote){
        $this->nouveau_admin->ajout_du_vote_avec_option_3($titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $option_3_du_vote);
        $_SESSION['ajout_réussi'] = 'Ajouté avec succès';
        return $_SESSION['ajout_réussi'];
    }
    public function ajouter_vote_sans_option_3($titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote){
        $this->nouveau_admin->ajout_du_vote_sans_option_3($titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote);
        $_SESSION['ajout_réussi'] = 'Ajouté avec succès';
        return $_SESSION['ajout_réussi'];
    }
    // Pour modifier les votes
    public function modifier_vote_avec_option_3($id_du_vote, $titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $option_3_du_vote, $id_option_1_du_vote, $id_option_2_du_vote, $id_option_3_du_vote){
        $this->nouveau_admin->modification_du_vote_avec_option_3($id_du_vote, $titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $option_3_du_vote, $id_option_1_du_vote, $id_option_2_du_vote, $id_option_3_du_vote);
        $_SESSION['modif_réussi'] = 'Modification réussie avec succès';
        return $_SESSION['modif_réussi'];
    }
    public function modifier_vote_sans_option_3($id_du_vote, $titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $id_option_1_du_vote, $id_option_2_du_vote, $statut_du_vote){
        $this->nouveau_admin->modification_du_vote_sans_option_3($id_du_vote, $titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $id_option_1_du_vote, $id_option_2_du_vote, $statut_du_vote);
        $_SESSION['modif_réussi'] = 'Modification réussie avec succès';
        return $_SESSION['modif_réussi'];
    }


}
?>