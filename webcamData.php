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

    if (isset($_POST['submit']) && !isset($_SESSION['photo_superp']) && !isset($_SESSION['webcam_path_tmp']) && !isset($_SESSION['fileToUpload'])){
        Session::getInstance()->setFlash('danger', "Merci de sélectionner une photo ou en prendre une via la webcam avant de valider");
        App::redirect("webcam.php");
        die();
    }

    if (isset($_SESSION['photo_superp']) && !isset($_SESSION['fileToUpload']) && !isset($_SESSION['webcam_path_tmp']))
    {
        echo 'juste superposable';
        print_r($_SESSION['photo_superp']);
        unset($_SESSION['photo_superp']);
        die();
    }

    if (!empty($_FILES) && !isset($_POST['submit']))
    {
        if (!isset($_SESSION['photo_superp'])) {
            $_SESSION['fileToUpload'] = $_FILES['file'];
            $ext = strtolower(substr($_SESSION['fileToUpload']['name'], -3));
            $allow_ext = array('jpg', 'png', 'gif');
            if (!(in_array($ext, $allow_ext))){
                Session::getInstance()->setFlash('danger', "Votre fichier n'a pas un format d'image autorisé");
                App::redirect('webcam.php');
            }
            else {
                $upload_image = "images/tmp/" . $_SESSION['fileToUpload']['name'];

                move_uploaded_file($_SESSION['fileToUpload']['tmp_name'], "images/tmp/" . $_SESSION['fileToUpload']['name']);

                if (mime_content_type($upload_image) == 'image/jpeg' || mime_content_type($upload_image) == 'image/png'){
                    Img::creerMin($upload_image, $random_numb, "images/tmp", $_SESSION['fileToUpload']['name'],
                        500, 375);

                    unlink($upload_image);

                    $_SESSION['fileToUpload']['name_tempo'] = $id_member . "_" . $random_numb . $_SESSION['fileToUpload']['name'];
                    $_SESSION['fileToUpload']['path_tmp'] = "images/tmp/" . $_SESSION['fileToUpload']['name_tempo'];

                    App::redirect("webcam.php");
                }
                else{
                    unlink($upload_image);
                    unset($_SESSION['fileToUpload']);
                    Session::getInstance()->setFlash('danger', "Votre fichier n'a pas un format d'image autorisé");
                    App::redirect('webcam.php');
                }
            }
        }
        if (isset($_SESSION['photo_superp'])) {
            unset($_SESSION['photo_superp']);

            $_SESSION['fileToUpload'] = $_FILES['file'];
            $ext = strtolower(substr($_SESSION['fileToUpload']['name'], -3));
            $allow_ext = array('jpg', 'png', 'gif');
            if (!(in_array($ext, $allow_ext))){
                Session::getInstance()->setFlash('danger', "Votre fichier n'a pas un format d'image autorisé");
                App::redirect('webcam.php');
            }
            else {
                $upload_image = "images/tmp/" . $_SESSION['fileToUpload']['name'];

                move_uploaded_file($_SESSION['fileToUpload']['tmp_name'], "images/tmp/" . $_SESSION['fileToUpload']['name']);

                if (mime_content_type($upload_image) == 'image/jpeg' || mime_content_type($upload_image) == 'image/png'){
                    Img::creerMin($upload_image, $random_numb, "images/tmp", $_SESSION['fileToUpload']['name'],
                        500, 375);

                    unlink($upload_image);

                    $_SESSION['fileToUpload']['name_tempo'] = $id_member . "_" . $random_numb . $_SESSION['fileToUpload']['name'];
                    $_SESSION['fileToUpload']['path_tmp'] = "images/tmp/" . $_SESSION['fileToUpload']['name_tempo'];

                    App::redirect("webcam.php");
                }
                else{
                    unlink($upload_image);
                    unset($_SESSION['fileToUpload']);
                    Session::getInstance()->setFlash('danger', "Votre fichier n'a pas un format d'image autorisé");
                    App::redirect('webcam.php');
                }

            }
        }
    }

    if (isset($_SESSION['webcam']) && !isset($_SESSION['fileToUpload']) && !isset($_POST['submit'])){

        if (!isset($_SESSION['photo_superp'])){
            $img = $_SESSION['webcam'];

            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $tempo_name = $_SESSION['auth']->id . "_" .mktime().".png";
            $path_update = "images/tmp/" . $tempo_name;

            file_put_contents($path_update, $data);

            $_SESSION['webcam_tempo_name'] = $tempo_name;
            $_SESSION['webcam_path_tmp'] = $path_update;

            unset($_SESSION['webcam']);
        }

        if (isset($_SESSION['photo_superp'])){
            unset($_SESSION['photo_superp']);

            $img = $_SESSION['webcam'];

            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $tempo_name = $_SESSION['auth']->id . "_" .mktime().".png";

            $path_update = "images/tmp/" . $tempo_name;


            file_put_contents($path_update, $data);


            $_SESSION['webcam_tempo_name'] = $tempo_name;
            $_SESSION['webcam_path_tmp'] = $path_update;

            unset($_SESSION['webcam']);
        }
    }

    if (isset($_SESSION['photo_superp']) && (isset($_SESSION['fileToUpload']) || (isset($_SESSION['webcam_path_tmp']))) && !isset($_POST['submit'])) {

        echo 'FILE + SUPERPOSABle => pas de validation';
        print_r($_SESSION['photo_superp']);
        die();
    }

    if (!isset($_SESSION['photo_superp']) && (isset($_SESSION['fileToUpload']) || $_SESSION['webcam_path_tmp']) && isset($_POST['submit'])) {

        if (isset($_SESSION['webcam_path_tmp'])){
            unlink($_SESSION['webcam_path_tmp']);
            unset($_SESSION['webcam_path_tmp']);
            unset($_SESSION['webcam_tempo_name']);
        }
        Session::getInstance()->setFlash('danger', "Merci de sélectionner une image superposable avant de valider");
        App::redirect("webcam.php");
    }

    if (isset($_SESSION['photo_superp']) && isset($_SESSION['fileToUpload']) && !isset($_SESSION['webcam_path_tmp'])&& isset($_POST['submit'])) {

        $img_superp = substr($_SESSION['photo_superp'], 27, -3);

        $upload_image = $_SESSION['fileToUpload']['path_tmp'];
        Img::superPhoto($upload_image, "images/webcam", $_SESSION['fileToUpload']['name_tempo'], $_SESSION['photo_superp']);

        unset($_SESSION['photo_superp']);

        unlink($upload_image);

        $path_update = "images/webcam/" . substr($_SESSION['fileToUpload']['name_tempo'], 0, -4) .
            "_" . $img_superp . substr($_SESSION['fileToUpload']['name'], -3);

        $db->query("INSERT INTO camagru.photo SET path_to_photo = ?, id_member = ?, login = ?, creation_date = NOW()",[$path_update, $id_member, $login]);

        unset($_SESSION['fileToUpload']);

        Session::getInstance()->setFlash('success', "Votre photo a bien ete upload");
        App::redirect("webcam.php");
    }

    if (isset($_SESSION['photo_superp']) && !isset($_SESSION['fileToUpload']) && isset($_SESSION['webcam_path_tmp'])&& isset($_POST['submit'])) {

        $img_superp_name = substr($_SESSION['photo_superp'], 27, -3);

        $upload_image = $_SESSION['webcam_path_tmp'];
        Img::superPhoto($upload_image, "images/webcam", $_SESSION['webcam_tempo_name'], $_SESSION['photo_superp']);

        unset($_SESSION['photo_superp']);

        unlink($upload_image);

        $path_update = "images/webcam/" . substr($_SESSION['webcam_tempo_name'], 0, -4) .
            "_" . $img_superp_name . substr($_SESSION['webcam_tempo_name'], -3);

        $db->query("INSERT INTO camagru.photo SET path_to_photo = ?, id_member = ?, login = ?, creation_date = NOW()",
            [$path_update, $id_member, $login]);

        unset($_SESSION['webcam_tempo_name']);
        unset($_SESSION['webcam_path_tmp']);

        Session::getInstance()->setFlash('success', "Votre photo a bien ete upload");
        App::redirect("webcam.php");
    }

?>