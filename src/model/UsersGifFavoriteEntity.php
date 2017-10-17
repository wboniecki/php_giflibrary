<?php
/**
 * Created by PhpStorm.
 * User: wb
 * Date: 13.10.17
 * Time: 18:58
 */

namespace Entities;


class UsersGifFavoriteEntity
{
    private $id;
    private $user_id;
    private $gif_id;
    private $favorite = false;

    public function __construct($data)
    {
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->user_id = $data['user_id'];
        $this->gif_id = $data['gif_id'];
        if(isset($data['favorite'])) {
            $this->favorite = $data['favorite'];
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
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getGifId()
    {
        return $this->gif_id;
    }

    /**
     * @param mixed $gif_id
     */
    public function setGifId($gif_id)
    {
        $this->gif_id = $gif_id;
    }

    /**
     * @return int
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * @param int $favorite
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;
    }


}