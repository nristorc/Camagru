<?php

    require_once 'inc/bootstrap.php';
    App::getAuth()->restrict();

    $auth = App::getAuth();
    $db = App::getDatabase();
    $user_id = $_SESSION['auth']->id;

    if ($_POST) {
        if ($_POST['comments_mail']) {
            if (!empty($_POST['checkbox'])) {

                $_SESSION['auth']->pref_comments_email = 1;
                $db->query('UPDATE camagru.members SET pref_comments_email = 1 WHERE id = ?', [$user_id]);

            }
            elseif(empty($_POST['checkbox'])) {

                $_SESSION['auth']->pref_comments_email = -1;
                $db->query('UPDATE camagru.members SET pref_comments_email = -1 WHERE id = ?', [$user_id]);

            }
            Session::getInstance()->setFlash('success', 'Votre demande a bien été prise en compte');
            App::redirect('edit_profile.php');
        }
    }

?>