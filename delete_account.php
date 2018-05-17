<?php

    require 'inc/bootstrap.php';
    App::getAuth()->restrict();
    $db = App::getDatabase();

    $check = $db->query("SELECT path_to_avatar, id FROM camagru.members WHERE id = ?", [$_SESSION['auth']->id])->fetch();

    if ($check->path_to_avatar != 'images/miniatures_profil/avatar_default.png')
        unlink($check->path_to_avatar);

    $files = glob('images/webcam/' . $check->id . "_*");
    foreach ($files as $file)
        unlink($file);

    $db->query('DELETE FROM camagru.comments WHERE member_id = ?', [$_SESSION['auth']->id]);
    $db->query('DELETE FROM camagru.votes WHERE user_id = ?', [$_SESSION['auth']->id]);
    $db->query('DELETE FROM camagru.photo WHERE id_member = ?', [$_SESSION['auth']->id]);

    $db->query('DELETE FROM camagru.members WHERE id = ? AND email = ?', [$_SESSION['auth']->id, $_SESSION['auth']->email]);

    App::getAuth()->logout();

    Session::getInstance()->setFlash('success', 'Votre compte a bien été supprimé');
    App::redirect("account.php");

?>