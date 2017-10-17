<?php
/**
 * Created by PhpStorm.
 * User: wb
 * Date: 09.10.17
 * Time: 19:26
 */
namespace Service;

use Entities\GifEntity;

class GifService extends Service
{
    public function findAll() {
        // Find all gifs
        $sql = "SELECT *
                from Gif";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();
        $gifs = array();
        if($result) {
            foreach ($stmt as $gif) {
                $gifs[] = new GifEntity($gif);
            }
        }
        return $gifs;
    }

    public function findByCategoryId($id) {
        $sql = "SELECT *
                from Gif g 
                WHERE g.category_id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["id" => $id]);
        $gifs = array();
        if($result) {
            foreach ($stmt as $gif) {
                $gifs[] = new GifEntity($gif);
            }
        }
        return $gifs;
    }

    public function findByUserName($userName) {
        $sql = "SELECT *
                from Gif g 
                WHERE g.username = :userName";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["userName" => $userName]);
        $gifs = array();
        if($result) {
            foreach ($stmt as $gif) {
                $gifs[] = new GifEntity($gif);
            }
        }
        return $gifs;
    }

    public function findById($id) {
        // find gif by id
        $sql = "SELECT * 
                FROM Gif g
                WHERE g.id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(["id" => $id]);
        } catch (\PDOException $e) {
            $message =  $e->getMessage();
        }
        if($stmt->rowCount() > 0) {
            return new GifEntity($stmt->fetch());
        }

        // TODO ta walidacja nie działa, można przekroczyć wartości w bazie i wywala błąd constructora

    }

    public function save(GifEntity $gif) {
        $sql ="INSERT INTO Gif
                (bytes, dateUploaded, description, username, category_id) VALUES 
                (:bytes, :dateUploaded, :description, :username, :category_id)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "bytes" => $gif->getBytes(),
            "dateUploaded" => $gif->getDateUpload(),
            "description" => $gif->getDescription(),
            "username" => $gif->getUsername(),
            "category_id" => $gif->getCategoryId()
        ]);

        if(!$result) {
            throw new Exception("could not save record");
        } else {
            $sql ="SELECT * FROM Gif g WHERE 
                g.bytes = :bytes AND g.dateUploaded = :dateUploaded AND g.description = :description AND g.username = :username AND g.category_id = :category_id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                "bytes" => $gif->getBytes(),
                "dateUploaded" => $gif->getDateUpload(),
                "description" => $gif->getDescription(),
                "username" => $gif->getUsername(),
                "category_id" => $gif->getCategoryId()
            ]);
            return new GifEntity($stmt->fetch());
        }

    }

    //TODO zrobienie usuwania - service
    public function delete(GifEntity $gif) {
        $sql = "DELETE FROM Gif WHERE
                id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
               "id" => $gif->getId()
            ]);
        } catch (\PDOException $e) {
            $message = $e->getMessage();
        }

    }

}