// Fonction pour afficher un élement
function afficherElement(element) {
    element.classList.add("afficher_element");
}

// Fonction pour cacher un élement
function cacherElement(element) {
    element.classList.remove("afficher_element");
}
function cachage_niveau2(element) {
    element.style.display="none"
}
const overflow_blanc = document.querySelector(".overflow_blanc")
const main = document.querySelector("main")
document.querySelectorAll(".bouton_de_vote").forEach(bouton =>{
    bouton.addEventListener("click", () => {

            const id_bouton_cliqué = bouton.getAttribute("data-id-du-btn-vote")
            const bloc_vote_en_cours_cliqué = document.getElementById(`bloc-vote-cliqué-qui-est-en-cours-${id_bouton_cliqué}`)

            afficherElement(bloc_vote_en_cours_cliqué);
            afficherElement(overflow_blanc);

            // Pour faire l'effet de scroll vers le haut lorsqu'on clique sur 'voter maintenant' meme si on a déjà scrollé sur la page principale
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

            // Ajuste la hauteur de la section principale pour empêcher le défilement
            if (bloc_vote_en_cours_cliqué) {
                let hauteur_de_la_div = getComputedStyle(bloc_vote_en_cours_cliqué).height;
                hauteur_de_la_div = parseFloat(hauteur_de_la_div);

                // Ajoute 40px pour éviter que le bas de la div ne soit coupé
                main.style.height = hauteur_de_la_div + 40 + "px";
                main.style.overflow = "hidden";
            } else {
                console.warn("bloc_vote_en_cours_cliqué est null ou undefined");
            }
    })
})
//Pour les btn de 'retour à accueil' dans les blocs où on veut voter
document.querySelectorAll(" .bloc_vote_cliqué_qui_est_en_cours > .retour_a_accueil").forEach(retour => {
    retour.addEventListener("click", () => {

        // Récupère l'ID du bouton de retour cliqué
        const id_btn_retour_cliqué = retour.getAttribute("data-id-du-btn-retour-a-accueil")
        // Récupère le bloc de vote en cours cliqué en utilisant l'ID
        const bloc_vote_en_cours_cliqué = document.getElementById(`bloc-vote-cliqué-qui-est-en-cours-${id_btn_retour_cliqué}`)
        cacherElement(bloc_vote_en_cours_cliqué)
        cacherElement(overflow_blanc)
        cachage_niveau2(bloc_vote_en_cours_cliqué)
        cachage_niveau2(overflow_blanc)
        main.style.height="max-content"
    })
})
/*--------------------------------------------------------------- */

// NOUVELLE LOGIQUE DE SOUMISSION DE VOTE
let currentVoteId = null //l' ID du vote sur lequel on travaille actuellement  
let id_est_ce_que_vote_voté = null //ID qu'on va stocker dans le localStorage
// 1. On ajoute un écouteur de clic pour capturer l'ID du vote avant l'envoi de la requête HTMX.
document.querySelectorAll(".confirme_vote").forEach(btn_confirme_vote => {
    btn_confirme_vote.addEventListener("click", () => {
        currentVoteId = btn_confirme_vote.getAttribute("data-bouton-soumission-du-vote-en-cours");
    });
});


// 2. On écoute l'événement htmx:afterSwap pour détecter quand le message de succès est chargé.
document.body.addEventListener('htmx:afterSwap', (event) => {

    // On vérifie si le conteneur de succès a été la cible du swap et si nous avons un ID de vote en attente.
    if (event.detail.target.id === 'success-container' && currentVoteId) {
        const success_container = event.detail.target;
        // On trouve tous les éléments liés au vote en utilisant l'ID que nous avons sauvegardé.
        const bloc_vote_cliqué_qui_est_en_cours = document.getElementById(`bloc-vote-cliqué-qui-est-en-cours-${currentVoteId}`);
        // Mettre à jour immédiatement la liste côté client (variable exposée par PHP)
        try {
            // Si la variable session n'est pas un tableau alors on le rend en tableau
            if (!Array.isArray(window.SESSION_VOTES)) {
                window.SESSION_VOTES = [];
            }
            // On conserve l'id actuel dans une variable après avoir su s'il est un nbre ou pas 
            const vid = isNaN(Number(currentVoteId)) ? currentVoteId : Number(currentVoteId);
            // On vérifie si l'ID du vote n'est pas déjà dans la liste des votes en session
            if (!window.SESSION_VOTES.includes(vid)) {
                // S'il n'est pas on l'insère à la fin
                window.SESSION_VOTES.push(vid);
            }

            // Mettre à jour l'interface : afficher 'Déjà voté' et supprimer le bouton 'Voter maintenant'
            const bouton_déjà_voté_cliqué = document.getElementById(`bouton_déjà_voté_${currentVoteId}`);
            const bouton_vote_cliqué = document.getElementById(`bouton_de_vote_${currentVoteId}`);

            if (bouton_déjà_voté_cliqué) {
                afficherElement(bouton_déjà_voté_cliqué);
            } else if (bouton_vote_cliqué) {
                // Remplacer le bouton de vote par un bouton déjà voté si nécessaire
                // * c'est meme pas important tout ce code car je connais déjà le bouton 'déjà voté qui apparaitra [T'as mal copilot]
                const btn = document.createElement('button');
                btn.className = 'bouton_déjà_voté';
                btn.id = `bouton_déjà_voté_${currentVoteId}`;
                btn.disabled = true;
                btn.textContent = 'Dejà voté ✅';
                bouton_vote_cliqué.replaceWith(btn);
            }
            if (bouton_vote_cliqué) {
                bouton_vote_cliqué.remove();
            }
        } catch (e) {
            console.warn('Erreur lors de la mise à jour client des votes en session :', e);
        }


        // On utilise un timeout pour laisser à l'utilisateur le temps de voir le message de succès.
        setTimeout(() => {
            // On supprime le message de succès.
            if (success_container.firstElementChild) {
                success_container.removeChild(success_container.firstElementChild);
            }
            // On cache la fenêtre de vote.
            if (bloc_vote_cliqué_qui_est_en_cours) {
                cacherElement(bloc_vote_cliqué_qui_est_en_cours)
                cachage_niveau2(bloc_vote_cliqué_qui_est_en_cours);
            }
            
            // On cache le calque blanc.
            cachage_niveau2(overflow_blanc);
            cacherElement(overflow_blanc)
            cacherElement(bloc_vote_cliqué_qui_est_en_cours)
            
            // On restaure le scroll.
            main.style.height = "max-content";

            // On réinitialise l'ID du vote.
            currentVoteId = null;

        }, 1500);
    }
});

