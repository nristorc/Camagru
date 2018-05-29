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
    <h1>Réinitialiser mon mot de passe</h1>

    <form method="post" action="">
        <label for="">Nouveau Mot de passe</label>
        <input type="password" name="password"><br/>
        <label for="">Confirmation du Mot de passe</label>
        <input type="password" name="password_confirm"><br/>
        <button type="submit">Réinitialiser mon mot de passe</button>
    </form>
</body>
    <div class="clear"></div>
<?php require 'inc/footer.php' ?>