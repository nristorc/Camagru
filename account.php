<?php require 'inc/bootstrap.php';


	$secret = "6LcKs1gUAAAAAIjzpGtaDf68G3teftD7q1EIEUi6";
    $response = $_POST['g-recaptcha-response'];
	$remoteip = $_SERVER['REMOTE_ADDR'];
	$api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
        . $secret
        . "&response=" . $response
        . "&remoteip=" . $remoteip ;
	$decode = json_decode(file_get_contents($api_url), true);
	if ($decode['success'] == true) {
        if (!empty($_POST)) {
            $errors = array();

            $db = App::getDatabase();
            $validator = new Validator($_POST);

            $validator->isName('firstname', "Votre Prénom contient une erreur de frappe. Merci de mettre un espace pour les Prénoms composés");
            $validator->isName('lastname', "Votre Nom contient une erreur de frappe. Merci de mettre un espace pour les Noms composés");
            $validator->isDate('birthdate', "Votre date de naissance semble être erronée");

            $validator->isAlpha('login', "Votre login n'est pas valide (alphanumérique)");
            if ($validator->isValid()) {
                $validator->isUniq('login', $db, 'camagru.members', 'Ce login est déja pris');
            }
            $validator->isEmail('email', "Votre email n'est pas valide");
            if ($validator->isValid()) {
                $validator->isUniq('email', $db, 'camagru.members', "Cet email est déjà utilisé pour un autre compte");
            }
            $validator->isConfirmed('password', "Vous devez renseigner un mot de passe valide");

            if ($validator->isValid()) {
                App::getAuth()->register($db, $_POST['firstname'], $_POST['lastname'], $_POST['birthdate'], $_POST['login'], $_POST['password'], $_POST['email']);

                Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte');

                App::redirect('account.php');
            } else {
                $errors = $validator->getErrors();
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/style.css">
        <title> Camagru </title>
    </head>
    <?php include "inc/header.php"; ?>
    <body>

        <section id="section_account">
            <div id="sign_up">
                <h4> Sign up to see photos from your friends. </h4>
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
                    <input class="form" type="text" name="firstname" placeholder="First Name" required> <br/>
                    <input class="form" type="text" name="lastname" placeholder="Last Name" required> <br/>
                    <input class="form" type="text" name="birthdate" placeholder="Date de Naissance (JJ/MM/AAAA)" required> <br/>
                    <input class="form" type="text" name="login" placeholder="Username" required> <br/>
                    <input class="form" type="email" name="email" placeholder="Email Address" required> <br/>
                    <input class="form" type="password" name="password" placeholder="Password" required> <br/>
                    <input class="form" type="password" name="password_confirm" placeholder="Confirm Password" required> <br/>
                    <div class="g-recaptcha" data-sitekey="6LcKs1gUAAAAAFzW-Eqb1W_lryR98wVFOmM6tK7U"></div>
                    <button class="form_submit" type="submit"> Sign-up ! </button><br/>
                </form>
            </div>
            <?php
                $auth = App::getAuth();
                $db = App::getDatabase();
                $auth->connectFromCookie($db);

                if ($auth->user()){
                    App::redirect('account_logged_in.php');
                }

                if (!empty($_POST) && !empty($_POST['login']) && !empty($_POST['password'])){

                    $user = $auth->login($db, $_POST['login'], $_POST['password'], isset($_POST['remember']));

                    $session = Session::getInstance();
                    if($user){

                        $session->setFlash('success', "Vous êtes maintenant bien connecté");
                        App::redirect('account_logged_in.php');
                    }
                    else{
                        $session->setFlash('danger', "Identifiant ou mot de passe incorrect");
                    }
                }

            ?>

            <div id="description">
                <p> Camagru <br/> <br/>
                    Is a social network where you can put photo of you and your friends !
                    Like, comment and share !
                </p>
            </div>
            <div id="sign_in">
                <h4> Have an account ? Sign in ! </h4>
                <form method="POST" action="">
                    <input class="form" type="text" name="login" placeholder="E-mail / Username" required> <br/>
                    <input class="form" type="password" name="password" placeholder="Password" required> <br/>
                    <button class="form_submit" type="submit"> Sign-in ! </button><br/>
                    <input type="checkbox" name="remember" value="1" /> Remember Me <br/>
                    <a href="forgotten_password.php"> Forgot your password ? </a>
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
</html>