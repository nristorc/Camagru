<?php

require "inc/bootstrap.php";

App::getAuth()->restrict();
$auth = App::getAuth();
$db = App::getDatabase();

$random_numb = Str::random(7);

if (isset($_POST) && isset($_POST['href'])){
    $_SESSION['photo_superp'] = $_POST['href'];
}

$id_member = $_SESSION['auth']->id;
$login = $_SESSION['auth']->login;

if (isset($_SESSION['photo_superp']) && !isset($_SESSION['fileToUpload']))
{
    unset($_SESSION['photo_superp']);
    die(json_encode($_SESSION['photo_superp']));
}

if (!isset($_SESSION['photo_superp']) && !empty($_FILES) && !isset($_POST['submit']))
{
    $_SESSION['fileToUpload'] = $_FILES['file'];
    $ext = strtolower(substr($_SESSION['fileToUpload']['name'], -3));
    $allow_ext = array('jpg', 'png', 'gif');
    if (!(in_array($ext, $allow_ext)))
        Session::getInstance()->setFlash('danger', "Votre fichier n'a pas un format d'image autorisé");
    else {
        $upload_image = "images/tmp/" . $_SESSION['fileToUpload']['name'];

        move_uploaded_file($_SESSION['fileToUpload']['tmp_name'], "images/tmp/" . $_SESSION['fileToUpload']['name']);

        Img::creerMin($upload_image, $random_numb,"images/tmp", $_SESSION['fileToUpload']['name'],
            500, 375);

        unlink($upload_image);

        $_SESSION['fileToUpload']['name_tempo'] = $id_member . "_" . $random_numb .$_SESSION['fileToUpload']['name'];
        $_SESSION['fileToUpload']['path_tmp'] = "images/tmp/" . $_SESSION['fileToUpload']['name_tempo'];

        //Session::getInstance()->setFlash('success', "Pas de session photo");

        //print_r($random_numb);
        App::redirect("webcam.php");
    }
}

if (isset($_SESSION['photo_superp']) && isset($_SESSION['fileToUpload']) && !isset($_POST['submit'])) {

    echo 'FILE + SUPERPOSABle => pas de validation';
    print_r($_SESSION['photo_superp']);
    die();
}

if (!isset($_SESSION['photo_superp']) && (isset($_SESSION['fileToUpload'])) && isset($_POST['submit'])) {

    Session::getInstance()->setFlash('danger', "Merci de sélectionner une image superposable avant de valider");
    App::redirect("webcam.php");
}

if (isset($_SESSION['photo_superp']) && isset($_SESSION['fileToUpload']) && isset($_POST['submit'])) {

    $img_superp = substr($_SESSION['photo_superp'], 27, -3);

    $upload_image = $_SESSION['fileToUpload']['path_tmp'];
    Img::superPhoto($upload_image, "images/webcam", $_SESSION['fileToUpload']['name_tempo'], $_SESSION['photo_superp']);

    unset($_SESSION['photo_superp']);

    unlink($upload_image);

    $path_update = "images/webcam/" . substr($_SESSION['fileToUpload']['name_tempo'], 0, -4) .
        "_" . $img_superp . substr($_SESSION['fileToUpload']['name'], -3);

    $db->query("INSERT INTO camagru.photo SET path_to_photo = ?, id_member = ?, login = ?, creation_date = NOW()",[$path_update, $id_member, $login]);

    unset($_SESSION['fileToUpload']);

    // Comment mettre le message flash alors que la page n'est pas reload...
    Session::getInstance()->setFlash('success', "Votre photo a bien ete upload");
    App::redirect("webcam.php");
}

?>