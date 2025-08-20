<?php
session_start();
require_once __DIR__ . '/../../../../config/database.php';
// require_once __DIR__ . '/../../../../public/index.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <script src="/systeme_de_votes/public/htmx.min-v-2.0.6.js" defer></script>
    <!-- <script src="/systeme_de_votes/public/sweetAlert.min-v-2.12.js" defer></script> -->
    <title>Accueil | User</title>
</head>
<body>
    <header>
        <div class="partie-gauche-du-header">
            <li class="logo">App de votes</li>
            <!-- ! mets plutot une icone de maison comme c'est l'accueil -->
            <div class="bloc_accueil"><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="15px" fill="#fff"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg> <span>Accueil</span></div>
        </div>

        <div class="partie-droite-du-header">
            <div class="bloc_nom_user"><img src="../img/person.svg" alt=""><span><?= strip_tags($_SESSION['user']['email']) ?></span></div>

            <div class="bloc_deconnexion"
                    hx-post="/systeme_de_votes/public/index.php/deconnexion"
                    hx-confirm="Veux-tu réellement te déconnecter ?">
                <img src="../img/logout_30dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt="">
                <span>Déconnexion</span>
            </div>
        </div>
    </header>
    
    <main>
        <section class="bloc_hero">
            <div class="bloc_texte_accueil">
                <p class="titre_accueil">Bienvenue, <span><?= strip_tags($_SESSION['user']['nom']) ?></span> !</p>
                <p class="sous_titre_accueil">Decouvrez les votes en cours et participez à ceux vous interessent.</p>
            </div>

            <div class="bloc_infos_sur_votes">
                    <!-- Change les images  -->
                     <?php
                        $sql_pour_compter_nbre_votes_actifs = 'SELECT count(id) FROM votes WHERE statut_du_vote=:statut_du_vote';
                        $requete_pour_compter_nbre_votes_actifs=$connexion->prepare($sql_pour_compter_nbre_votes_actifs);
                        $requete_pour_compter_nbre_votes_actifs->execute([':statut_du_vote' => 'Actif']);
                        $resultat_pour_compter_nbre_votes_actifs=$requete_pour_compter_nbre_votes_actifs->fetch();
                     ?>
                    <svg  xmlns="http://www.w3.org/2000/svg"  viewBox="0 -960 960 960" fill="#82c91e" class="icone-1"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg>
                    <div class="texte_dans_bloc_infos_sur_votes">
                        <p>Votes actifs</p>
                        <h4><?= $resultat_pour_compter_nbre_votes_actifs['count(id)'] ?></h4>
                    </div>
            </div>

            <div class="bloc_infos_sur_votes">
                    <!-- Change les images  -->
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"  fill="#fff" class="icone-2"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg>
                    <div class="texte_dans_bloc_infos_sur_votes">
                    <?php 
                                $email_user = $_SESSION['user']['email'];
                                $sql_pour_trouver_id_du_user = "SELECT id FROM user WHERE email = :email";
                                $requete_pour_trouver_id_du_user = $connexion->prepare($sql_pour_trouver_id_du_user);
                                $requete_pour_trouver_id_du_user->execute(['email' => $email_user]);
                                $resultat_pour_trouver_id_du_user= $requete_pour_trouver_id_du_user->fetch();
                                $id_user = $resultat_pour_trouver_id_du_user['id'];
                                $sql_pour_compter_nbre_votes_participations= "SELECT count(id) FROM votes_utilisateur WHERE user_id=$id_user";
                                $requete_pour_compter_nbre_votes_participations=$connexion->prepare($sql_pour_compter_nbre_votes_participations);
                                $requete_pour_compter_nbre_votes_participations->execute();
                                $resultat_pour_compter_nbre_votes_participations=$requete_pour_compter_nbre_votes_participations->fetch();
                        ?>
                        <p>Mes participations</p>
                        <h4><?= $resultat_pour_compter_nbre_votes_participations['count(id)'] ?></h4>
                    </div>
            </div>

            <div class="bloc_infos_sur_votes">
                <!-- Change les images  -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"fill="#74c0fc" class="icone-3"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg>
                    <div class="texte_dans_bloc_infos_sur_votes">
                          <?php
                        $sql_pour_compter_nbre_votes_terminés = 'SELECT count(id) FROM votes WHERE statut_du_vote=:statut_du_vote';
                        $requete_pour_compter_nbre_votes_terminés=$connexion->prepare($sql_pour_compter_nbre_votes_terminés);
                        $requete_pour_compter_nbre_votes_terminés->execute([':statut_du_vote' => 'Terminé']);
                        $resultat_pour_compter_nbre_votes_terminés=$requete_pour_compter_nbre_votes_terminés->fetch();
                     ?>
                        <p>Votes terminés</p>
                        <h4><?= $resultat_pour_compter_nbre_votes_terminés['count(id)'] ?></h4>
                    </div>
            </div>

        </section>

        <section class="bloc_sur_votes_en_cours">
            <div class="titre_de_bloc_sur_votes_en_cours"><svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg><span>Votes en cours</span></div>
            <div class="grille_des_differents_votes_en_cours">
                <?php
                    $sql_pour_obtenir_votes ='SELECT * FROM votes WHERE statut_du_vote= :statut_du_vote ORDER BY id DESC';
                    $requete_pour_obtenir_votes = $connexion->prepare($sql_pour_obtenir_votes);
                    $requete_pour_obtenir_votes->execute(['statut_du_vote' => 'Actif']);
                    $resultat_de_la_requete_pour_obtenir_votes = $requete_pour_obtenir_votes->fetchAll();
                    foreach ($resultat_de_la_requete_pour_obtenir_votes as $resultat) {
                ?>
                
                <!-- Votes en cours -->
                <div class="votes_en_cours">
                    <div class="header_votes_en_cours">
                        <p><span class="titre_vote_en_cours"><?= $resultat['titre'] ?></span> <span class="description_vote_en_cours"><?= $resultat['description'] ?></span></p>
                        <button class="statut-du-vote"><?= $resultat['statut_du_vote'] ?></button>
                    </div>
                    
                    <div class="corps_votes_en_cours">
                         <?php
                                            $sql_pour_obtenir_nbre_participants ='SELECT * FROM votes_utilisateur WHERE vote_id=:id_du_vote';
                                            $requete_pour_obtenir_nbre_participants = $connexion->prepare($sql_pour_obtenir_nbre_participants);
                                            $requete_pour_obtenir_nbre_participants->execute(['id_du_vote' => $resultat['id']]);
                                            $resultat_de_la_requete_pour_obtenir_nbre_participants = $requete_pour_obtenir_nbre_participants->fetchAll();
                                             $total_participants = count($resultat_de_la_requete_pour_obtenir_nbre_participants);
                            ?>
                        <div class="participants"><img src="../img/users.svg" alt=""> <span><?= $total_participants ?> Participants</span></div>
                        <!-- Change image -->
                        <div class="date_de_fin"><img src="../img/users.svg" alt=""> <span>Fin : <?= $resultat['date_fin'] ?></span></div>
                    </div>
                    <button class="bouton_de_vote" data-id-du-btn-vote="<?= $resultat['id'] ?>"   id="bouton_de_vote_<?= $resultat['id'] ?>" >Voter maintenant</button>
                    <!-- Le btn ci apparaitra quand on a déja voté il a la classe "bouton_pour_voir_resultat'' car j'avais la flemme de refaire le css unique rien que pour ce btn-->
                    <button class="bouton_déjà_voté" id="bouton_déjà_voté_<?= $resultat['id'] ?>" data-id-du-btn-déjà-voté="<?= $resultat['id'] ?>">Dejà voté ✅</button>
                </div>
                <?php
                    } //fin du 'for each' pour afficher les votes 'actifs'
                ?>
                <!-- -------------------------- -->
            </div>
        </section>

         <section class="bloc_sur_votes_terminés"> <!--Change les images-->
            <div class="titre_de_bloc_sur_votes_terminés"><svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg><span>Votes terminés</span></div>
            <div class="grille_des_differents_votes_terminés">
                
                <!-- Votes en cours -->
                  <?php
                    $sql_pour_obtenir_votes_terminés ='SELECT * FROM votes WHERE statut_du_vote= :statut_du_vote';
                    $requete_pour_obtenir_votes_terminés = $connexion->prepare($sql_pour_obtenir_votes_terminés);
                    $requete_pour_obtenir_votes_terminés->execute(['statut_du_vote' => 'Terminé']);
                    $resultat_de_la_requete_pour_obtenir_votes_terminés = $requete_pour_obtenir_votes_terminés->fetchAll();
                    foreach ($resultat_de_la_requete_pour_obtenir_votes_terminés as $resultat) {
                        
                                            $sql_pour_obtenir_nbre_participants ='SELECT * FROM votes_utilisateur WHERE vote_id=:id_du_vote';
                                            $requete_pour_obtenir_nbre_participants = $connexion->prepare($sql_pour_obtenir_nbre_participants);
                                            $requete_pour_obtenir_nbre_participants->execute(['id_du_vote' => $resultat['id']]);
                                            $resultat_de_la_requete_pour_obtenir_nbre_participants = $requete_pour_obtenir_nbre_participants->fetchAll();
                                             $total_participants = count($resultat_de_la_requete_pour_obtenir_nbre_participants);
                ?>      
                <div class="votes_terminés">
                    <div class="header_votes_terminés">
                        <p><span class="titre_vote_terminés"><?= $resultat['titre']?></span> <span class="description_vote_terminés"><?= $resultat['description']?></span></p>
                        <button class="statut-du-vote"><?= $resultat['statut_du_vote']?></button>
                    </div>

                    <div class="corps_votes_terminés">
                        <div class="participants"><img src="../img/users.svg" alt=""> <span><?= $total_participants ?> Participants</span></div>
                        <!-- Change image -->
                        <div class="date_de_fin"><img src="../img/users.svg" alt=""> <span>Terminé : <?= $resultat['date_fin']?></span></div>
                    </div>
                        <button class="bouton_pour_voir_resultat"  id="bouton_pour_voir_resultat_<?= $resultat['id'] ?>" data-id-du-btn-vote-terminé="<?= $resultat['id'] ?>" >Voir les résultats</button>
                    </div>
                        <?php
                    }
                        ?>
                </div>
                <!-- -------------------------- -->
        </section>

        <!-- Section ou bloc qui apparaitra lorsqu'on cliquera sur 'voter maintenant' -->
                <?php
                    $sql_pour_obtenir_votes ='SELECT * FROM votes WHERE statut_du_vote= :statut_du_vote';
                    $requete_pour_obtenir_votes = $connexion->prepare($sql_pour_obtenir_votes);
                    $requete_pour_obtenir_votes->execute(['statut_du_vote' => 'Actif']);
                    $resultat_de_la_requete_pour_obtenir_votes = $requete_pour_obtenir_votes->fetchAll();
                    foreach ($resultat_de_la_requete_pour_obtenir_votes as $resultat) {
                        
                                            $sql_pour_obtenir_nbre_participants ='SELECT * FROM votes_utilisateur WHERE vote_id=:id_du_vote';
                                            $requete_pour_obtenir_nbre_participants = $connexion->prepare($sql_pour_obtenir_nbre_participants);
                                            $requete_pour_obtenir_nbre_participants->execute(['id_du_vote' => $resultat['id']]);
                                            $resultat_de_la_requete_pour_obtenir_nbre_participants = $requete_pour_obtenir_nbre_participants->fetchAll();
                                             $total_participants = count($resultat_de_la_requete_pour_obtenir_nbre_participants);
                ?>                      
         <section class="bloc_vote_cliqué_qui_est_en_cours" id="bloc-vote-cliqué-qui-est-en-cours-<?= $resultat['id'] ?>" data-id-bloc-où-on-vote ="<?= $resultat['id'] ?>">
            
            <button class="retour_a_accueil" data-id-du-btn-retour-a-accueil="<?= $resultat['id'] ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" >
                    <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                </svg>
                <span>Retour à l'accueil </span>
            </button>

            <div class="presentation_vote_cliqué_qui_est_en_cours">
                    <div class="header_bloc_vote_cliqué_qui_est_en_cours">
                            <!-- Change les images  -->
                            <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#000"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg>
                            <div class="texte_dans_bloc_vote_cliqué_qui_est_en_cours">
                                <h3><?= $resultat['titre']  ?></h3>
                                <p><?= $resultat['description']  ?></p>
                            </div>
                        </div>
                        
                        <div class="corps_vote_cliqué_qui_est_en_cours">
                            <div class="participants"><img src="../img/users.svg" alt=""> <span><?= $total_participants ?> Participants actuels</span></div>
                            <!-- Change image -->
                            <div class="date_de_fin"><img src="../img/users.svg" alt=""> <span>Fin du vote : <span class="date_fin_vote"><?= $resultat['date_fin'] ?></span></span></div>
                        </div>
                    </div>
                    
                    <div class="bloc_options_du_vote_cliqué_qui_est_en_cours">
                        <h4>Choississez votre option : </h4>
                        <form action="/systeme_de_votes/public/index.php/soumission_votes" method="POST"
                                    hx-post="/systeme_de_votes/public/index.php/soumission_vote"
                                    hx-target="#errors-<?= $resultat['id']?>">

                                    <div class="errors" id="errors-<?= $resultat['id']?>"></div>
                            <?php
                                $sql_pour_afficher_options ='SELECT id, libelle FROM option_votes WHERE vote_id='.$resultat['id'];
                                $requete_pour_afficher_options = $connexion->prepare($sql_pour_afficher_options);
                                $requete_pour_afficher_options->execute();
                                $resultat_pour_afficher_options = $requete_pour_afficher_options->fetchAll();
                                
                                #Ce qu' on enverra à la bd   Nous récuperons l'id du user    
                                foreach ($resultat_pour_afficher_options as $resultat_option){
                                $email_user = $_SESSION['user']['email'];
                                    $sql_pour_trouver_id_du_user_votant = "SELECT id FROM user WHERE email = :email";
                                    $requete_pour_trouver_id_du_user_votant = $connexion->prepare($sql_pour_trouver_id_du_user_votant);
                                    $requete_pour_trouver_id_du_user_votant->execute(['email' => $email_user]);
                                    $resultat_pour_trouver_id_du_user_votant = $requete_pour_trouver_id_du_user_votant->fetch();
                            ?>

                                <input type="hidden" name="user_id" value="<?= $resultat_pour_trouver_id_du_user_votant['id'] ?>"> <!-- Pour l'id du vote choisie-->
                                <input type="hidden" name="vote_id" value="<?= $resultat['id'] ?>"> <!-- Pour l'id du vote choisie-->
                                <!-- ---------------------------- -->
                                <label class="bloc_option"> <!-- Je mets tout cela dans un label car quand je cliques sur le label il va me 'checked' le input-->
                                    <!-- Pour l'id de l'option votée -->
                                    <input type="radio" name="option_vote_id" value="<?= $resultat_option['id'] ?>" id="option-<?= $resultat_option['id'] ?>">
                                    <label for="option-<?= $resultat_option['id'] ?>"><?= $resultat_option['libelle'] ?></label>
                                </label>
                            <?php
                            }
                            ?>
                            
                            <div class="boutons_action_sur_vote_cliqué">
                                <button type="submit" name="confirme_vote" class="confirme_vote"
                                                hx-post="/systeme_de_votes/public/index.php/soumission_vote"
                                                hx-target="#errors-<?= $resultat['id'] ?>"
                                                id="bouton_soumission_du_vote_en_cours_<?= $resultat['id'] ?>" 
                                                data-bouton-soumission-du-vote-en-cours ="<?= $resultat['id'] ?>"
                                                 >Confirmer mon vote</button>
                                <!-- <button type="submit" name="annule_vote" class="annule_vote">Annuler</button> -->
                            </div>
                        </form>
                        </div>
                    </div>

            <p class="notez-bien"> <strong>Important : </strong> Vous ne pouvez voter qu'une seule fois pour ce sondage. Assurez-vous de votre choix avant de confirmer.</p>
         </section>
         <?php
                    }
         ?>
         <div id="success-container"></div>

         <!-- section pour voir les résultats de chaque vote -->
          <?php
                    $sql_pour_obtenir_votes_terminés ='SELECT * FROM votes WHERE statut_du_vote= :statut_du_vote';
                    $requete_pour_obtenir_votes_terminés = $connexion->prepare($sql_pour_obtenir_votes_terminés);
                    $requete_pour_obtenir_votes_terminés->execute(['statut_du_vote' => 'Terminé']);
                    $resultat_de_la_requete_pour_obtenir_votes_terminés = $requete_pour_obtenir_votes_terminés->fetchAll();
                    foreach ($resultat_de_la_requete_pour_obtenir_votes_terminés as $resultat) {
                ?>      
         <section class="bloc_vote_cliqué_pour_voir_ses_résultats" id="bloc-vote-cliqué-pour-voir-résultat-<?= $resultat['id'] ?>" data-id-bloc-voir-résultat ="<?= $resultat['id'] ?>">
            
            <button class="retour_a_accueil"  id="retour_a_accueil_du_bloc_voir_resultat" data-id-du-btn-retour-a-accueil-pour-voir-resultat="<?= $resultat['id'] ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" >
                    <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                </svg>
                <span>Retour à l'accueil </span>
            </button>

            <div class="header_bloc_vote_cliqué_pour_voir_ses_résultats">
                         <div class="header_du_vote_terminé">
                                <!-- Change les images  -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="15px" fill="#000"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg>
                                <div class="texte_dans_bloc_vote_cliqué_pour_voir_ses_résultats">
                                    <h3><?= $resultat['titre']?></h3>
                                    <p><?= $resultat['description']?></p>
                                </div>
                        </div>
                        <div class="infos_du_vote_terminé">
                        <?php
                            try {
                                // On va trouver le total de votes votés pour ce vote
                                $sql_pour_avoir_le_total_de_votes = "SELECT * FROM votes_utilisateur WHERE vote_id = :vote_id";
                                $requete_pour_avoir_le_total_de_votes = $connexion->prepare($sql_pour_avoir_le_total_de_votes);
                                $requete_pour_avoir_le_total_de_votes->execute(['vote_id' => $resultat['id']]);
                                $resultat_pour_avoir_le_total_de_votes = $requete_pour_avoir_le_total_de_votes->fetchAll();
                                $total_votes = count($resultat_pour_avoir_le_total_de_votes);
                                
                                // On va maintenant trouver le nombre total d'options
                                $sql_pour_avoir_le_total_d_options = "SELECT * FROM option_votes WHERE vote_id = :vote_id";
                                $requete_pour_avoir_le_total_d_options = $connexion->prepare($sql_pour_avoir_le_total_d_options);
                                $requete_pour_avoir_le_total_d_options->execute(['vote_id' => $resultat['id']]);
                                $resultat_pour_avoir_le_total_d_options = $requete_pour_avoir_le_total_d_options->fetchAll();
                                $total_options = count($resultat_pour_avoir_le_total_d_options);
                                // On va maintenant trouver l'option la plus votée
                                            // On va d'abord trouver l'id de l'option  correcte
                                $id_correct = $resultat['id'];
                                $sql = "SELECT option_vote_id, COUNT(*) AS nb_votes
                                        FROM votes_utilisateur
                                        INNER JOIN option_votes ON votes_utilisateur.option_vote_id = option_votes.id
                                        WHERE votes_utilisateur.vote_id = :id_correct
                                        GROUP BY option_vote_id
                                        ORDER BY nb_votes DESC
                                        LIMIT 1";
                                // Préparation de la requête manquante
                                $requete = $connexion->prepare($sql);
                                //Bind veut dire 'combiner'
                                $requete->bindParam(':id_correct', $id_correct, PDO::PARAM_INT);
                                $requete->execute();
                                $result = $requete->fetch(PDO::FETCH_ASSOC);

                                if ($result) {
                                    $option_la_plus_votee = $result['option_vote_id'];
                                
                                    // Maintenant récuperons le libellé de l'option la plus votée
                                    $sql_pour_obtenir_libelle_option = "SELECT libelle FROM option_votes WHERE id = :id_option";
                                    $requete_pour_obtenir_libelle_option = $connexion->prepare($sql_pour_obtenir_libelle_option);
                                    $requete_pour_obtenir_libelle_option->execute(['id_option' => $option_la_plus_votee]);
                                    $resultat_pour_obtenir_libelle_option = $requete_pour_obtenir_libelle_option->fetch();
                                    $libelle_option_la_plus_votee = $resultat_pour_obtenir_libelle_option['libelle'];

                                    // Affichons le nombre de fois que ce vote a été voté
                                    $sql_pour_obtenir_nb_votes = "SELECT COUNT(id) AS nb_votes FROM votes_utilisateur WHERE option_vote_id = :option_vote_id";
                                    $requete_pour_obtenir_nb_votes = $connexion->prepare($sql_pour_obtenir_nb_votes);
                                    $requete_pour_obtenir_nb_votes->execute(['option_vote_id' => $option_la_plus_votee]);
                                    $resultat_pour_obtenir_nb_votes = $requete_pour_obtenir_nb_votes->fetch(PDO::FETCH_ASSOC);
                                    $nb_fois_option_plus_voté = $resultat_pour_obtenir_nb_votes['nb_votes'];

                                    //Calculons le pourcentage de votes pour cette option
                                    $pourcentage_option_plus_voté = ($nb_fois_option_plus_voté / $total_votes) * 100;
                                    
                            } else {
                                    $libelle_option_la_plus_votee = "Aucun vote trouvée pour le moment";
                                    $nb_fois_option_plus_voté = 0;
                                    $pourcentage_option_plus_voté = 0;
                                }
                        } catch (PDOException $e) {
                            echo "Erreur SQL : " . $e->getMessage();
                        }
                        ?>
                            <div class="info1 info">
                                <p style="color:#845ef7; font-weight: var(--font-weight-primary); font-size: var(--font-size-subtitle);"><?= $total_votes ?></p>
                                <p>Total des votes</p>
                            </div>

                            <div class="info2 info">
                                <p style="color:#1c7ed6; font-weight: var(--font-weight-primary); font-size: var(--font-size-subtitle);"><?= $total_options ?></p>
                                <p>Options disponibles</p>
                            </div>
                            <div class="info3 info">
                                <p style="color:#2f9e44; font-weight: var(--font-weight-primary); font-size: var(--font-size-subtitle);"><?= htmlspecialchars($pourcentage_option_plus_voté) ?>%</p>
                                <p>Meilleur score</p>
                            </div>
                            <div class="info4 info">
                                <p style="font-size: var(--font-size-subtitle);"><strong><?= $resultat['statut_du_vote']?></strong></p>
                                <p>Cloturé</p>
                            </div>
                        </div>
                    </div>

                        <div class="bloc_option_plus_voté">
                            <div class="titre_option_plus_voté">
                               <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M280-120v-80h160v-124q-49-11-87.5-41.5T296-442q-75-9-125.5-65.5T120-640v-40q0-33 23.5-56.5T200-760h80v-80h400v80h80q33 0 56.5 23.5T840-680v40q0 76-50.5 132.5T664-442q-18 46-56.5 76.5T520-324v124h160v80H280Zm0-408v-152h-80v40q0 38 22 68.5t58 43.5Zm200 128q50 0 85-35t35-85v-240H360v240q0 50 35 85t85 35Zm200-128q36-13 58-43.5t22-68.5v-40h-80v152Zm-200-52Z"/></svg>
                                <p>Option la plus votée</p>
                            </div>

             
                <div class="détail_option_plus_voté">
                    <h4><?= htmlspecialchars($libelle_option_la_plus_votee) ?></h4>
                    <p><?= htmlspecialchars($nb_fois_option_plus_voté) ?> votes (<?= htmlspecialchars($pourcentage_option_plus_voté) ?>%)</p>
                </div>

                        </div>

         <section class="graphiques_resultats">
            <p>Repartiton des votes en pourcentages</p>
            <div class="graphe">
                <!-- A remplir avec celui de chart.js -->
            </div>
         </section>

         </section>
                        <?php
                    }
         ?>
         <!-- 
         
         <section class="resultats_avec_détails">
            <p>Résultats détaillés</p>

            <div class="resultat_option">
                <div class="option">
                    <div class="header_option">
                        <p class="nom_option">Logo A- Moderne</p>
                        <p><span class="nbre_votes">52 votes</span> <span class="pourcentage_votes">39.9%</span></p>
                    </div>
                    <div class="barre_de_pourcentage"></div>
                </div>

                <div class="option">
                    <div class="header_option">
                        <p class="nom_option">Logo B- Moderne</p>
                        <p><span class="nbre_votes">41 votes</span> <span class="pourcentage_votes">25.6%</span></p>
                    </div>
                    <div class="barre_de_pourcentage"></div>
                </div>

            </div>
         </section> -->

         <div class="overflow_blanc"></div>
    </main>
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="./script.js" defer></script>
</body>
</html>