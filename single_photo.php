<?php

    require "inc/bootstrap.php";
    App::getAuth()->restrict();
    $db = App::getDatabase();

    $vote = false;

    if (isset($_SESSION['auth']->id)){
        $vote = $db->query('SELECT * FROM camagru.votes WHERE ref_photo = ? AND ref_id = ? AND user_id = ?',
            ['camagru.photo', $_GET['id_photo'], $_SESSION['auth']->id])->fetch();
    }

    $display_gallery = $db->query('SELECT * FROM camagru.photo WHERE id_photo = ?', [$_GET['id_photo']])->fetch();

?>

<?php include "inc/header.php"; ?>

    <article>
        <h1><?=$display_gallery->login;?></h1>
        <img src="<?= $display_gallery->path_to_photo;?>">
    </article>

<div style="text-align: right;">
    <div class="vote <?= Vote::getClass($vote);?>" id="vote" data-ref_photo="camagru.photo" data-ref_id="<?=$display_gallery->id_photo;?>" data-user_id="<?=$_SESSION['auth']->id;?>">
        <div class="vote_bar">
            <div class="vote_progress" style="width:<?= ($display_gallery->like_count + $display_gallery->dislike_count) == 0 ? 100 : round(100 * ($display_gallery->like_count / ($display_gallery->like_count + $display_gallery->dislike_count))); ?>%;"></div>
        </div>
        <div class="vote_btns">
            <button class="vote_btn vote_like"><span id="like_count"><?=$display_gallery->like_count;?></span></button>
            <button class="vote_btn vote_dislike"><span id="dislike_count"><?=$display_gallery->dislike_count;?></span></button>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
