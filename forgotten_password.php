<?php

    require 'inc/bootstrap.php';

    if (!empty($_POST) && !empty($_POST['email'])) {

        $db = App::getDatabase();
        $auth = App::getAuth();
        $session = Session::getInstance();

        if($auth->resetPassword($db, $_POST['email'])){
            $session->setFlash('success', "Les instructions du rappel de mot de passe vous ont été envoyé par email");
            App::redirect('account.php');
        }
        else{
            $session->setFlash('danger', "Aucun compte ne correspond à cette adresse email");
        }

    }

?>

<?php require 'inc/header.php' ?>
<body>
    <h2 style="text-align: center">Mot de passe oublié</h2>

    <form style="text-align: center" method="post" action="">
        <input class="form" placeholder="Adresse Email" type="email" name="email"><br/>
        <button class="form_submit" id="button_reset" type="submit">Soumettre</button>
    </form>
</body>
    <div class="clear"></div>
<?php require 'inc/footer.php' ?>