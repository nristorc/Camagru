<?php

    require "inc/bootstrap.php";
    App::getAuth()->restrict();
    $db = App::getDatabase();

    $vote = false;

    if (isset($_SESSION['auth']->id)){
        $vote = $db->query('SELECT * FROM camagru.votes WHERE ref_photo = ? AND ref_id = ? AND user_id = ?',
            ['camagru.photo', $_GET['id_photo'], $_SESSION['auth']->id])->fetch();
    }

    $gallery = $db->query('SELECT * FROM camagru.photo WHERE id_photo = ?', [$_GET['id_photo']])->fetch();

    if (!isset($_GET['id_photo']) || $_GET['id_photo'] != $gallery->id_photo){
        Session::getInstance()->setFlash('danger', "La photo recherchée n'existe pas");
        App::redirect("index.php");
    }

?>

<?php require "inc/header.php"; ?>
<body>
    <h1>Les Commentaires et les Likes</h1>

    <article>
        <h1><?=$gallery->login;?></h1>
        <img src="<?= $gallery->path_to_photo;?>">

        <div style="text-align: right;">
            <div class="vote <?= Vote::getClass($vote);?>" id="vote" data-ref_photo="camagru.photo" data-ref_id="<?=$gallery->id_photo;?>" data-user_id="<?=$_SESSION['auth']->id;?>">
                <div class="vote_bar">
                    <div class="vote_progress" style="width:<?= ($gallery->like_count + $gallery->dislike_count) == 0 ? 100 : round(100 * ($gallery->like_count / ($gallery->like_count + $gallery->dislike_count))); ?>%;"></div>
                </div>
                <div class="vote_btns">
                    <button class="vote_btn vote_like"><span id="like_count"><?=$gallery->like_count;?></span></button>
                    <button class="vote_btn vote_dislike"><span id="dislike_count"><?=$gallery->dislike_count;?></span></button>
                </div>
            </div>
        </div>

    </article>

<?php

    $comments = new Comments();

    if ($_POST){
        if (isset($_POST['content']) && !empty($_POST['content'])){
            $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;

            if ($parent_id != 0){
                $comment = $db->query('SELECT id_comment FROM camagru.comments WHERE id_comment = ?', [$parent_id])->fetch();
                if ($comment == false)
                    throw new Exception("Ce parent n'existe pas");
            }

            $req = $db->query('INSERT INTO camagru.comments SET content = ?, parent_id = ?, photo_id = ?, member_id = ?',
                [$_POST['content'], $parent_id, $_POST['photo_id'], $_SESSION['auth']->id]);

            $email = $db->query('SELECT * FROM camagru.photo INNER JOIN camagru.members ON camagru.photo.id_member = camagru.members.id WHERE id_photo = ?', [$_GET['id_photo']])->fetch();
            if ($email->pref_comments_email == 1){
                mail($email->email, 'Un nouveau commentaire a été ajouté', $_SESSION['auth']->login . " a ajouté une nouveau commentaire sur l'une de vos photos.\n\n
                Pour visualiser le commentaire, cliquer sur le lien suivant: http://127.0.0.1:8080/Camagru/comments_likes.php?id_photo=$gallery->id_photo");
            }
            Session::getInstance()->setFlash('success', "Merci pour votre commentaire :)");


        }
        else{
            Session::getInstance()->setFlash('danger', "Vous n'avez rien posté");
        }
        App::redirect("comments_likes.php?id_photo=" . $_GET['id_photo']);
    }

?>
<?php foreach ($comments->findAllWithChildren($db, $gallery->id_photo) as $elem): ?>
    <?php require 'add_comments.php'; ?>
<?php endforeach; ?>

    <form action="" id="form-comment" method="POST">
        <input type="hidden" name="parent_id" value="0" id="parent_id">
        <input type="hidden" name="photo_id" value="<?=$gallery->id_photo;?>" id="photo_id">
        <h4>Poster un commentaire</h4>
        <div class="form-group">
            <textarea name="content" id="content" class="form-control" placeholder="Votre commentaire" required></textarea>
        </div>

        <div class="form-group">
            <button type="submit">Commenter</button>
        </div>
    </form>
</body>
<?php require "inc/footer.php"; ?>