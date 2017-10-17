<?php
/**
 * Created by PhpStorm.
 * User: wb
 */
namespace Service;

abstract class Service
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

}