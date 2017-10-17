<?php
/**
 * Created by PhpStorm.
 * User: wb
 * Date: 09.10.17
 * Time: 20:03
 */
namespace Entities;

class GifEntity
{
    private $id;
    private $bytes;
    private $description;
    private $categoryId;
    private $dateUpload;
    private $username;

   public function __construct(array $data)
   {
       // no id if we're creating
       if(isset($data['id'])) {
           $this->id = $data['id'];
       }
       if(isset($data['bytes'])) {
           $this->bytes = $data['bytes'];
       }
       $this->description = $data['description'];
       $this->categoryId = $data['category_id'];
       // no date when creating
       if(isset($data['dateUploaded'])) {
           $this->dateUpload = $data['dateUploaded'];
       }
       // Bez tabeli Users w bazie nazwa jest podawana na sztywno i przy tworzeniu jej nie ma w tablicy
       if(isset($data['username'])) {
           $this->username = $data['username'];
       }

   }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEncodedBytes()
    {
        return base64_encode($this->bytes);
    }

    /**
     * @return mixed
     */
    public function getBytes()
    {
        return $this->bytes;
    }

    /**
     * @param mixed $bytes
     */
    public function setBytes($bytes)
    {
        $this->bytes = $bytes;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return mixed|string
     */
    public function getDateUpload()
    {
        return $this->dateUpload;
    }

    /**
     * @param mixed|string $dateUpload
     */
    public function setDateUpload($dateUpload)
    {
        $this->dateUpload = $dateUpload;
    }

    /**
     * @return mixed|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed|string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


}