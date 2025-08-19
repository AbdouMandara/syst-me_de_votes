    //Variables pour affichage/disparition des formulaires de modification/suppression
    const btn_connexion = document.querySelector("#connexion")
    const bloc_de_connexion = document.querySelector(".bloc-de-connexion");
    const btn_inscription = document.querySelector("#inscription")
    const bloc_inscription = document.querySelector(".bloc_inscription");
    
    //affiche le formulaire de modification
    btn_connexion.addEventListener("click", (e)=>{
        bloc_de_connexion.classList.add("affiche_element")
        bloc_inscription.classList.remove("affiche_element")

        btn_connexion.classList.add("btn_cliquée")
        btn_inscription.classList.remove("btn_cliquée")
    })
    
    //Affiche la div de suppression du user
    btn_inscription.addEventListener("click", (e)=>{
        bloc_inscription.classList.add("affiche_element")
        bloc_de_connexion.classList.remove("affiche_element")

        btn_connexion.classList.remove("btn_cliquée")
        btn_inscription.classList.add("btn_cliquée")
    })

//Pour AJAX sur le formulaire
