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

    <h1>Mot de passe oublié</h1>

    <form method="post" action="">
        <label for="">Email</label>
        <input type="email" name="email"><br/>
        <button type="submit">Se connecter</button>
    </form>

<?php require 'inc/footer.php' ?>