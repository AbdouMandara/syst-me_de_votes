<?php

class User{
private $connexion;

public function __construct()
{
    $this->connexion = require __DIR__ . '/../../config/database.php';   // Récupère la connexion CAR on a retourné dans database.php $connexion
}
/*---------Fonctions à utiliser dans la classe---------------------- */
        //Fonction pour faciliter la sécurisation des données et  pour sécurisation de l'email
    private function securisation_entrée($donnée){
        $donnée = trim($donnée); // Supprimer les espaces en début et fin de chaîne
        $donnée = stripslashes($donnée);
        $donnée = str_replace(["\\", "/"], "",$donnée); // Supprime tous les antislashs et slashs
        $donnée = strip_tags($donnée);
        return $donnée;
    }
    private function suppression_caracteres_speciaux_email($donnée) {
        $donnée = strip_tags($donnée);
        $donnée = preg_replace('/[^a-zA-Z0-9@._\-+]/', '', $donnée); // garde uniquement les caractères autorisés
        return trim($donnée);
    }
    // !utilise filter_sanitize_email  pour sécuriser et voir si la forme de l'email envoyé est correcte
/*______________________________________________________________________ */

    public function inscription($nom, $password, $email){
        $nom = $this->securisation_entrée($nom);
        $password = $this->securisation_entrée($password);
        $email = $this->suppression_caracteres_speciaux_email($email);
        $password_hash = password_hash($password, PASSWORD_DEFAULT); //pour hasher le password donc coder le mot de passe entré en plusieurs autres caractères
        $sql_insertion ='INSERT INTO user (nom, email, mot_de_passe) VALUES (:nom, :email, :mot_de_passe)';
        $requete_insertion = $this->connexion->prepare($sql_insertion); // Utilise $connexion
        $requete_insertion->execute([':nom' => $nom, ':mot_de_passe' => $password_hash, ':email' => $email]);
    }

    public function connexion ($email_user_connexion, $password_user_connexion){
        $sql_recherche='SELECT * FROM user  WHERE email= :email_user_connexion';
        $requete_recherche = $this->connexion->prepare($sql_recherche);
        $email_user_connexion = $this->suppression_caracteres_speciaux_email($email_user_connexion);
        $password_user_connexion = $this ->securisation_entrée($password_user_connexion);
        $requete_recherche->execute([':email_user_connexion'=>$email_user_connexion]);
        $user_trouvé=$requete_recherche->fetch(PDO::FETCH_ASSOC); 

        if(!empty($user_trouvé)){
            return $user_trouvé;
        }else{
            return false;
        }
    }

    public function soumission_du_vote($user_id, $vote_id, $option_vote_id){
        $user_id_soumis=$user_id;
        $vote_id_soumis = $vote_id;
        $option_vote_id_soumis = $option_vote_id; 

        $sql_envoi_des_données_de_vote = 'INSERT INTO votes_utilisateur (user_id, vote_id, option_vote_id) VALUES (:user_id, :vote_id, :option_vote_id)';
        $requete_envoi_des_données_de_vote = $this->connexion->prepare($sql_envoi_des_données_de_vote);
        $requete_envoi_des_données_de_vote->execute([
            ':user_id'=>$user_id_soumis,
            ':vote_id'=>$vote_id_soumis,
            ':option_vote_id'=>$option_vote_id_soumis
        ]);
    }

