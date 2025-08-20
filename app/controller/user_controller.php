<?php
 require_once __DIR__ . '/../model/user_model.php';
 
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
                $_SESSION['user'] =[
                    'nom'=>$user_trouvé['nom'],
                    'email'=>$user_trouvé['email']
                ];
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
    session_destroy();
    header('HX-Redirect:/systeme_de_votes/public/index.php');
}
#__________________________________________

    public function soumission_vote($user_id, $vote_id, $option_vote_id){
        $this->nouveau_user->soumission_du_vote($user_id, $vote_id, $option_vote_id);
        echo "<script>
                    htmx.ajax('GET', '/systeme_de_votes/public/index.php/success_message', '#success-container')
                  </script>";
        die();
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
    public function modifier_vote_sans_option_3($id_du_vote, $titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $id_option_1_du_vote, $id_option_2_du_vote){
        $this->nouveau_admin->modification_du_vote_sans_option_3($id_du_vote, $titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $id_option_1_du_vote, $id_option_2_du_vote);
        $_SESSION['modif_réussi'] = 'Modification réussie avec succès';
        return $_SESSION['modif_réussi'];
    }

    //  public function obtenir_options_de_vote_et_leurs_nombre($id_du_vote){
    //     $this->nouveau_admin->obtenir_options_de_vote_et_leurs_nombre($id_du_vote);
    //  }
}
?>