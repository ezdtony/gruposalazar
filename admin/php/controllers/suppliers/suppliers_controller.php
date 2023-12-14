<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}




function saveSuppliers()
{

    $company = $_POST['company'];
    $contact_name = $_POST['contact_name'];
    $contact_mail = $_POST['contact_mail'];
    $contact_phone = $_POST['contact_phone'];
    $contact_street = $_POST['contact_street'];
    $contact_ext_num = $_POST['contact_ext_num'];
    $contact_int_num = $_POST['contact_int_num'];
    $contact_colony = $_POST['contact_colony'];
    $contact_zipcode = $_POST['contact_zipcode'];
    $state = $_POST['state'];
    $city = $_POST['city'];

    $contact_int_num_txt = '';
    if ($contact_int_num != '') {
        $contact_int_num_txt = "Int. " . $contact_int_num;
    }
    $address =  $contact_street . " " . $contact_ext_num . $contact_int_num_txt . " " . $contact_colony . " " . $contact_zipcode . " " . $city . " " . $state;

    $sql = "INSERT INTO u803991314_main.suppliers
    (
        supplier,
        address_supplier,
        contact_name,
        cellphone_contact,
        email_contact,
        registration_date,
        active_supplier

    ) VALUES(
        '$company',
        '$address',
        '$contact_name',
        '$contact_phone',
        '$contact_mail',
        NOW(),
        1
    )";

    $queries = new Queries;
    $insert = $queries->insertData($sql);


    if (!empty($insert)) {
        $last_id = $insert['last_id'];
        $data = array(
            'response' => true,
            'message' => 'El proveedor sé registró correctamente',
            'last_id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se guardaron los archivos'
        );
    }



    echo json_encode($data);
}



function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
