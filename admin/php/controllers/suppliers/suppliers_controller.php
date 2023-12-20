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
    $active_address = $_POST['active_address'];

    $address = 'N/A';
    if ($active_address) {
        $contact_int_num_txt = '';
        if ($contact_int_num != '') {
            $contact_int_num_txt = " Int. " . $contact_int_num;
        }
        $address =  $contact_street . " " . $contact_ext_num . $contact_int_num_txt . " " . $contact_colony . " C.P. " . $contact_zipcode . " " . $city . " " . $state;
    }

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

        $html_tr = '<tr id="trSupplier' . $last_id . '">
        <td class="name fw-bold" id="td_supplier_' . $company . '">
        ' . $company . '
        </td>
        <td class="name " id="td_contact_name_' . $last_id . '">
            ' . $contact_name . '
        </td>
        <td class="name " id="td_email_contact_' . $last_id . '">
        ' . $contact_mail . '
        </td>
        <td class="name " id="td_cellphone_contact' . $last_id . '">
        ' . $contact_phone . '
        </td>
        <td class="name " id="td_address_supplier_' . $last_id . '">
        ' . $address . '
        </td>
        <td class=" text-center">
            <div class="dropdown pull-right" style="float: right !important;">
                <a href="javascript: void(0);" class="dropdown-toggle no-arrow text-secondary" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="14" width="14">
                        <g>
                            <circle cx="12" cy="3.25" r="3.25" style="fill: currentColor"></circle>
                            <circle cx="12" cy="12" r="3.25" style="fill: currentColor"></circle>
                            <circle cx="12" cy="20.75" r="3.25" style="fill: currentColor"></circle>
                        </g>
                    </svg>
                </a>
                <div class="dropdown-menu">
                    <a href="javascript: void(0);" data-id-supplier="' . $last_id . '" class="dropdown-item editSupplier" data-bs-toggle="modal" data-bs-target="#modalEditSuppliers">
                        Editar
                    </a>
                    <a href="javascript: void(0);" class="dropdown-item deleteSupplier" data-id-supplier="' . $last_id . '" style="color:red !important;">
                        Eliminar
                    </a>
                </div>
            </div>
        </td>
    </tr>';
        $data = array(
            'response' => true,
            'message' => 'El proveedor sé registró correctamente',
            'last_id' => $last_id,
            'html' => $html_tr,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se guardaron los archivos'
        );
    }



    echo json_encode($data);
}

function getSupplierInfo()
{



    $id_supplier = $_POST['id_supplier'];

    $queries = new Queries;
    $prod_info = array();

    $sqlCI = "SELECT * FROM u803991314_main.suppliers
    WHERE id_suppliers = $id_supplier";
    //echo $sqlCI;
    $prod_info = $queries->getData($sqlCI);

    if (!empty($prod_info)) {
        $data = array(
            'response' => true,
            'message' => '',
            'supplier_info' => $prod_info
        );
    } else {
        $data = array(
            'response' => true,
            'message' => 'Error al consultar producto',
            'prod_info' => $prod_info
        );
    }

    echo json_encode($data);
}

function deleteSupplier()
{



    $id_suppliers = $_POST['id_supplier'];

    $queries = new Queries;
    $prod_info = array();

    $sqlCI = "UPDATE u803991314_main.suppliers SET active_supplier = 0 WHERE id_suppliers = $id_suppliers";
    $prod_info = $queries->InsertData($sqlCI);
    if (empty($prod_info)) {
        $data = array(
            'response' => true,
            'message' => 'Se eliminó correctamente',
            'prod_info' => $prod_info
        );
    } else {
        $data = array(
            'response' => true,
            'message' => 'Error al eliminar al proveedor',
            'prod_info' => $prod_info
        );
    }

    echo json_encode($data);
}




function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
