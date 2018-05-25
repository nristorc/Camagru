<?php
require_once 'inc/bootstrap.php';
App::getAuth()->restrict();
$db = App::getDatabase();
require 'inc/header.php'
?>

<body>
    <div id="account_logged">
        <img src="<?=$_SESSION['auth']->path_to_avatar; ?>" alt="avatar_member">
        <h1>Hello <?=$_SESSION['auth']->firstname; ?></h1>
        <form method="post" action="edit_profile.php">
            <button id="button_accountlogged" type="submit"> Editer son profil </button>
        </form>
    </div>

<?php

    $return = $db->query("SELECT COUNT(id_photo) as nbrPhoto FROM camagru.photo")->fetch();

    $nbrPhoto = $return->nbrPhoto;

    $perPage = 5;
    $nbrPage = ceil($nbrPhoto / $perPage);

    if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbrPage){
        $currentPage = $_GET['page'];
    }
    else
        $currentPage = 1;

    $ret = $db->query("SELECT login, path_to_photo, id_photo, creation_date FROM camagru.photo  WHERE id_member = ? ORDER BY creation_date DESC LIMIT ". (($currentPage - 1) * $perPage) .", $perPage", [$_SESSION['auth']->id]);
    while ($display_gallery = $ret->fetch()) {

        ?>

        <div id="photo_index">
            <?= $display_gallery->login;?><br/>
            <hr/>
            <img onclick='self.location.href="comments_likes.php?id_photo=<?=$display_gallery->id_photo;?>"' src="<?= $display_gallery->path_to_photo;?>" /> <br/><br/>
            Date de création: <?= $display_gallery->creation_date;?><br/><br/>
            <button class="button share_twitter" data-url="http://127.0.0.1:8080/Camagru/comments_likes.php?id_photo=<?= $display_gallery->id_photo;?>" style="background-color: #55ACEE; width:45%; margin: 0 auto; vertical-align: middle"">
            <p style="text-align: center; color: white;"><img src="ressources/twitter_share.png" style="position: relative; width: 10%; margin-right: 8%">Partager sur Twitter</p>
            </button><br><br>
            <button class="button share_facebook" data-url="http://127.0.0.1:8080/Camagru/comments_likes.php?id_photo=<?= $display_gallery->id_photo;?>" style="background-color: #4C67A1; width:45%; margin: 0 auto; vertical-align: middle"">
                <p style="text-align: center; color: white;"><img src="ressources/facebook_share.png" style="position: relative; width: 10%; margin-right: 8%">Partager sur Facebook</p>
            </button><br><br>
            <a href="delete_photo.php?photo_id=<?=$display_gallery->id_photo;?>">Supprimer</a>
        </div>

    <?php
    }

    for ($i = 1; $i <= $nbrPage; $i++){
        if ($i == $currentPage)
            echo " $i /";
        else
            echo " <a href=\"account_logged_in.php?page=$i\">$i</a> /";
    }

    ?>
<script src="share_social.js"></script>
</body>
<?php include "inc/footer.php"; ?>
