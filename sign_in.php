<?php

    require 'inc/bootstrap.php';

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
            App::redirect('account.php');
        }
    }

?>