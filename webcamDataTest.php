<?php

require "inc/bootstrap.php";

App::getAuth()->restrict();
$auth = App::getAuth();
$db = App::getDatabase();

$random_numb = Str::random(7);

if (isset($_POST) && (isset($_POST['href']) || isset($_POST['hidden_data']))){
    if (isset($_POST['href']))
        $_SESSION['photo_superp'] = $_POST['href'];
    if (isset($_POST['hidden_data']))
        $_SESSION['webcam'] = $_POST['hidden_data'];
}

$id_member = $_SESSION['auth']->id;
$login = $_SESSION['auth']->login;

if (isset($_SESSION['photo_superp']) && !isset($_SESSION['fileToUpload']) && !isset($_SESSION['webcam_path_tmp']))
{
    echo 'juste superposable';
    print_r($_SESSION['photo_superp']);
    unset($_SESSION['photo_superp']);
    //die(json_encode($_SESSION['photo_superp']));
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
        App::redirect("webcamTest.php");
    }
}

if (!isset($_SESSION['photo_superp']) && isset($_SESSION['webcam']) && !isset($_SESSION['fileToUpload']) && !isset($_POST['submit'])){

    $img = $_SESSION['webcam'];

    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $tempo_name = $_SESSION['auth']->id . "_" .mktime().".png";
    //$upload_image = "images/" . $tempo_name;
    $path_update = "images/tmp/" . $tempo_name;


    file_put_contents($path_update, $data);


    $_SESSION['webcam_tempo_name'] = $tempo_name;
    $_SESSION['webcam_path_tmp'] = $path_update;

    unset($_SESSION['webcam']);
    //App::redirect("webcamTest.php");

}

if (isset($_SESSION['photo_superp']) && (isset($_SESSION['fileToUpload']) || (isset($_SESSION['webcam_path_tmp']))) && !isset($_POST['submit'])) {

    echo 'FILE + SUPERPOSABle => pas de validation';
    print_r($_SESSION['photo_superp']);
    die();
}

if (!isset($_SESSION['photo_superp']) && (isset($_SESSION['fileToUpload']) || $_SESSION['webcam_path_tmp']) && isset($_POST['submit'])) {

    Session::getInstance()->setFlash('danger', "Merci de sélectionner une image superposable avant de valider");
    App::redirect("webcamTest.php");
}

if (isset($_SESSION['photo_superp']) && isset($_SESSION['fileToUpload']) && !isset($_SESSION['webcam'])&& isset($_POST['submit'])) {

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
    App::redirect("webcamTest.php");
}

if (isset($_SESSION['photo_superp']) && !isset($_SESSION['fileToUpload']) && isset($_SESSION['webcam'])&& isset($_POST['submit'])) {

    $img_superp_name = substr($_SESSION['photo_superp'], 27, -3);

    $upload_image = $_SESSION['webcam_path_tmp'];
    Img::superPhoto($upload_image, "images/webcam", $_SESSION['webcam_tempo_name'], $_SESSION['photo_superp']);

    unset($_SESSION['photo_superp']);

    unlink($upload_image);

    print_r($_SESSION);
    die();

    $path_update = "images/webcam/" . substr($_SESSION['fileToUpload']['name_tempo'], 0, -4) .
        "_" . $img_superp . substr($_SESSION['fileToUpload']['name'], -3);

    $db->query("INSERT INTO camagru.photo SET path_to_photo = ?, id_member = ?, login = ?, creation_date = NOW()",[$path_update, $id_member, $login]);

    unset($_SESSION['fileToUpload']);

    // Comment mettre le message flash alors que la page n'est pas reload...
    Session::getInstance()->setFlash('success', "Votre photo a bien ete upload");
    App::redirect("webcamTest.php");
}

?>