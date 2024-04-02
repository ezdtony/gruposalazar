<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}

function saveClientCredit()
{

    $id_client = $_POST['id_client'];
    $ammount = $_POST['ammount'];

    $queries = new Queries;

    $stmt = "INSERT INTO u803991314_main.clients_credits (
        id_clients,
        credit_ammount,
        credit_line,
        datelog
    ) VALUES (
        $id_client,
        '$ammount',
        '$ammount',
        NOW()
    )";

    $insertAddress = $queries->InsertData($stmt);

    if (!empty($insertAddress)) {
        $idCredit = $insertAddress['last_id'];
        $credit_code = "CRSLZ-".$idCredit . "-".rand(0, 20);
        $sqlUpdateCode = "UPDATE u803991314_main.clients_credits SET credit_code = '$credit_code' WHERE id_clients_credits = $idCredit";
        $queries->InsertData($sqlUpdateCode );

        $data = array(
            'response' => true,
            'id_colab'                => $idCredit,
            'message'                => 'Se ha registrado el crédito con éxito!!!',
        );
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
