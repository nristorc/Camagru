<?php

require "inc/bootstrap.php";
App::getAuth()->restrict();
$db = App::getDatabase();

$gallery = $db->query('SELECT * FROM camagru.photo WHERE id_photo = ?', [$_GET['id_photo']])->fetch();

?>

<?php require "inc/header.php"; ?>

<h1>Les Commentaires</h1>

<article>
    <h1><?=$gallery->login;?></h1>
    <img src="<?= $gallery->path_to_photo;?>">

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
            Pour visualiser le commentaire, cliquer sur le lien suivant: http://127.0.0.1:8080/Camagru/display_comments.php?id_photo=$gallery->id_photo");
        }
        Session::getInstance()->setFlash('success', "Merci pour votre commentaire :)");


    }
    else{
        Session::getInstance()->setFlash('danger', "Vous n'avez rien posté");
    }
    App::redirect("display_comments.php?id_photo=" . $_GET['id_photo']);
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

<?php require "inc/footer.php"; ?>
