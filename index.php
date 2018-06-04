<?php include_once 'config/setup.php' ?>

<?php include "inc/bootstrap.php";
$auth = App::getAuth();

?>

<?php include "inc/header.php"; ?>
<body>
    <h1 id="h1_index"> Toutes les photos disponibles sur notre site </h1>

<?php

$db = App::getDatabase();

$return = $db->query("SELECT COUNT(id_photo) as nbrPhoto FROM camagru.photo")->fetch();

$nbrPhoto = $return->nbrPhoto;

$perPage = 5;
$nbrPage = ceil($nbrPhoto / $perPage);

if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbrPage){
    $currentPage = $_GET['page'];
}
else
    $currentPage = 1;

$ret = $db->query("SELECT * FROM camagru.photo ORDER BY creation_date DESC LIMIT ". (($currentPage - 1) * $perPage) .", $perPage");
while ($display_gallery = $ret->fetch()) {

    ?>

    <div id="photo_index">
        <?= $display_gallery->login;?><br/>
        <hr/>
        <img src="<?= $display_gallery->path_to_photo;?>" /> <br/><br/>
        <div style="display: flex; justify-content: space-evenly;">
            <p><?php echo $display_gallery->like_count?> likes</p>
            <p><?php echo $display_gallery->dislike_count?> dislikes</p>
        </div>

        <a href="comments_likes.php?id_photo=<?=htmlentities($display_gallery->id_photo);?>">Plus de détails</a>
    </div>

    <?php
}

for ($i = 1; $i <= $nbrPage; $i++){
    if ($i == $currentPage)
        echo " $i /";
    else
        echo " <a href=\"index.php?page=$i\">$i</a> /";
}

?>

</body>
    <div class="clear"></div>
<?php include "inc/footer.php"; ?>