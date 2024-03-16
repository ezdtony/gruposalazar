<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}

function saveClient()
{
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    /* $street = $_POST['street'];
    $int_num = $_POST['int_num'];
    $ext_num = $_POST['ext_num'];
    $colony = $_POST['colony'];
    $zipcode = $_POST['zipcode'];
    $state = $_POST['state']; */
    $password = $_POST['password'];

    $queries = new Queries;

    $stmt = "INSERT INTO u803991314_main.clients (
        name,
        lastname,
        email,
        password,
        cellphone
    ) VALUES (
        '$name',
        '$lastname',
        '$email',
        '$password',
        '$phonenumber'
    )";

    $insertAddress = $queries->InsertData($stmt);

    if (!empty($insertAddress)) {
        $idUser = $insertAddress['last_id'];

        $stmtContact = "INSERT INTO u803991314_main.colaborators_contact (
            principal_cellphone,
            email
        ) VALUES (
            '$phonenumber',
            '$email'
        )";

        $insertContacto = $queries->InsertData($stmtContact);
        if (!empty($insertContacto)) {
            $idClient = $insertContacto['last_id'];
            $data = array(
                'response' => true,
                'id_colab'                => $idClient,
                'message'                => 'Se ha registrado el cliente con éxito!!!',
            );
        }
        //--- --- ---//

        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
