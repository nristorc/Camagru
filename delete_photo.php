<?php

    require 'inc/bootstrap.php';
    $db = App::getDatabase();
    App::getAuth()->restrict();

    $check = $db->query('SELECT * FROM camagru.photo WHERE id_member = ?', [$_SESSION['auth']->id])->fetch();

    if ($check->id_member == $_SESSION['auth']->id){
        if (isset($_GET['photo_id'])){
            $id_photo = htmlspecialchars($_GET['photo_id']);

            $pictures = $db->query('SELECT * FROM camagru.photo WHERE id_member = ?', [$_SESSION['auth']->id])->fetch();

            $db->query('DELETE FROM camagru.photo WHERE id_photo = ?', [$_GET['photo_id']]);
            $db->query('DELETE FROM camagru.comments WHERE photo_id = ?', [$id_photo]);
            $db->query('DELETE FROM camagru.votes WHERE ref_id = ?', [$id_photo]);


            Session::getInstance()->setFlash('success', 'Votre photo a bien été supprimée');
            App::redirect("account_logged_in.php");
        }
    }
    else
        Session::getInstance()->setFlash('danger', "Vous n'êtes pas autorisé à supprimer cette photo");
        App::redirect("account_logged_in.php");

?>