<?php


require_once('./models/ColorsModel.php');

class ColorsController{
    private $model;

    function __construct()
    {
        $this->model = new ColorsModel();
    }
}
