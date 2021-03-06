<?php

    require_once 'inc/bootstrap.php';

    App::getAuth()->restrict();

    if (!empty($_POST) && empty($_FILES)) {
        $auth = App::getAuth();
        $db = App::getDatabase();

        if ((!empty($_POST['firstname']) || !empty($_POST['lastname']) || !empty($_POST['lastname']) || !empty($_POST['email']) || !empty($_POST['birthdate'])) && (empty($_POST['password']) && empty($_POST['password_confirm']))) {

            $user_id = $_SESSION['auth']->id;
            $auth = App::getAuth();
            $db = App::getDatabase();
            $validator = new Validator($_POST);

            $validator->isName('firstname', "Votre Prénom contient une erreur de frappe. Merci de mettre un espace pour les Prénoms composés");
            $validator->isName('lastname', "Votre Nom contient une erreur de frappe. Merci de mettre un espace pour les Noms composés");
            $validator->isDate('birthdate', "Votre date de naissance semble être erronée");

            $validator->isAlpha('login', "Votre login n'est pas valide (alphanumérique)");
            if ($validator->isValid() && ($_SESSION['auth']->login != $_POST['login'])) {
                $validator->isUniq('login', $db, 'camagru.members', 'Ce login est déja pris');
            }

            $validator->isEmail('email', "Votre email n'est pas valide");
            if ($validator->isValid() && ($_SESSION['auth']->email != $_POST['email'])) {
                $validator->isUniq('email', $db, 'camagru.members', "Cet email est déjà utilisé pour un autre compte");
            }

            if ($validator->isValid()) {
                $db->query('UPDATE camagru.members SET firstname = ?, lastname = ?, login = ?, email = ?,birthdate = ? WHERE id = ?', [$_POST['firstname'], $_POST['lastname'], $_POST['login'], $_POST['email'], $_POST['birthdate'], $user_id]);
                $_SESSION['auth']->firstname = $_POST['firstname'];
                $_SESSION['auth']->lastname = $_POST['lastname'];
                $_SESSION['auth']->login = $_POST['login'];
                $_SESSION['auth']->birthdate = $_POST['birthdate'];
                $_SESSION['auth']->email = $_POST['email'];
                Session::getInstance()->setFlash('success', "Vos informations personnelles ont bien été mis à jour");
            }
            else {
                $errors = $validator->getErrors();
            }
        }

        elseif (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {

            Session::getInstance()->setFlash('danger', "Les mots de passe ne correspondent pas");
        }
        else {

            $user_id = $_SESSION['auth']->id;
            $password = $auth->hashPassword($_POST['password']);
            $db->query('UPDATE camagru.members SET password = ? WHERE id = ?', [$password, $user_id]);
            Session::getInstance()->setFlash('success', "Votre mot de passe a bien été mis à jour");
        }
    }

    if (!empty($_FILES)){

        $auth = App::getAuth();
        $db = App::getDatabase();

        $img = $_FILES['img'];

        $ext = strtolower(substr($img['name'], -3));
        $allow_ext = array('jpg', 'png');
        if (!(in_array($ext, $allow_ext))){
            Session::getInstance()->setFlash('danger', "Votre fichier n'a pas un format d'image autorisé");
        }
        else{
            $user_id = $_SESSION['auth']->id;

            $upload_image = "images/" . $img['name'];
            move_uploaded_file($img['tmp_name'], $upload_image);

            if (mime_content_type($upload_image) == 'image/jpeg' || mime_content_type($upload_image) == 'image/png'){
                Img::profil($upload_image, "images/miniatures_profil", $img['name'], 150, 150);
                unlink("images/" . $img['name']);
                $path_update = "images/miniatures_profil/" . $img['name'];

                $db->query('UPDATE camagru.members SET path_to_avatar = ? WHERE id = ?', [$path_update, $user_id]);
                $_SESSION['auth']->path_to_avatar = $path_update;

                Session::getInstance()->setFlash('success', "Votre photo de profil a bien été mis à jour");
            }
            else{
                unlink($upload_image);
                Session::getInstance()->setFlash('danger', "Votre fichier n'a pas un format d'image autorisé");
            }
        }
    }
?>

<?php require_once 'inc/header.php'; ?>
<body>

    <?php if (!empty($errors)): ?>
        <div style="background-color: red; color: white;">
            <p>Vous n'avez pas rempli le formulaire correctement</p>
            <ul>
                <?php foreach ($errors as $elem): ?>
                    <li><?= $elem; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

    <?php endif;?>

    <div id="edit_profil">
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'profil_photo')" id="defaultOpen"> Modifier sa photo de profil </button>
            <button class="tablinks" onclick="openTab(event, 'profil_data')"> Modifier ses données personnelles </button>
            <button class="tablinks" onclick="openTab(event, 'profil_pass')"> Modifier son mot de passe </button>
            <button class="tablinks" onclick="openTab(event, 'profil_pref')"> Préférences </button>
            <button class="tablinks" onclick="openTab(event, 'profil_del')"> Supprimer son Compte </button>
        </div>

        <div id="profil_photo" class="tabcontent">
            <h2> Modifiez votre photo de profil </h2>
            <img src="<?=$_SESSION['auth']->path_to_avatar; ?>" alt="avatar_member"><br/>
            <form action="" method="POST" enctype="multipart/form-data" id="form">
                <input type="hidden" name="MAX_FILE_SIZE" value="2097152"/>
                <input type="file" onchange="ValidateSize(this)" name="img" />
                <br/>
            </form>
            <br/>
        </div>

        <div id="profil_data" class="tabcontent">
            <h2> Modifiez vos données personnelles </h2>
            <form action="" method="post">
                Prénom: <br/><input type="text" name="firstname" value="<?=$_SESSION['auth']->firstname; ?>"><br/>
                Nom: <br/><input type="text" name="lastname" value="<?=$_SESSION['auth']->lastname; ?>"><br/>
                Login: <br/><input type="text" name="login" value="<?=$_SESSION['auth']->login; ?>"><br/>
                Date de Naissance: <br/><input type="text" name="birthdate" value="<?=$_SESSION['auth']->birthdate; ?>"><br/>
                Email : <br/><input type="text" name="email" value="<?=$_SESSION['auth']->email; ?>"><br/>
                <br/>
                <button id="button_edit_2">Changer ses données personnelles</button>
            </form>
        </div>

        <div id="profil_pass" class="tabcontent">
            <h2> Modifiez votre mot de passe </h2>
            <form action="" method="post">
                <input type="password" name="password" placeholder="Changer de mot de passe"><br/>
                <input type="password" name="password_confirm" placeholder="Confirmation du mot de passe"><br/>
                <br/>
                <button id="button_edit_3">Changer mon mot de passe</button>
            </form>
            <br/>
        </div>

        <div id="profil_pref" class="tabcontent">
            <h2> Mes préférences </h2>
            <div>
                Recevoir un email lorsqu'un commentaire est ajouté <br/>à l'une de mes photos<br/>
                <form action="checkbox_profile.php" method="post">
                    <input style="margin: 0 50%;" id="check_pref" type="checkbox" name="checkbox" value="<?=$_SESSION['auth']->pref_comments_email;?>"
                        <?php
                        if ($_SESSION['auth']->pref_comments_email == 1)
                            echo 'checked';
                        ?>
                    >
                    <br/>
                    <button type="submit" id="button_edit_4" name="comments_mail" value="OK">Modifier mes préférences</button>
                </form>
            </div>
            <br/>
        </div>
        <div id="profil_del" class="tabcontent">
            <h2> Supprimer votre compte </h2>
            Attention, cette action est irreversible !
            <br/>
            <form action="delete_account.php" method="post">
                <button style="border: solid 3px crimson; font-weight: bold; color: crimson">SUPPRIMER SON COMPTE</button>
            </form>
        </div>
        <br/>
        <a href="account_logged_in.php">Retour Page de Profil</a>
    </div>
</body>
    <script src="edit_profil.js"></script>
    <div class="clear"></div>
<?php require_once 'inc/footer.php'; ?>