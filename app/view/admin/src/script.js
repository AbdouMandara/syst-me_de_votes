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

//Pour augmenter la hauteur du overflow pour qu'elle soit proportionnelle à celle de l'écran
function hauteur_overflow(params) {
    setTimeout(() => {
        let hauteur_du_body = body.clientHeight;
        hauteur_du_body = parseFloat(hauteur_du_body);
        params.style.height = hauteur_du_body + "px";
    }, 0);
}

// Pour augmenter nbre d'options
let nbre_option = 3;
let id_option_3 =window.id_option_3;
let libelle_option_3 =window.libelle_option_3;
function afficher_option(les_options_du_vote, ajouter_option_au_vote) {
 hauteur_overflow(overflow)
    les_options_du_vote.insertAdjacentHTML("beforeend",`<input type="text" class="option_${nbre_option}" name="option_${nbre_option}_du_vote" id="${id_option_3}" placeholder="Option ${nbre_option}" value="${libelle_option_3}">`)
    // les_options_du_vote.insertAdjacentHTML("beforeend",`<input type="hidden" name="id_option_${nbre_option}_du_vote" value="<?= $resultat_pour_avoir_l_id_d_options[2]['id'] ?>">` )
    nbre_option++
    if (nbre_option > 3) {
        ajouter_option_au_vote.disabled=true;
        ajouter_option_au_vote.removeEventListener("click", afficher_option())
        ajouter_option_au_vote.style.cursor="no-drop"
    }
}

/*____________________________________________________________________________ */
/* Variable utiliser pour affichage/cachage du formulaire pour ajouter un vote */
const bouton_pour_ajouter_un_vote = document.querySelector("#ajout_du_vote")
const formulaire_pour_ajout_vote = document.querySelector("#formulaire_pour_ajout_vote") //Formulaire à remplir pour ajouter un vote
const overflow = document.querySelector(".overflow")
const fermer_la_modal_pour_ajouter_vote = document.querySelector(".fermer-la-modal-pour-ajouter-vote")
const body = document.querySelector("body")

/* Gestion de l'affichage du formulaire pour ajouter un vote */
bouton_pour_ajouter_un_vote.addEventListener("click", ()=>{
    afficherElement(overflow)
    afficherElement(formulaire_pour_ajout_vote)
     let hauteur_du_body = getComputedStyle(body).height;
    hauteur_du_body = parseFloat(hauteur_du_body);
    overflow.style.height = hauteur_du_body + "px";
})

/* Gestion du cachage du formulaire pour ajouter un vote */
fermer_la_modal_pour_ajouter_vote.addEventListener("click", ()=>{
    cacherElement(overflow)
    cacherElement(formulaire_pour_ajout_vote)
})
overflow.addEventListener("click", ()=>{
    cacherElement(overflow)
    cacherElement(formulaire_pour_ajout_vote)
})


const overflow_blanc = document.querySelector(".overflow_blanc")
const main = document.querySelector("main")

// _____Pour l'affichage du bloc des résultats
document.querySelectorAll(".resultat_vote").forEach(bouton =>{
    bouton.addEventListener("click", () => {
            setTimeout(() => {
                let hauteur_du_body = body.clientHeight;
                hauteur_du_body = parseFloat(hauteur_du_body);
                overflow_blanc.style.height = hauteur_du_body + "px";
            }, 0);
            const id_bouton_cliqué = bouton.getAttribute("data-id-du-btn-de-resultat-du-vote")
            const bloc_pour_voir_résultat = document.getElementById(`bloc-vote-cliqué-pour-voir-résultat-${id_bouton_cliqué}`)

            afficherElement(bloc_pour_voir_résultat);
            afficherElement(overflow_blanc);

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

//Pour les boutons de 'retour à accueil' dans les blocs où on veut voter
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

// gestion de l'affichage des formulaires de modifs des votes
document.querySelectorAll(".modifier_vote").forEach(btn_modifier_vote =>{
    btn_modifier_vote.addEventListener("click", ()=>{
        const id_btn_modifier_vote= btn_modifier_vote.getAttribute("data-id-du-btn-de-modif-du-vote")
        const formulaire_pour_modifier_vote = document.getElementById(`formulaire_pour_modifier_vote_${id_btn_modifier_vote}`)
        const  fermer_la_modal_pour_modifier_vote= document.getElementById(`fermer-la-modal-pour-modifier-vote-${id_btn_modifier_vote}`)
        
        afficherElement(overflow)
        afficherElement(formulaire_pour_modifier_vote)
        hauteur_overflow(overflow)
        /* Gestion du cachage du formulaire pour ajouter un vote */
        fermer_la_modal_pour_modifier_vote.addEventListener("click", ()=>{
            cacherElement(overflow)
            cacherElement(formulaire_pour_modifier_vote)
        })
        overflow.addEventListener("click", ()=>{
            cacherElement(overflow)
            cacherElement(formulaire_pour_modifier_vote)
        })
        
        
    // Pour augmenter le nbre d'options 
//  document.querySelectorAll(".form_modif_votes > .ajouter_option_au_vote").forEach(btn_ajout_option =>{
//         btn_ajout_option.addEventListener("click", () => {
//             const les_options_du_vote = document.querySelector(`#formulaire_pour_modifier_vote_${id_btn_modifier_vote} > .les_options_du_vote`)
//             console.log(les_options_du_vote);
            
//             afficher_option(les_options_du_vote, btn_ajout_option)
//         })
//     })
 })
})