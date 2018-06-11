<?php

    require 'inc/bootstrap.php';
    App::getAuth()->restrict();
    $db = App::getDatabase();

    $id_comment = $_GET['id_comment'];

    $check = $db->query("SELECT * FROM camagru.comments WHERE member_id = ?", [$_SESSION['auth']->id])->fetch();

    if ($_SESSION['auth']->id != $check->member_id){
        Session::getInstance()->setFlash('danger', "Vous n'êtes pas autorisé à supprimer un commentaire que vous n'avez pas écrit");
        App::redirect('index.php');
    }
    else{
        $comments = new Comments();
        $comments->deleteCommentsWithChildren($db, $id_comment);

        Session::getInstance()->setFlash('success', 'Le commentaire a bien été supprimé');
        App::redirect("comments_likes.php?id_photo=" . $comments->comment->photo_id);
    }
?>