    public function déjà_voté($id_vote, $id_user){
        $sql_pour_voir_si_ce_user_a_déjà_voté = "SELECT * FROM votes_utilisateur WHERE user_id = :user_id AND vote_id =:vote_id";
        $requete_pour_voir_si_ce_user_a_déjà_voté = $this->connexion->prepare($sql_pour_voir_si_ce_user_a_déjà_voté);
        $requete_pour_voir_si_ce_user_a_déjà_voté->execute(["user_id"=> $id_user, "vote_id"=> $id_vote]);
        return $requete_pour_voir_si_ce_user_a_déjà_voté->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne un tableau d'IDs de votes déjà soumis par un utilisateur.
     * Utile pour initialiser $_SESSION['votes'] lors de la connexion.
     * Retourne [] si aucun vote trouvé.
     */
    public function votes_par_utilisateur($id_user){
        $sql_pour_trouver_vote_fait_par_user_connecté= 'SELECT vote_id FROM votes_utilisateur WHERE user_id = :user_id';
        $requete_pour_trouver_vote_fait_par_user_connecté = $this->connexion->prepare($sql_pour_trouver_vote_fait_par_user_connecté);
        $requete_pour_trouver_vote_fait_par_user_connecté->execute(['user_id' => $id_user]);
        $resultat_pour_trouver_vote_fait_par_user_connecté = $requete_pour_trouver_vote_fait_par_user_connecté->fetchAll(PDO::FETCH_ASSOC);

        // SI pas de vote on retourne un array vide []
        if (!$resultat_pour_trouver_vote_fait_par_user_connecté) return [];
        // Si on trouve on stocke ce qu'on trouve dans le tableau des ids sous forme de int pour se rassurer 😅
        $tableau_des_ids = [];
        foreach ($resultat_pour_trouver_vote_fait_par_user_connecté as $resultat) {
            $tableau_des_ids[] = (int)$resultat['vote_id'];
        }
        // on retourne toutes les valeurs du tableau pour qu'il stocke celà 
        // Dans le controlleur ça sera stocké en occurence par $votes_user 
        return array_values($tableau_des_ids);
    }
}


class Admin{
    private $connexion;

    public function __construct()
    {
        $this->connexion = require __DIR__ . '/../../config/database.php';   // Récupère la connexion CAR on a retourné dans database.php $connexion
    }

    public function suppression_du_vote($id_du_vote_envoyé){
        /*
        DELIMITER $$ 
        CREATE TRIGGER suppression_de_votes_effectués_sur_ce_vote  
        BEFORE DELETE ON votes
        FOR EACH row
        BEGIN
             DELETE FROM votes_utilisateur WHERE vote_id= OLD.id;
        END $$
        DELIMITER ;

        DELIMITER $$ 
        CREATE TRIGGER suppression_des_options_de_ce_vote  
        BEFORE DELETE ON votes
        FOR EACH row
        BEGIN
             DELETE FROM option_votes WHERE vote_id= OLD.id;
        END $$
        DELIMITER ;
        */ //? J'execute d'ab ces triggers dans mon php My Admin
        $sql_suppression_du_vote = "DELETE FROM votes WHERE id = :id_du_vote";
        $requete_suppression_du_vote = $this->connexion->prepare($sql_suppression_du_vote);
        $requete_suppression_du_vote->execute(["id_du_vote" => $id_du_vote_envoyé]);
    }

    // Méthode si l'option 3 est indisponible
     public function ajout_du_vote_sans_option_3($titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote){

        $this->connexion->beginTransaction();

        $sql_pour_insérer_infos_du_vote ="INSERT INTO votes (titre, description, date_fin) VALUES (:titre_du_vote, :description_du_vote, :date_et_heure_fin_vote)";
        $requete_pour_insérer_infos_du_vote =$this->connexion->prepare($sql_pour_insérer_infos_du_vote);
        
        $requete_pour_insérer_infos_du_vote->execute([
            "titre_du_vote"=>$titre_du_vote,
            "description_du_vote"=>$description_du_vote,
            "date_et_heure_fin_vote"=>$date_et_heure_fin_vote
        ]);

        $vote_id = $this->connexion->lastInsertId();
        
        // SQL pour insérer les options 1 et 2
        $sql_pour_insérer_option_1_du_vote = "INSERT INTO option_votes (vote_id,libelle) VALUES (:vote_id, :option_1_du_vote)";
        $requete_pour_insérer_option_1_du_vote = $this->connexion->prepare($sql_pour_insérer_option_1_du_vote);
        
        $requete_pour_insérer_option_1_du_vote->execute([
            "vote_id"=>$vote_id,
            "option_1_du_vote" => $option_1_du_vote
        ]);

        $sql_pour_insérer_option_2_du_vote = "INSERT INTO option_votes (vote_id, libelle) VALUES (:vote_id, :option_2_du_vote)";
        $requete_pour_insérer_option_2_du_vote = $this->connexion->prepare($sql_pour_insérer_option_2_du_vote);
        
        $requete_pour_insérer_option_2_du_vote->execute([
            "vote_id"=>$vote_id,
            "option_2_du_vote" => $option_2_du_vote
        ]);
        $this->connexion->commit();
    }
    

