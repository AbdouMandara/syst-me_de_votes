    <section>
        <div id="errors"></div>
        <h1>Système de votes</h1>
        <p class="sous-titre_2">Connectez-vous ou créer un compte pour participer aux votes  .</p>

        <div class="boutons">
            <button id="connexion" class="btn_cliquée">Connexion</button>
            <button id="inscription">Inscription</button>
        </div>

        <!-- Bloc connexion -->
         <form class="bloc-de-connexion bloc affiche_element" method="POST" action="/systeme_de_votes/public/index.php/login/submit"
                    hx-post="/systeme_de_votes/public/index.php/login_submit"
                    hx-target="#errors"
                    hx-swap="innerHTML">
            
            <div class="champ">
                <label for="email">Email</label>
                <input type="email" name="email_user_connexion" id="email" placeholder="votreadresse@gmail.com"
                hx-post="/systeme_de_votes/public/index.php/login_validation"         
                hx-trigger="keyup changed delay:100ms"
                hx-target="#errors">
            </div>
            <div class="champ">
                <label for="password">Mot de passe</label>
                <input type="password"   name="password_user_connexion" id="password" placeholder="••••••••"
                hx-post="/systeme_de_votes/public/index.php/login_validation"                               
                hx-trigger="keyup changed delay:100ms"
                hx-target="#errors">
            </div>

            <input type="submit" name="connecter" aria-label="Veuillez vous connectez à votre compte" value="Se connecter">
        </form>

         <!-- ---------------------------- -->

         <!-- Bloc d'inscription -->
          <form class="bloc_inscription bloc" method="post" action="/systeme_de_votes/public/index.php/inscription_submit"
                    hx-post="/systeme_de_votes/public/index.php/inscription_submit"
                    hx-target="#errors"
                    hx-swap="innerHTML">
            <div class="champ">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" placeholder="Votre nom"
                hx-trigger="keyup changed delay:100ms"
                hx-target="#errors">
            </div>

            <div class="champ">
                <label for="new_email">Email </label>
                <input type="email" name="email" id="new_email" placeholder="votreadresse@gmail.com"
                hx-post="/systeme_de_votes/public/index.php/inscription_validation"         
                hx-trigger="keyup changed delay:100ms"
                hx-target="#errors">
            </div>

             <div class="champ">
                <label for="new_password">Mot de passe </label>
                <input type="password"  name="password1" id="new_password" placeholder="••••••••"
                hx-post="/systeme_de_votes/public/index.php/inscription_validation"         
                hx-trigger="keyup changed delay:100ms"
                hx-target="#errors">
            </div>

           <input type="submit" name="inscrire" aria-label="Veuillez créer votre compte" value="Créer un compte">
        </form>
        <!-- --------------------------------- -->
    </section>