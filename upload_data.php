<?php

    require "inc/bootstrap.php";

    App::getAuth()->restrict();
    if (!empty($_POST["hidden_data"]))
    {
        $auth = App::getAuth();
        $db = App::getDatabase();

        $id_member = $_SESSION['auth']->id;
        $login = $_SESSION['auth']->login;
        $img = $_POST['hidden_data'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $upload_image = "images/" . mktime() . ".png";
        $path_update = "images/webcam/" . mktime() . ".png";
        file_put_contents($path_update, $data);
        $db->query("INSERT INTO camagru.photo SET path_to_photo = ?, id_member = ?, login = ? , creation_date = NOW()",[$path_update, $id_member, $login]);
        Session::getInstance()->setFlash('success', "Votre photo a bien ete upload");
    }
?>