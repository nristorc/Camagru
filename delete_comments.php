<?php

    require 'inc/bootstrap.php';
    $db = App::getDatabase();

    $id = $_GET['id_comment'];
    $comments = new Comments();
    $comments->deleteCommentsWithChildren($db, $id);

    Session::getInstance()->setFlash('success', 'Le commentaire a bien été supprimé');
    App::redirect("display_comments.php?id_photo=" . $comments->comment->photo_id);

?>