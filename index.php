<?php include "inc/bootstrap.php";
$auth = App::getAuth();

?>

<?php include "inc/header.php"; ?>
<body>
<h1 id="h1_index">Toutes les photos disponibles sur notre site</h1>

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

$ret = $db->query("SELECT login, path_to_photo, id_photo FROM camagru.photo ORDER BY creation_date DESC LIMIT ". (($currentPage - 1) * $perPage) .", $perPage");
while ($display_gallery = $ret->fetch()) {

    ?>

    <div id="photo_index">
        <?= $display_gallery->login;?><br/>
        <hr/>
        <img src="<?= $display_gallery->path_to_photo;?>" /> <br/><br/>
        <a href="comments_likes.php?id_photo=<?=$display_gallery->id_photo;?>">Commenter et Liker</a>
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
<?php include "inc/footer.php"; ?>