    //Méthode si l'option 3 est disponible
        public function ajout_du_vote_avec_option_3($titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $option_3_du_vote){
        try{
        $this->connexion->beginTransaction();

        $sql_pour_insérer_infos_du_vote ="INSERT INTO votes (titre, description, date_fin) VALUES (:titre_du_vote, :description_du_vote, :date_et_heure_fin_vote)";
        $requete_pour_insérer_infos_du_vote =$this->connexion->prepare($sql_pour_insérer_infos_du_vote);
        $requete_pour_insérer_infos_du_vote->execute(
        [
            "titre_du_vote"=>$titre_du_vote,
            "description_du_vote"=>$description_du_vote,
            "date_et_heure_fin_vote"=>$date_et_heure_fin_vote
        ]);
        /*
        DELIMITER $$
        CREATE TRIGGER recupere_id
        AFTER INSERT ON votes
        FOR EACH row
        BEGIN 
            INSERT INTO option_votes (vote_id) VALUES (NEW.id)
        END $$
        DELIMITER ;
        */

    // 2. Récupérer l'ID du dernier vote inserée
        $vote_id = $this->connexion->lastInsertId();

        $sql_pour_insérer_option_1_du_vote = "INSERT INTO option_votes (vote_id,libelle) VALUES (:vote_id, :option_1_du_vote)";
        $requete_pour_insérer_option_1_du_vote = $this->connexion->prepare($sql_pour_insérer_option_1_du_vote);
        $requete_pour_insérer_option_1_du_vote->execute([
            "vote_id"=>$vote_id,
            "option_1_du_vote" => $option_1_du_vote
        ]);

        $sql_pour_insérer_option_2_du_vote = "INSERT INTO option_votes (vote_id, libelle) VALUES (:vote_id, :option_2_du_vote)";
        $requete_pour_insérer_option_2_du_vote = $this->connexion->prepare($sql_pour_insérer_option_2_du_vote);
        $requete_pour_insérer_option_2_du_vote->execute([
            "vote_id"=>$vote_id,
            "option_2_du_vote" => $option_2_du_vote
        ]);
        
        $sql_pour_insérer_option_3_du_vote = "INSERT INTO option_votes (vote_id, libelle) VALUES (:vote_id, :option_3_du_vote)";
        $requete_pour_insérer_option_3_du_vote = $this->connexion->prepare($sql_pour_insérer_option_3_du_vote);
        $requete_pour_insérer_option_3_du_vote->execute([
            "vote_id"=>$vote_id,
            "option_3_du_vote" => $option_3_du_vote
        ]);
        
        $this->connexion->commit();
        } catch (Exception $e) {
       $this->connexion->rollBack();
       throw $e;
        }
    }

