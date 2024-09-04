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

    $razon_social = $_POST['razon_social'];
    $rfc = $_POST['rfc'];
    $street = $_POST['street'];
    $ext_num = $_POST['ext_num'];
    $int_num = $_POST['int_num'];
    $colony = $_POST['colony'];
    $locality = $_POST['locality'];
    $zipcode = $_POST['zipcode'];
    $state = $_POST['state'];
    $city = $_POST['city'];

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
        $idClient = $insertAddress['last_id'];

        $stmtContact = "INSERT INTO u803991314_main.clients_billing_data (
            id_clients,
            rfc,
            razon_social,
            street,
            int_number,
            ext_number,
            colony,
            locality,
            zip_code,
            state,
            city
        ) VALUES (
            $idClient,
            '$rfc',
            '$razon_social',
            '$street',
            '$int_num',
            '$ext_num',
            '$colony',
            '$locality',
            '$zipcode',
            '$state',
            '$city'
        )";

        $insertContacto = $queries->InsertData($stmtContact);
        if (!empty($insertContacto)) {
            $idBilling = $insertContacto['last_id'];
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

function updatePaymentDet()
{
    $id_credit_purchase_detail = $_POST['id_credit_purchase_detail'];

    $queries = new Queries;

    $stmt = "UPDATE u803991314_main.credit_purchase_detail SET payment_status = 2 WHERE id_credit_purchase_detail = $id_credit_purchase_detail";

    if ($queries->InsertData($stmt)) {
        $data = array(
            'response' => true,
            'message'                => 'Se ha actualizado el pago!!!',
        );
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
