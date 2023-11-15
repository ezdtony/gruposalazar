<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}




function getMunicipios()
{
    $id_estado = $_POST['id_estado'];

    $queries = new Queries;

    $stmt = "SELECT munic.* 
        FROM u803991314_main.estados AS est 
        INNER JOIN u803991314_main.estados_municipios AS est_mun ON est.id = est_mun.estados_id
        INNER JOIN u803991314_main.municipios AS munic ON est_mun.municipios_id = munic.id
        WHERE est.id = $id_estado";

    $getMunicipios = $queries->getData($stmt);

    if (!empty($getMunicipios)) {


        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getMunicipios
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
function saveColab()
{
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $curp = $_POST['curp'];
    $rfc = $_POST['rfc'];
    $street = $_POST['street'];
    $int_num = $_POST['int_num'];
    $ext_num = $_POST['ext_num'];
    $colony = $_POST['colony'];
    $zipcode = $_POST['zipcode'];
    $state = $_POST['state'];
    $subsidiary = $_POST['subsidiary'];
    $position = $_POST['position'];
    $assigned_mail = $_POST['assigned_mail'];
    $password = $_POST['password'];
    $city = $_POST['city'];
    $nss = $_POST['nss'];

    $queries = new Queries;

    $stmt = "INSERT INTO u803991314_main.colaborators_addresess (
        street,
        int_number,
        ext_number,
        colony,
        delegation,
        postal_code,
        state
    ) VALUES (
        '$street',
        '$int_num',
        '$ext_num',
        '$colony',
        '$city',
        '$zipcode',
        '$state'
    )";

    $insertAddress = $queries->InsertData($stmt);

    if (!empty($insertAddress)) {
        $idAddress = $insertAddress['last_id'];

        $stmtContact = "INSERT INTO u803991314_main.colaborators_contact (
            principal_cellphone,
            email
        ) VALUES (
            '$phonenumber',
            '$email'
        )";

        $insertContacto = $queries->InsertData($stmtContact);
        if (!empty($insertContacto)) {
            $idContact = $insertContacto['last_id'];

            $stmtLastUser = "SELECT id_colaborator FROM u803991314_main.colaborators ORDER BY id_colaborator DESC";
            $LastUSer = $queries->getData($stmtLastUser);
            if (!empty($LastUSer)) {
                $lastUserID = ($LastUSer[0]->id_colaborator);
                $lastUserID++;
            } else {
                $lastUserID = rand(1, 9);
            }
            if (strlen($lastUserID) > 1) {
                $prenum = "0";
            } else {
                $prenum = "00";
            }
            $pre_str = generateRandomString(3);

            $colab_code = substr($curp, 0, 4) . "-" . $prenum.$lastUserID . "-" . $pre_str;
            $stmtColab = "INSERT INTO u803991314_main.colaborators (
                id_colaborators_contact,
                id_colaborators_addresess,
                colaborator_code,
                lastname,
                name,
                curp,
                rfc,
                nss,
                business_mail,
                password_access,
                status
            ) VALUES (
                $idContact,
                $idAddress,
                '$colab_code',
                '$lastname',
                '$name',
                '$curp',
                '$rfc',
                '$nss',
                '$assigned_mail',
                '$password',
                1
            )";

            $insertColab = $queries->InsertData($stmtColab);

            if (!empty($insertColab)) {
                $id_colab = $insertColab['last_id'];
                $stmtColabProf = "INSERT INTO u803991314_main.relationship_colab_prof (
                    id_user_profiles,
                    id_colaborator
                ) VALUES (
                    $position,
                    $id_colab
                )";

                $insertColabProf = $queries->InsertData($stmtColabProf);

                $stmtColabSubs = "INSERT INTO u803991314_main.relationship_colabs_subs (
                    id_colaborator,
                    id_subsidiary,
                    status
                ) VALUES (
                    $id_colab,
                    $subsidiary,
                    1
                )";

                $insertColabSubs = $queries->InsertData($stmtColabSubs);
                if (!empty($insertColabSubs) && !empty($insertColabSubs)) {
                    $html = '<tr>
                    <td>' . $colab_code . '</td>
                    <td>' . $name . ' ' . $lastname . ' </td>
                    <td>' . $assigned_mail . '</td>
                    <td class="text-end">
                        <div class="fw-bold">' . $password . '</div>
                    </td>
                </tr>';
                    $data = array(
                        'response' => true,
                        'id_colab'                => $id_colab,
                        'message'                => 'Se ha registrado con éxito!!!',
                        'html' => $html
                    );
                } else {
                    $data = array(
                        'response' => false,
                        'message'                => 'Ocurrió un error al registrar el usuario :('
                    );
                }
            }
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
