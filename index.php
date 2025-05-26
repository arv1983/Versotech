<?php

require './config/connection.php';
require './controllers/UsersController.php';
require './controllers/ColorsController.php';
require './controllers/UserColorsController.php';

$usersController = new UsersController();
$colorsController = new ColorsController();
$userColorsController = new UserColorsController();


$action = (isset($_GET['action']) || isset($_POST['action'])) ? (isset($_POST['action']) ? $_POST['action'] : $_GET['action']) : 'index';

switch($action){
    
    case "deleteUser":
        $usersController->{$action}($_POST);
        break;
    case "newUser":
        $usersController->{$action}($_POST);
        break;
    case "editUser":
        $usersController->{$action}($_POST);
        break;
    case "getColorsUser":
        $userId = $_GET['id'] ?? null;
        if ($userId) {
            $userColors = $userColorsController->{$action}($userId);
            $colorList = array_column($userColors, 'color_id');

            header('Content-Type: application/json');
            echo json_encode([
                'colors' => $colorList
            ]);
        } else {
            echo json_encode(['colors' => []]);
        }
        break;

    default:
        $usersController->getAll();
 }



