<?php
session_start();
require_once __DIR__ . '/../../../../config/database.php';
require_once __DIR__ . '/../../../controller/user_controller.php'

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
            <script src="/systeme_de_votes/public/chart_js/chart.umd.min.js"></script>

    <script src="/systeme_de_votes/public/htmx.min-v-2.0.6.js" defer></script>
    <title>Dashboard | Admin</title>
       <script src="./script.js" defer></script>
</head>
<body>
    <header>
        <div class="partie-gauche-du-header">
            <li class="logo">Dashboard</li>
            <!-- ! mets plutot une icone de maison comme c'est l'accueil -->
            <div class="bloc_accueil"><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="15px" fill="#fff"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg> <span>Accueil</span></div>
        </div>

        <div class="partie-droite-du-header">
            <div class="bloc_nom_user"><img src="../img/person.svg" alt=""><span><?=$_SESSION['admin']['email'] ?></span></div>
            <div class="bloc_deconnexion"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000"><path d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 320Zm0-400Z"/></svg><span>Ajout admin</span></div>
            <div class="bloc_deconnexion" name="deconnexion_admin"
                    hx-post="/systeme_de_votes/public/index.php/deconnexion"
                    hx-confirm="Veux-tu réellement te déconnecter ?">
            <img src="../img/logout_30dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt="">
            <span>Déconnexion</span></div>
        </div>
    </header>

    <main>
        
        <section class="bloc_hero">
            <div class="bloc_texte_accueil">
                <!-- Le nom du user restera dans ce span -->
                <p class="titre_accueil">Bienvenue, sur votre dashboard Mr <span><?=$_SESSION['admin']['nom'] ?></span> !</p>
                <p class="sous_titre_accueil">Vous pouvez gérer votre plateforme en toute sérenité.</p>
            </div>
            </section>

            <section class="bloc_actions_sur_votes">
                <div class="titre_pour_actions_sur_votes">
                    <p>Voici les différentes actions que vous pouvez effectuer sur votre dashboard : </p>
                </div>

                    <div>

                        <button class="ajout_du_vote" id="ajout_du_vote">
                            Ajouter un vote
                        </button>
                        <button class="ajout_du_vote" id="gerer_user">
                            Gestion des utilisateurs
                        </button>
                    </div>

            </section>

            <!-- Formulaire pour ajouter un nouveau vote  -->
            <form action="/systeme_de_votes/public/index.php/ajout_vote" method="post" class="formulaire_pour_votes" id="formulaire_pour_ajout_vote"
                        hx-post="/systeme_de_votes/public/index.php/ajout_vote"
                        hx-target="#errors">
                        <div class="errors" id="errors"></div>
                    <span class="fermer-la-modal-vote fermer-la-modal-pour-ajouter-vote">&times;</span>
                    <li>Ajout d'un nouveau vote</li>
                    <p>Pour ajouter un nouveau vote, veuillez remplir les infos suivantes :</p>
                    <div>
                        <label for="titre">Titre</label>
                        <input type="text" name="titre_du_vote" id="titre">
                    </div>        

                    <div>
                        <label for="description">Description</label>
                        <input type="text" name="description_du_vote" id="description">
                    </div>    
                       
                    <div class="les_options_du_vote">
                        <label for="option">Options</label>
                        <input type="text" name="option_1_du_vote" id="option" placeholder="Option 1">
                        <input type="text" class="option_2" name="option_2_du_vote" id="option" placeholder="Option 2">
                    </div>
                    
                    <div id="ajouter_option_au_vote" class="ajouter_option_au_vote">Ajouter une option</div>
                    
                    <div>
                        <label for="date_et_heure_de_fin">Date de fin & heure de fin</label>
                        <input type="datetime-local" name="date_et_heure_fin_vote" id="date_et_heure_de_fin">
                    </div>

                    <button type="submit" class="btn_pour_ajouter_vote">Ajouter ce vote</button>
            </form>
           
            

            <!-- Bloc qui apparaitra pour la gestion des users  -->
             <div class="bloc_pour_gestion_des_users"></div>

            <!-- Diffrents votes qui sont dispo qu'ils soient actifs ou terminé -->
             <section id="differents_votes">
                            <li>Différents votes</li>

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
                                        
                                        <div class="actions_sur_ce_vote">
                                            <button class="modifier_vote" data-id-du-btn-de-modif-du-vote="<?= $resultat['id'] ?>"   id="bouton_de_modif_du_vote_<?= $resultat['id'] ?>" >Modifier 🖋️</button>
                                            <button class="supprimer_vote" data-id-du-btn-de-suppression-du-vote="<?= $resultat['id'] ?>"   id="bouton_de_suppression_du_vote_<?= $resultat['id'] ?>"  name="id_vote"  value="<?= $resultat['id'] ?>"
                                                            hx-post="/systeme_de_votes/public/index.php/supprimer_vote"
                                                            hx-confirm="Veux-tu supprimer  ?"
                                                            hx-target=".votes_en_cours"
                                                            hx-swap="outherHTML">Supprimer 🗑️</button>
                                            <button class="resultat_vote" data-id-du-btn-de-resultat-du-vote="<?= $resultat['id'] ?>"   id="bouton_de_resultat_vote_terminé<?= $resultat['id'] ?>" >Résultat 📈</button>
                                        </div>
                                    </div>
                                    
                                    <div class="corps_votes_en_cours">
                                        <?php
                                            $id_du_vote = $resultat['id'];
                                            $sql_pour_obtenir_nbre_participants ='SELECT * FROM votes_utilisateur WHERE vote_id=:id_du_vote';
                                            $requete_pour_obtenir_nbre_participants = $connexion->prepare($sql_pour_obtenir_nbre_participants);
                                            $requete_pour_obtenir_nbre_participants->execute(['id_du_vote' => $resultat['id']]);
                                            $resultat_de_la_requete_pour_obtenir_nbre_participants = $requete_pour_obtenir_nbre_participants->fetchAll();
                                             $total_participants = count($resultat_de_la_requete_pour_obtenir_nbre_participants);
                                        ?>
                                        <div class="participants"><img src="../img/users.svg" alt=""> <span> <?= $total_participants ?> Participants</span></div>
                                        <!-- Change image -->
                                        <div class="date_de_fin"><img src="../img/users.svg" alt=""> <span>Fin : <?= $resultat['date_fin'] ?></span></div>
                                    </div>
                                </div>

                                <!-- Div pour modifier un vote  -->
                                <?php
                                    } //fin du 'for each' pour afficher les votes 'actifs'
                                ?>
                                <!-- -------------------------- -->
                            </div>
                </section>

                <section class="bloc_sur_votes_terminés"> <!--Change les images-->
                    <div class="titre_de_bloc_sur_votes_terminés"><svg xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960" width="25px" fill="#000"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg><span>Votes terminés</span></div>
                    <div class="grille_des_differents_votes_terminés">
                        
                        <!-- Votes en terminés -->
                        <?php
                            $sql_pour_obtenir_votes_terminés ='SELECT * FROM votes WHERE statut_du_vote= :statut_du_vote ';
                            $requete_pour_obtenir_votes_terminés = $connexion->prepare($sql_pour_obtenir_votes_terminés);
                            $requete_pour_obtenir_votes_terminés->execute(['statut_du_vote' => 'Terminé']);
                            $resultat_de_la_requete_pour_obtenir_votes_terminés = $requete_pour_obtenir_votes_terminés->fetchAll();
                            foreach ($resultat_de_la_requete_pour_obtenir_votes_terminés as $resultat) {
                        ?>      
                        <div class="votes_terminés">
                            <div class="header_votes_terminés">
                                <p><span class="titre_vote_terminés"><?= $resultat['titre']?></span> <span class="description_vote_terminés"><?= $resultat['description']?></span></p>
                                
                                <div class="actions_sur_ce_vote">
                                    <button class="supprimer_vote" data-id-du-btn-de-suppression-du-vote-terminé="<?= $resultat['id'] ?>"   id="bouton_de_suppression_du_vote_terminé<?= $resultat['id'] ?>" name="id_vote"  value="<?= $resultat['id'] ?>"
                                                      hx-post="/systeme_de_votes/public/index.php/supprimer_vote"
                                                    hx-confirm="Veux-tu supprimer  ?"
                                                    hx-target=".votes_terminés" >Supprimer 🗑️</button>
                                    <button class="resultat_vote" data-id-du-btn-de-resultat-du-vote="<?= $resultat['id'] ?>"   id="bouton_de_resultat_vote_terminé<?= $resultat['id'] ?>" >Résultat 📈</button>
                                </div>
                            </div>
                            <div class="corps_votes_terminés">
                                <?php
                                            $id_du_vote = $resultat['id'];
                                            $sql_pour_obtenir_nbre_participants ='SELECT * FROM votes_utilisateur WHERE vote_id=:id_du_vote';
                                            $requete_pour_obtenir_nbre_participants = $connexion->prepare($sql_pour_obtenir_nbre_participants);
                                            $requete_pour_obtenir_nbre_participants->execute(['id_du_vote' => $resultat['id']]);
                                            $resultat_de_la_requete_pour_obtenir_nbre_participants = $requete_pour_obtenir_nbre_participants->fetchAll();
                                             $total_participants = count($resultat_de_la_requete_pour_obtenir_nbre_participants);
                                        ?>
                                <div class="participants"><img src="../img/users.svg" alt=""> <span> <?= $total_participants ?> Participants</span></div>
                                <!-- Change image -->
                                <div class="date_de_fin"><img src="../img/users.svg" alt=""> <span>Terminé : <?= $resultat['date_fin']?></span></div>
                            </div>
                            </div>
                                <?php
                            }
                                ?>
                        </div>
                        <!-- -------------------------- -->
                </section>
             </section>


              <!-- section pour voir les résultats de chaque vote -->
          <?php
                    // J'ai fait une erreur dans la syntaxe du nom des variables😅 
                    $sql_pour_obtenir_votes_terminés ='SELECT * FROM votes';
                    $requete_pour_obtenir_votes_terminés = $connexion->prepare($sql_pour_obtenir_votes_terminés);
                    $requete_pour_obtenir_votes_terminés->execute();
                    $resultat_de_la_requete_pour_obtenir_votes_terminés = $requete_pour_obtenir_votes_terminés->fetchAll();
                    foreach ($resultat_de_la_requete_pour_obtenir_votes_terminés as $resultat) {
                ?>      

                <!-- Formulaire pour modifier un vote  -->
            <form action="/systeme_de_votes/public/index.php/modif_vote" method="post" class="form_modif_votes formulaire_pour_votes" id="formulaire_pour_modifier_vote_<?= $resultat['id'] ?>"
                        hx-post="/systeme_de_votes/public/index.php/modif_vote"
                        hx-target="#errors_<?= $resultat['id'] ?>">
                        <div class="errors" id="errors_<?= $resultat['id'] ?>"></div>
                    <span class="fermer-la-modal-vote"  id="fermer-la-modal-pour-modifier-vote-<?= $resultat['id'] ?>">&times;</span>
                    <li>Modification du vote existant</li>
                    <p>Pour modifier ce vote, veuillez remplir les infos suivantes :</p>
                    <input type="hidden" name="id_du_vote" value="<?= $resultat['id'] ?>">
                    <div>
                        <label for="titre">Titre</label>
                        <input type="text" name="titre_du_vote" value="<?= $resultat['titre']?>" id="titre">
                    </div>        

                    <div>
                        <label for="description">Description</label>
                        <input type="text" name="description_du_vote" id="description" value="<?= $resultat['description']?>">
                    </div>    
                       
                    <div class="les_options_du_vote">
                        <?php
                                $sql_pour_avoir_l_id_d_options = "SELECT * FROM option_votes WHERE vote_id = :vote_id";
                                $requete_pour_avoir_l_id_d_options = $connexion->prepare($sql_pour_avoir_l_id_d_options);
                                $requete_pour_avoir_l_id_d_options->execute(['vote_id' =>  $resultat['id']]);
                                $resultat_pour_avoir_l_id_d_options = $requete_pour_avoir_l_id_d_options->fetchAll();          
                        ?>
                        <label for="option">Options</label>
                        <input type="hidden" name="id_option_1_du_vote" value="<?= $resultat_pour_avoir_l_id_d_options[0]['id'] ?>">
                        <input type="hidden" name="id_option_2_du_vote" value="<?= $resultat_pour_avoir_l_id_d_options[1]['id'] ?>">
                        
                        <input type="text" name="option_1_du_vote"  id="option" placeholder="Option 1" value="<?= $resultat_pour_avoir_l_id_d_options[0]['libelle'] ?>">
                        <input type="text" name="option_2_du_vote" id="option" placeholder="Option 2" class="option_2" value="<?= $resultat_pour_avoir_l_id_d_options[1]['libelle'] ?>">
                    </div>
                    
                    <!-- <div class="ajouter_option_au_vote"  id="ajouter_option_au_vote_<?= $resultat['id'] ?>">Ajouter une option</div> -->
                    
                    <div>
                        <label for="date_et_heure_de_fin">Date de fin & heure de fin</label>
                        <input type="datetime-local" name="date_et_heure_fin_vote" id="date_et_heure_de_fin" value="<?= $resultat['date_fin']?>">
                    </div>

                    <!-- Pour génerer le statut -->

                    <label class="bloc_de_statut">
                            <label >Statut</label>

                        <div class="grille_des_status">
                            <?php
                                $sql_pour_obtenir_statut_vote ='SELECT DISTINCT statut_du_vote FROM votes ';
                                $requete_pour_obtenir_statut_vote = $connexion->prepare($sql_pour_obtenir_statut_vote);
                                $requete_pour_obtenir_statut_vote->execute();
                                $resultat_de_la_requete_pour_obtenir_statut_vote = $requete_pour_obtenir_statut_vote->fetchAll();
                                foreach ($resultat_de_la_requete_pour_obtenir_statut_vote as $resultat_statut_vote) {

                            ?>
                                <label class="statut_de_vote">
                                    <?php $status_value = htmlspecialchars($resultat_statut_vote['statut_du_vote']); ?>
                                    <input type="radio" name="statut_du_vote" value="<?=$resultat_statut_vote['statut_du_vote'] ?>" id="statut_<?= $resultat_statut_vote['statut_du_vote'] ?>" <?= ($resultat['statut_du_vote'] === $resultat_statut_vote['statut_du_vote']) ? 'checked' : '' ?> >
                                    <label for="statut_<?=$resultat_statut_vote['statut_du_vote'] ?>"><?= $resultat_statut_vote['statut_du_vote'] ?> </label>
                                </label>
                                
                                <!-- <label class="statut_de_vote">    
                                    <input type="radio" name="Terminé" id="Terminé">
                                    <label for="Terminé">Terminé </label>
                                </label> -->
                                <?php
                                }
                                ?>
                        </div>
                    </label>

                    <button type="submit" class="btn_pour_ajouter_vote">Modification terminée</button>
            </form>

            <!-- Section pour résultats des votes -->
         <section class="bloc_vote_cliqué_pour_voir_ses_résultats" id="bloc-vote-cliqué-pour-voir-résultat-<?= $resultat['id'] ?>" data-id-bloc-voir-résultat ="<?= $resultat['id'] ?>">
            
            <button class="retour_a_accueil"  id="retour_a_accueil_du_bloc_voir_resultat" data-id-du-btn-retour-a-accueil-pour-voir-resultat="<?= $resultat['id'] ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" >
                    <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                </svg>
                <span>Retour à l'accueil</span>
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
                                // On va maintenant trouver le nombre total d'options
                                $sql_pour_avoir_le_total_d_options = "SELECT * FROM option_votes WHERE vote_id = :vote_id";
                                $requete_pour_avoir_le_total_d_options = $connexion->prepare($sql_pour_avoir_le_total_d_options);
                                $requete_pour_avoir_le_total_d_options->execute(['vote_id' =>  $resultat['id']]);
                                $resultat_pour_avoir_le_total_d_options = $requete_pour_avoir_le_total_d_options->fetchAll();
                                $total_options = count($resultat_pour_avoir_le_total_d_options);
                                
                                // On va trouver le total de votes votés pour ce vote
                                $sql_pour_avoir_le_total_de_votes = "SELECT * FROM votes_utilisateur WHERE vote_id = :vote_id";
                                $requete_pour_avoir_le_total_de_votes = $connexion->prepare($sql_pour_avoir_le_total_de_votes);
                                $requete_pour_avoir_le_total_de_votes->execute(['vote_id' => $resultat['id']]);
                                $resultat_pour_avoir_le_total_de_votes = $requete_pour_avoir_le_total_de_votes->fetchAll();
                                $total_votes = count($resultat_pour_avoir_le_total_de_votes);
                                
                                
                                // On va maintenant trouver l'option la plus votée
                                // On va d'abord trouver l'id de l'option  correcte
                                if ($total_votes>=1) {
                                $id_correct = $resultat['id'];
                                $sql = "SELECT option_vote_id, COUNT(*) AS nb_votes
                                        FROM votes_utilisateur
                                        INNER JOIN option_votes ON votes_utilisateur.option_vote_id = option_votes.id
                                        WHERE votes_utilisateur.vote_id = :id_correct
                                        GROUP BY option_vote_id
                                        ORDER BY nb_votes DESC
                                    ";
                                // Préparation de la requête manquante
                                $requete = $connexion->prepare($sql);
                                //Bind veut dire 'combiner'
                                $requete->bindParam(':id_correct', $id_correct, PDO::PARAM_INT);
                                $requete->execute();
                                $result = $requete->fetch(PDO::FETCH_ASSOC);
                                
                                if ($result) {//
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
                                    $pourcentage_option_plus_voté =number_format($pourcentage_option_plus_voté, 2);
                                }else {
                                    $libelle_option_la_plus_votee = "Aucun vote trouvée pour le moment";
                                    $nb_fois_option_plus_voté = 0;
                                    $pourcentage_option_plus_voté = 0;
                                }
                        }else {
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
                                <p><?= htmlspecialchars($nb_fois_option_plus_voté) ?> votes (<?= htmlspecialchars($pourcentage_option_plus_voté) ?> %)</p>
                            </div>
                        </div>
                        <?php
                           /* Ici on cherche à envoyer les libellés des options et le nbre de votes des option à chart JS */
                            //on récupère l'id de chaque option de ce vote
                            $sql_pour_obtenir_option_de_ce_vote="SELECT * FROM option_votes WHERE vote_id= :vote_id";
                            $requete_pour_obtenir_option_de_ce_vote=$connexion->prepare($sql_pour_obtenir_option_de_ce_vote);
                            $requete_pour_obtenir_option_de_ce_vote->execute(['vote_id' => $resultat['id']]);
                            $resultat_pour_obtenir_option_de_ce_vote= $requete_pour_obtenir_option_de_ce_vote->fetchAll();
                            
                            // Arrays qui vont servir au niveau de chart.js
                            $titre_des_options=[];
                            $nbre_vote_option=[];
                            //on récupère l'id de chaque option de ce vote
                            foreach($resultat_pour_obtenir_option_de_ce_vote as $id_de_option_de_ce_vote){
                                
                                $sql_pour_compter_nbre_votes_sur_cette_option = "SELECT id FROM votes_utilisateur WHERE option_vote_id=:option_vote_id ";
                                $requete_pour_compter_nbre_votes_sur_cette_option = $connexion->prepare($sql_pour_compter_nbre_votes_sur_cette_option);
                                $requete_pour_compter_nbre_votes_sur_cette_option->execute(['option_vote_id'=> $id_de_option_de_ce_vote['id']]);
                                $resultat_pour_compter_nbre_votes_sur_cette_option = $requete_pour_compter_nbre_votes_sur_cette_option->fetchAll();
                                
                                $total_votes_de_cette_option = count($resultat_pour_compter_nbre_votes_sur_cette_option);
                                
                                /*Explication sur ce bout de code
                                *on stocke les libellés donc les titres des options 
                                * on stocke le nbre de fois que chaque option a été voté
                                *On stocke tout cela dans des arrays pour les utiliser avec Chart.js
                                */
                                 $titre_des_options[]= $id_de_option_de_ce_vote['libelle'];
                                $nbre_vote_option[]= $total_votes_de_cette_option;
                                // $donées_envoyée_à_chart_js= json_encode(['labels'=> $titre_des_options, 'values'=> $nbre_vote_option]);

                                ?>
                                    
                                    <?php
                                    }
                                        ?>
                                 <script>
                                        if (!window.données_pour_charts) {
                                            window.données_pour_charts = {};
                                        }
                                        window.données_pour_charts[<?= json_encode($resultat['id']) ?>] = {
                                            labels: <?= json_encode($titre_des_options) ?>,
                                            values: <?= json_encode($nbre_vote_option) ?>
                                        };
                                    </script>



         <section class="graphiques_resultats">
            <canvas id="graphe-<?= $resultat['id'] ?>"  class="graphe"width="80" height="80"></canvas>
            <canvas id="schema-<?= $resultat['id'] ?>"  class="graphe"width="80" height="80"></canvas>
         </section>

         </section>
                        <?php
                    }
         ?>

             <div class="overflow"></div>
             <div class="overflow_blanc"></div>
    </main>
              
        <script src="/systeme_de_votes/public/chart_js/script_chart.js"></script>
</body>
</html>