// Au chargement de la page, on vérifie les votes déjà effectués (données exposées par PHP)
document.addEventListener("DOMContentLoaded", () => {
    // Utilise la variable exposée par PHP : window.SESSION_VOTES
    let votes_déjà_votés = Array.isArray(window.SESSION_VOTES) ? window.SESSION_VOTES : [];

    votes_déjà_votés.forEach(id_vote_déjà_voté => {
        const bouton_déjà_voté_cliqué = document.getElementById(`bouton_déjà_voté_${id_vote_déjà_voté}`);
        const bouton_vote_cliqué = document.getElementById(`bouton_de_vote_${id_vote_déjà_voté}`);

        // On met à jour l'interface : on affiche 'Déjà voté' et on supprime 'Voter maintenant'.
        if (bouton_déjà_voté_cliqué) {
            afficherElement(bouton_déjà_voté_cliqué);
        }
        if (bouton_vote_cliqué) {
            bouton_vote_cliqué.remove();
        }
    });
});

// _____Pour l'affichage du bloc des résultats
document.querySelectorAll(".bouton_pour_voir_resultat").forEach(bouton =>{
    bouton.addEventListener("click", () => {

            const id_bouton_cliqué = bouton.getAttribute("data-id-du-btn-vote-terminé")
            const bloc_pour_voir_résultat = document.getElementById(`bloc-vote-cliqué-pour-voir-résultat-${id_bouton_cliqué}`)

            afficherElement(bloc_pour_voir_résultat);
            afficherElement(overflow_blanc);

            // Pour faire l'effet de scroll vers le haut lorsqu'on clique sur 'voter maintenant' meme si on a déjà scrollé sur la page principale
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

            // Ajuste la hauteur de la section principale pour empêcher le défilement
            if (bloc_pour_voir_résultat) {
                let hauteur_de_la_div = getComputedStyle(bloc_pour_voir_résultat).height;
                hauteur_de_la_div = parseFloat(hauteur_de_la_div);

                // Ajoute 40px pour éviter que le bas de la div ne soit coupé
                main.style.height = hauteur_de_la_div + 40 + "px";
                main.style.overflow = "hidden";
            } else {
                console.warn("bloc_pour_voir_résultat est null ou undefined");
            }
    })
})
//Pour les btn de 'retour à accueil' dans les blocs où on veut voter
document.querySelectorAll(" .bloc_vote_cliqué_pour_voir_ses_résultats > .retour_a_accueil").forEach(retour => {
    retour.addEventListener("click", () => {

        // Récupère l'ID du bouton de retour cliqué
        const id_btn_retour_cliqué = retour.getAttribute("data-id-du-btn-retour-a-accueil-pour-voir-resultat")
        // Récupère le bloc de vote en cours cliqué en utilisant l'ID
        const bloc_vote_resultat = document.getElementById(`bloc-vote-cliqué-pour-voir-résultat-${id_btn_retour_cliqué}`)
        cacherElement(bloc_vote_resultat)
        cacherElement(overflow_blanc)
        cachage_niveau2(bloc_vote_resultat)
        cachage_niveau2(overflow_blanc)
        main.style.height="max-content"
    })
})
/*--------------------------------------------------------------- */
