<?php


require_once('./models/UserColorsModel.php');

class UserColorsController extends Connection{
    private $model;

    function __construct()
    {
        $this->model = new UserColorsModel();
    }

    function getColorsUser($userId){

        $result = $this->model->getColorsUser($userId);
        
        return $result;
    }

    function deleteUserColorsId($userId)
    {
        $result = $this->model->deleteUserColorsId($userId);
        return $result;
    }
    
}
