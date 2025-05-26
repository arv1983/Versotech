<?php

require_once('./models/UsersModel.php');
require_once('./models/ColorsModel.php');

class UsersController{
    private $model;

    function __construct()
    {
        $this->model = new UsersModel();
        $this->colorsModel = new ColorsModel();
    }

    function getAll(){
        $users = $this->model->getAll(PDO::FETCH_OBJ);
        $colors = $this->colorsModel->getAll(PDO::FETCH_OBJ);
        $colors = array_map(function($color) {
            return $color['name'];  // acessa pelo índice associativo
        }, $colors);

        
        require_once('./views/UsersView.php');
    }

    function deleteUser($user)
    {
        $result = $this->model->deleteUser($user);
        $_SESSION['message'] = [
            'type' => $result ? 'success' : 'danger',
            'text' => $result ? 'Usuário deletado com sucesso!' : 'Erro ao deletar o usuário.'
        ];
        $this->getAll();
    }

    function newUser($user)
    {
        
        if($user['email']){
            $result = $this->model->getUserByEmail($user['email']);
            if($result){
                $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Email já cadastrado.'
                ];
            return $this->getAll();
            }
        }else{
            $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Campo email é obrigatório.'
            ];
            return $this->getAll();
        }
        

        $result = $this->model->newUser($user);

        $_SESSION['message'] = [
            'type' => $result ? 'success' : 'danger',
            'text' => $result ? 'Usuário cadastrado com sucesso!' : 'Erro ao cadastrar o usuário.'
        ];
        $this->getAll();
    }


    function editUser($user)
    {
        $result = $this->model->editUser($user);

        $_SESSION['message'] = [
            'type' => $result ? 'success' : 'danger',
            'text' => $result ? 'Usuário atualizado com sucesso!' : 'Erro ao atualizar o usuário.'
        ];
        $this->getAll();
    }
}