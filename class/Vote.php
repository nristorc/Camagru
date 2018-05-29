<?php

class Vote{

    private $former_vote;

    private function recordExists($db, $ref_photo, $ref_id){
        $req = $db->query("SELECT * FROM camagru.photo WHERE id_photo = ?", [$ref_id]);
        if ($req->rowCount() == 0){
            throw new Exception("Impossible de voter pour un enregistrement qui n'existe pas");
        }
    }

    public function like($db, $ref_photo, $ref_id, $user_id){
        if ($this->voted($db, $ref_photo, $ref_id, $user_id, 1)){
            $sql_part = "";
            if ($this->former_vote){
                $sql_part = ", dislike_count = dislike_count - 1";
            }
            $db->query("UPDATE camagru.photo SET like_count = like_count + 1 $sql_part WHERE id_photo = $ref_id");
            return true;
        }
        else{
            $db->query("UPDATE camagru.photo SET like_count = like_count - 1 WHERE id_photo = $ref_id");
        }
        return false;
    }

    public function dislike($db, $ref_photo, $ref_id, $user_id){
        if ($this->voted($db, $ref_photo, $ref_id, $user_id, -1)){
            $sql_part = "";
            if ($this->former_vote){
                $sql_part = ", like_count = like_count - 1";
            }
            $db->query("UPDATE camagru.photo SET dislike_count = dislike_count + 1 $sql_part WHERE id_photo = $ref_id");
            return true;
        }
        else{
            $db->query("UPDATE camagru.photo SET dislike_count = dislike_count - 1 WHERE id_photo = $ref_id");
        }
        return false;
    }

    public static function getClass($vote){
        if ($vote){
            return $vote->vote == 1 ? 'is-liked' : 'is-disliked';
        }
        return null;
    }

    private function voted($db, $ref_photo, $ref_id, $user_id, $vote)
    {
        $this->recordExists($db, $ref_photo, $ref_id);
        $vote_row = $db->query("SELECT id, vote FROM camagru.votes WHERE ref_photo = ? AND ref_id = ? AND user_id = ?",
            [$ref_photo, $ref_id, $user_id])->fetch();

        if ($vote_row){
            if ($vote_row->vote == $vote) {
                $db->query('DELETE FROM camagru.votes WHERE id = ' . $vote_row->id);
                return false;
            }

            $this->former_vote = $vote_row;

            $db->query("UPDATE camagru.votes SET vote = ?, created_at = ? WHERE id = {$vote_row->id}",
                [$vote, date('Y-m-d- H:i:s')]);
            return true;
        }

        $db->query("INSERT INTO camagru.votes SET ref_photo = ?, ref_id = ?, user_id = ?, created_at = ?, vote = $vote",
            [$ref_photo, $ref_id, $user_id, date('Y-m-d- H:i:s')]);
        return true;
    }

    public function updateCount($db, $ref_photo, $ref_id)
    {

        $votes = $db->query("SELECT COUNT(id) AS count, vote FROM camagru.votes WHERE ref_photo = ? AND ref_id = ? GROUP BY vote ",
            [$ref_photo, $ref_id])->fetchAll();

        $counts = ['-1' => 0, '1' => 0];
        foreach ($votes as $vote) {
            $counts[$vote->vote] = $vote->count;
        }

        $db->query("UPDATE camagru.photo SET like_count = {$counts[1]}, dislike_count = {$counts[-1]} WHERE id_photo = $ref_id");
        return true;
    }

}