<?php require 'inc/bootstrap.php';

    $captcha = new Recaptcha('6Ld4nVoUAAAAAGVRIL3UPWUqcKZEQmQMHzA6M1sa','6Ld4nVoUAAAAANPCj0KYt6A0B2tor4lPa6Ukunx3');
    if (!empty($_POST))
    {
        if ($captcha->checkCode($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']) === false){
            Session::getInstance()->setFlash('danger', 'Le captcha ne semble pas valide');
            App::redirect('account.php');
        }
        else{
            $errors = array();

            $db = App::getDatabase();
            $validator = new Validator($_POST);

            $validator->isName('firstname', "Votre Prénom contient une erreur de frappe. Merci de mettre un espace pour les Prénoms composés");
            $validator->isName('lastname', "Votre Nom contient une erreur de frappe. Merci de mettre un espace pour les Noms composés");
            $validator->isDate('birthdate', "Votre date de naissance semble être erronée");

            $validator->isAlpha('login', "Votre login n'est pas valide (alphanumérique)");
            if ($validator->isValid())
                $validator->isUniq('login', $db, 'camagru.members', 'Ce login est déja pris');
            $validator->isEmail('email', "Votre email n'est pas valide");
            if ($validator->isValid())
                $validator->isUniq('email', $db, 'camagru.members', "Cet email est déjà utilisé pour un autre compte");
            $validator->isConfirmed('password', "Vous devez renseigner un mot de passe valide");

            if ($validator->isValid())
            {
                App::getAuth()->register($db, $_POST['firstname'], $_POST['lastname'], $_POST['birthdate'], $_POST['login'], $_POST['password'], $_POST['email']);

                Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte');
                App::redirect('account.php');
            }
            else
                $errors = $validator->getErrors();
        }
    }
?>

    <?php include "inc/header.php"; ?>
    <body>

        <section id="section_account">
            <div id="sign_up">
                <h4> Inscrivez-vous pour poster vos plus beaux montages </h4>
                <?php if (!empty($errors)): ?>
                    <div style="background-color: red; color: white;">
                        <p>Vous n'avez pas rempli le formulaire correctement</p>
                        <ul>
                            <?php foreach ($errors as $elem): ?>
                                <li><?= $elem; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                <?php endif;?>
                <form method="POST" action="">
                    <input class="form" type="text" name="firstname" placeholder="Prénom" required> <br/>
                    <input class="form" type="text" name="lastname" placeholder="Nom" required> <br/>
                    <input class="form" type="text" name="birthdate" placeholder="Date de Naissance (JJ/MM/AAAA)" required> <br/>
                    <input class="form" type="text" name="login" placeholder="Login" required> <br/>
                    <input class="form" type="email" name="email" placeholder="Adresse Email" required> <br/>
                    <input class="form" type="password" name="password" placeholder="Mot de Passe" required> <br/>
                    <input class="form" type="password" name="password_confirm" placeholder="Confirmation Mot de Passe" required> <br/>

                    <?= $captcha->html(); ?>

                    <button class="form_submit" type="submit"> S'inscrire ! </button><br/>
                </form>
            </div>

            <div id="description">
                <p> Camagru <br/> <br/>
                    Un réseau social pour partager des montages inédits !
                    Likez, Commentez et Partagez !
                </p>
            </div>
            <div id="sign_in">
                <h4> Vous avez déjà un compte ? Connectez-vous ! </h4>
                <form method="POST" action="sign_in.php">
                    <input class="form" type="text" name="login" placeholder="E-mail / Login" required> <br/>
                    <input class="form" type="password" name="password" placeholder="Mot de Passe" required> <br/>
                    <button class="form_submit" type="submit"> Se connecter ! </button><br/>
                    <input type="checkbox" name="remember" value="1"> Se souvenir de moi <br/>
                    <a href="forgotten_password.php"> Mot de passe oublié ? </a>
                </form>
            </div>
        </section>

        <div class="slideshow-container">
            <div class="mySlides fade">
                <img class="slide_photo" src="ressources/slideshow/slideshow_1.jpg">
            </div>
            <div class="mySlides fade">
                <img class="slide_photo" src="ressources/slideshow/slideshow_2.jpg">
            </div>
            <div class="mySlides fade">
                <img class="slide_photo" src="ressources/slideshow/slideshow_3.jpg">
            </div>
            <div class="mySlides fade">
                <img class="slide_photo" src="ressources/slideshow/slideshow_4.jpg">
            </div>
        </div>
        <div class="slideshow_dot">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
        <br/>
        <script src="slideshow.js"></script>
    </body>
    <?php include "inc/footer.php"; ?>