<?php require_once 'bootstrap.php' ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <title> Camagru </title>
    </head>
    <header>
        <div id="header_section">
            <div id="logo">
                <a href="index.php"><div id="logo_img"></div></a>
            </div>
            <div id="profil_icon">
                <a href="webcam.php"><img src="ressources/camera.png" alt="camera" title="camera"></a>
                <?php if (isset($_SESSION['auth'])): ?>
                    <a href="account_logged_in.php"><img src="ressources/profil.png" alt="profil" title="Profil"></a>
                <?php else: ?>
                    <a href="account.php"><img src="ressources/profil.png" alt="profil" title="Inscription/Connexion"></a>
                <?php endif; ?>
                <?php if (isset($_SESSION['auth'])): ?>
                    <a href="log_out.php"><img src="ressources/power_off.png" alt="deco" title="Deconnexion"></a>
                <?php endif; ?>
            </div>
        </div>
    </header>

<?php if (Session::getInstance()->hasFlashes()): ?>
    <?php foreach (Session::getInstance()->getFlashes() as $type => $message): ?>
        <?php
            if ($type == 'success'){
                ?>
                <div style="background-color: green; color: white;"<?= $type; ?>>
                    <?= $message; ?>
                </div> <?php
            }
            elseif ($type == 'danger'){
                ?>
                <div style="background-color: red; color: white;"<?= $type; ?>>
                    <?= $message; ?>
                </div>
                <?php
            }
        ?>

    <?php endforeach; ?>
<?php endif; ?>