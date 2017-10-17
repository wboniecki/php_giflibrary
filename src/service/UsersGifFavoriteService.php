<?php
/**
 * Created by PhpStorm.
 * User: wb
 * Date: 13.10.17
 * Time: 19:04
 */

namespace Service;

use Entities\UsersGifFavoriteEntity;

class UsersGifFavoriteService extends Service
{

    public function save(UsersGifFavoriteEntity $usersGifFavoriteEntity) {
        print_r($usersGifFavoriteEntity);
        $sql ="INSERT INTO UsersGifFavorite
                (user_id, gif_id, favorite) VALUES 
                (:user_id, :gif_id, :favorite)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "user_id" => $usersGifFavoriteEntity->getUserId(),
            "gif_id" => $usersGifFavoriteEntity->getGifId(),
            "favorite" => $usersGifFavoriteEntity->getFavorite()
        ]);

        if(!$result) {
            throw new Exception("could not save record (UsersGifFavorite)");
        }
    }

    public function deleteWithGif($gif_id) {
        $sql = "DELETE FROM UsersGifFavorite
                WHERE gif_id = :gif_id";
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                "gif_id" => $gif_id
            ]);
        } catch (\PDOException $e) {
            $message = $e->getMessage();
        }

    }

    public function findFavorite($user_id, $gif_id) {
        $sql = "SELECT * FROM UsersGifFavorite u
                WHERE u.user_id = :user_id && u.gif_id = :gif_id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "user_id" => $user_id,
            "gif_id" => $gif_id
        ]);
        if ($result) {
            $userGifFavorite = new UsersGifFavoriteEntity($stmt->fetch());
        } else {
            $userGifFavorite = new UsersGifFavoriteEntity([
                "user_id" => $user_id,
                "gif_id" => $gif_id
            ]);
        }

        return $userGifFavorite;
    }

    public function updateFavorite($user_id, $gif_id) {
        $usersGifFavoriteEntity = $this->findFavorite($user_id, $gif_id);
        $sql = "UPDATE UsersGifFavorite
                SET favorite = :favorite
                WHERE user_id = :user_id AND gif_id = :gif_id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "favorite" => !$usersGifFavoriteEntity->getFavorite(),
            "user_id" => $usersGifFavoriteEntity->getUserId(),
            "gif_id" => $usersGifFavoriteEntity->getGifId()
        ]);
        if(!$result) {
            throw new Exception("could not update record (favorite)");
        }

    }

    public function getUserFavoriteGifsId($user_id) {
        $sql = "SELECT * FROM UsersGifFavorite WHERE user_id = :user_id AND favorite = :favorite";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "user_id" => $user_id,
            "favorite" => true
        ]);

        $gifsId = array();
        if ($result) {
            foreach ($stmt as $record) {
                $gifsId[] = $record['gif_id'];
            }
        }

        return $gifsId;
    }

}