     //-Modification-  Méthode si l'option 3 est indisponible 
     public function modification_du_vote_sans_option_3($id_du_vote,$titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $id_option_1_du_vote, $id_option_2_du_vote, $statut_du_vote ){

        // Nouvelle version : on attend les id des options en plus des libellés
        // $option_1_id et $option_2_id doivent être transmis
        // $option_1_du_vote et $option_2_du_vote sont les nouveaux libellés
        // $this->connexion->beginTransaction();
        $sql_pour_modifier_infos_du_vote ="UPDATE  votes SET titre=:titre_du_vote, description =:description_du_vote, date_fin=:date_et_heure_fin_vote, statut_du_vote = :statut_du_vote WHERE id=:id_du_vote";
        $requete_pour_modifier_infos_du_vote =$this->connexion->prepare($sql_pour_modifier_infos_du_vote);
        $requete_pour_modifier_infos_du_vote->execute([
            "titre_du_vote"=>$titre_du_vote,
            "description_du_vote"=>$description_du_vote,
            "date_et_heure_fin_vote"=>$date_et_heure_fin_vote,
            "id_du_vote"=>$id_du_vote,
            "statut_du_vote"=>$statut_du_vote
        ]);

        // Modifier option 1
        $sql_pour_modifier_option_1_du_vote = "UPDATE option_votes SET libelle = :option_1_du_vote WHERE id = :option_1_id AND vote_id = :id_du_vote";
        $requete_pour_modifier_option_1_du_vote = $this->connexion->prepare($sql_pour_modifier_option_1_du_vote);
        $requete_pour_modifier_option_1_du_vote->execute([
            "option_1_du_vote" => $option_1_du_vote,
            "option_1_id" => $id_option_1_du_vote,
            "id_du_vote"=>$id_du_vote
        ]);

        // Modifier option 2
        $sql_pour_modifier_option_2_du_vote = "UPDATE option_votes SET libelle = :option_2_du_vote WHERE id = :option_2_id AND vote_id = :id_du_vote";
        $requete_pour_modifier_option_2_du_vote = $this->connexion->prepare($sql_pour_modifier_option_2_du_vote);
        $requete_pour_modifier_option_2_du_vote->execute([
            "option_2_du_vote" => $option_2_du_vote,
            "option_2_id" => $id_option_2_du_vote,
            "id_du_vote"=>$id_du_vote
        ]);
        // $this->connexion->commit();
    }
    

    //-Modification- Méthode si l'option 3 est disponible
        public function modification_du_vote_avec_option_3($id_du_vote, $titre_du_vote, $description_du_vote, $date_et_heure_fin_vote, $option_1_du_vote, $option_2_du_vote, $option_3_du_vote, $id_option_1_du_vote, $id_option_2_du_vote, $id_option_3_du_vote){
        try{
        $sql_pour_modifier_infos_du_vote ="UPDATE  votes SET titre=:titre_du_vote, description =:description_du_vote, date_fin=:date_et_heure_fin_vote WHERE id=:id_du_vote";
        $requete_pour_modifier_infos_du_vote =$this->connexion->prepare($sql_pour_modifier_infos_du_vote);
        
        $requete_pour_modifier_infos_du_vote->execute([
            "titre_du_vote"=>$titre_du_vote,
            "description_du_vote"=>$description_du_vote,
            "date_et_heure_fin_vote"=>$date_et_heure_fin_vote,
            "id_du_vote"=>$id_du_vote,
        ]);

        
        // SQL pour insérer les options 1 et 2
        $sql_pour_modifier_option_1_du_vote = "UPDATE option_votes SET libelle = :option_1_du_vote WHERE vote_id = :id_du_vote";
        $requete_pour_modifier_option_1_du_vote = $this->connexion->prepare($sql_pour_modifier_option_1_du_vote);
        
        $requete_pour_modifier_option_1_du_vote->execute([
            "option_1_du_vote" => $option_1_du_vote,
            "option_1_id" => $id_option_1_du_vote,
            "id_du_vote"=>$id_du_vote
        ]);

        $sql_pour_modifier_option_2_du_vote = "UPDATE option_votes SET libelle = :option_2_du_vote WHERE vote_id = :id_du_vote";
        $requete_pour_modifier_option_2_du_vote = $this->connexion->prepare($sql_pour_modifier_option_2_du_vote);
        
        $requete_pour_modifier_option_2_du_vote->execute([
            "option_2_du_vote" => $option_2_du_vote,
            "option_2_id" => $id_option_2_du_vote,
            "id_du_vote"=>$id_du_vote
        ]);

        $sql_pour_modifier_option_3_du_vote = "UPDATE option_votes SET libelle = :option_3_du_vote WHERE vote_id = :id_du_vote";
        $requete_pour_modifier_option_3_du_vote = $this->connexion->prepare($sql_pour_modifier_option_3_du_vote);
        
        $requete_pour_modifier_option_3_du_vote->execute([
            "option_3_du_vote" => $option_3_du_vote,
            "option_3_id" => $id_option_3_du_vote,
            "id_du_vote"=>$id_du_vote
        ]);
        
        // $this->connexion->commit();
        } catch (Exception $e) {
       $this->connexion->rollBack();
       throw $e;
        }
    }

    }
?>