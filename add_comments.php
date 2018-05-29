<?php

App::getAuth()->restrict();
$db = App::getDatabase();

$post = $db->query('SELECT * FROM camagru.comments WHERE id_comment = ?',
    [$elem->id_comment])->fetch();

?>

<div  id="comment-<?= $elem->id_comment; ?>">
    <div style="border: solid 1px black;">
        <p><?= htmlentities($elem->content); ?></p>
        <div style="text-align: right;">
            <?php
                if ($_SESSION['auth']->id == $elem->member_id){
                    echo "<a href=\"delete_comments.php?id_comment=$elem->id_comment\">Supprimer</a>";
                }
            ?>
            <button class="reply" data-id="<?= $elem->id_comment; ?>">Répondre</button>
        </div>
    </div>
</div>

<div style="margin-left: 50px;">
    <?php if (isset($elem->children)): ?>
        <?php foreach($elem->children as $elem): ?>
            <?php require 'add_comments.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>