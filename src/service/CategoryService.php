<?php
/**
 * Created by PhpStorm.
 * User: wb
 * Date: 09.10.17
 * Time: 19:25
 */
namespace Service;
use Entities\CategoryEntity;

class CategoryService extends Service
{
    public function findAll() {
        $sql = "SELECT *
                from Category";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();
        $categories = array();
        if($result) {
            foreach ($stmt as $category) {
                $categories[] = new CategoryEntity($category);
            }
        }
        return $categories;
    }

    public function findById($id) {
        $sql = "SELECT c.id, c.name, c.colorCode
                from Category c 
                where c.id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["id" => $id]);

        if($stmt->rowCount() > 0) {
            return new CategoryEntity($stmt->fetch());
        }
    }

    public function save(CategoryEntity $category) {
        $sql ="INSERT INTO Category
                (name, colorCode) VALUES 
                (:name, :colorCode)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
           "name" => $category->getName(),
            "colorCode" => $category->getColorCode()
        ]);
        if (!$result) {
            throw new \Exception("could not save record!");
        }
        echo "Save!";
    }

    public function delete(CategoryEntity $category) {

    }
}