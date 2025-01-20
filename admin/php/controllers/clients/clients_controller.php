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
    // Datos recibidos del formulario
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
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
    $cdfi = $_POST['cdfi'];
    $reg_fiscal = $_POST['reg_fiscal'];

    $queries = new Queries;

    // Insertar cliente
    $stmt = "INSERT INTO u803991314_main.clients (
        name, lastname, email, password, cellphone
    ) VALUES (
        '$name', '$lastname', '$email', '$password', '$phonenumber'
    )";

    $insertAddress = $queries->InsertData($stmt);

    if (!empty($insertAddress)) {
        $idClient = $insertAddress['last_id'];

        // Insertar datos de facturación
        $stmtContact = "INSERT INTO u803991314_main.clients_billing_data (
            id_clients, rfc, razon_social, street, int_number, ext_number, colony, locality, zip_code, state, city, c_UsoCFDI, c_RegimenFiscal
        ) VALUES (
            $idClient, '$rfc', '$razon_social', '$street', '$int_num', '$ext_num', '$colony', '$locality', '$zipcode', '$state', '$city', '$cdfi', '$reg_fiscal'
        )";

        $insertContacto = $queries->InsertData($stmtContact);
        if (!empty($insertContacto)) {
            $idBilling = $insertContacto['last_id'];
            $data = array(
                'response' => true,
                'id_colab' => $idClient,
                'message' => 'Se ha registrado el cliente con éxito!!!',
            );
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'Error al registrar el cliente'
        );
    }

    echo json_encode($data);
}

// Nueva función para actualizar los datos del cliente
function updateClient()
{
    if (isset($_POST['clientId'])) {
        // Datos recibidos para la actualización
        $idClient = $_POST['clientId'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phonenumber = $_POST['phonenumber'];
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
        $cdfi = $_POST['cdfi'];
        $reg_fiscal = $_POST['reg_fiscal'];

        $queries = new Queries;

        // Actualizar la tabla `clients`
        $stmtClient = "UPDATE u803991314_main.clients SET 
            name = '$name', lastname = '$lastname', email = '$email', password = '$password', cellphone = '$phonenumber'
            WHERE id_clients = $idClient";

        $updateClient = $queries->InsertData($stmtClient);

        // Actualizar la tabla `clients_billing_data`
        $stmtBilling = "UPDATE u803991314_main.clients_billing_data SET 
            rfc = '$rfc', razon_social = '$razon_social', street = '$street', int_number = '$int_num', 
            ext_number = '$ext_num', colony = '$colony', locality = '$locality', zip_code = '$zipcode', 
            state = '$state', city = '$city', c_UsoCFDI = '$cdfi', c_RegimenFiscal = '$reg_fiscal'
            WHERE id_clients = $idClient";

        $updateBilling = $queries->InsertData($stmtBilling);

        if ($updateClient && $updateBilling) {
            $data = array(
                'response' => true,
                'message' => 'Cliente actualizado con éxito!!!'
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Error al actualizar el cliente'
            );
        }

        echo json_encode($data);
    } else {
        echo json_encode(array(
            'response' => false,
            'message' => 'ID del cliente no proporcionado'
        ));
    }
}

function getClientData()
{
    $idClient = $_POST['idClient'];
    $queries = new Queries();

    // Obtener datos del cliente
    $stmtClient = "SELECT * FROM u803991314_main.clients WHERE id_clients = $idClient";
    $client = $queries->getData($stmtClient);

    if ($client) {
        // Obtener datos de facturación
        $stmtBilling = "SELECT * FROM u803991314_main.clients_billing_data WHERE id_clients = $idClient";
        $billingData = $queries->getData($stmtBilling);

        // Unir datos del cliente y datos de facturación
        $clientData = array_merge($client, $billingData);

        echo json_encode([
            'response' => true,
            'client' => $clientData
        ]);
    } else {
        echo json_encode([
            'response' => false,
            'message' => 'No se encontraron datos para este cliente'
        ]);
    }
}

function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
