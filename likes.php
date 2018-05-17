<?php

    require 'inc/bootstrap.php';
    $db = App::getDatabase();
    App::getAuth()->restrict();

    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        http_response_code(403);
        die();
    }

    $accepted_refs = ['camagru.photo'];
    if (!in_array($_POST['ref_photo'], $accepted_refs)){
        http_response_code(403);
        die();
    }

    $vote = new Vote();
    if ($_POST['vote'] == 1){
        $success = $vote->like($db, $_POST['ref_photo'], $_POST['ref_id'], $_SESSION['auth']->id);
    }
    else{
        $success = $vote->dislike($db, $_POST['ref_photo'], $_POST['ref_id'], $_SESSION['auth']->id);
    }

    $req = $db->query("SELECT like_count, dislike_count FROM {$_POST['ref_photo']} WHERE id_photo = ?", [$_POST['ref_id']]);
    header('Content-type: application/json');

    $record = $req->fetch(PDO::FETCH_ASSOC);
    $record['success'] = $success;
    die(json_encode($record));
?>