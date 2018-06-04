<?php

    require 'inc/bootstrap.php';

    if (isset($_GET['id']) && isset($_GET['token'])){

        $auth = App::getAuth();
        $db = App::getDatabase();
        $user = $auth->getUserFromResetToken($db, $_GET['id'], $_GET['token']);

        if ($user){
            if (!empty($_POST)){

                $validator = new Validator($_POST);
                $validator->isConfirmed('password');

                if ($validator->isValid()){

                    $password = $auth->hashPassword($_POST['password']);
                    $db->query('UPDATE camagru.members SET password = ?, reset_at = NULL, reset_token = NULL WHERE id = ?', [$password, $_GET['id']]);
                    $auth->connect($user);
                    Session::getInstance()->setFlash('success', "Votre mot de passe a bien été réinitialisé");
                    App::redirect('account_logged_in.php');
                }
            }
        }
        else{
            Session::getInstance()->setFlash('danger', "Ce token n'est pas valide");
            App::redirect('account.php');
        }
    }
    else{
        App::redirect('account.php');
    }

?>

<?php require 'inc/header.php' ?>
<body>
<h2 style="text-align: center">Réinitialiser mon mot de passe</h2>

    <form style="text-align: center" method="post" action="">
        <input class="form" type="password" name="password" placeholder="Nouveau Mot de Passe"><br/>
        <input class="form" type="password" name="password_confirm" placeholder="Confirmation du Mot de Passe"><br/>
        <button id="button_reset_2" type="submit">Réinitialiser mon mot de passe</button>
    </form>
</body>
    <div class="clear"></div>
<?php require 'inc/footer.php' ?>