<?php
/**
 * Created by PhpStorm.
 * User: wb
 * Date: 09.10.17
 * Time: 20:12
 */
namespace Entities;

class CategoryEntity
{
    private $id;
    private $name;
    private $colorCode;

    private $colors;
    //private $gifs = array();

    public function __construct(array $data)
    {
        // no id if we're creating
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->name = $data['name'];
        $this->colorCode = $data['colorCode'];
        //$this->gifs = gifs;;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getColorCode()
    {
        return $this->colorCode;
    }

    /**
     * @param mixed $colorCode
     */
    public function setColorCode($colorCode)
    {
        $this->colorCode = $colorCode;
    }

}