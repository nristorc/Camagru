<?php

class Comments
{
    public  $comment;
    private $comments_by_id;

    public function findAllWithChildren($db, $photo_id)
    {
        $comments = $db->query('SELECT * FROM camagru.comments WHERE photo_id = ?', [$photo_id])->fetchAll();
        $comments_by_id = [];
        foreach ($comments as $elem) {
            $comments_by_id[$elem->id_comment] = $elem;
        }

        foreach ($comments as $k => $elem) {
            if ($elem->parent_id != 0) {
                $comments_by_id[$elem->parent_id]->children[] = $elem;
                unset($comments[$k]);
            }
        }
        $this->comments_by_id = $comments_by_id;
        return $comments;
    }

    public function deleteComments($db, $id){

        $this->comment = $db->query('SELECT * FROM camagru.comments WHERE id_comment = ?', [$id])->fetch();

        $db->query('DELETE FROM camagru.comments WHERE id_comment = ?', [$id]);

        $db->query('UPDATE camagru.comments SET parent_id = ? WHERE parent_id = ?', [$this->comment->parent_id, $this->comment->id_comment]);

    }

    public function deleteCommentsWithChildren($db, $id)
    {
        $this->comment = $db->query('SELECT * FROM camagru.comments WHERE id_comment = ?', [$id])->fetch();
        $this->findAllWithChildren($db, $this->comment->photo_id);

        $ids = $this->getChildrenIds($this->comments_by_id[$this->comment->id_comment]);
        $ids[] = $this->comment->id_comment;

        return $db->query('DELETE FROM camagru.comments WHERE id_comment IN (' . implode(',', $ids) . ')');
    }

    public function getChildrenIds($comment){

        $ids = [];
        foreach ($comment->children as $child){
            $ids[] = $child->id_comment;
            if (isset($child->children)){
                $ids = array_merge($ids, $this->getChildrenIds($child));
            }
        }
        return $ids;
    }
}
?>