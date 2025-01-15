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
function getClientBillingInfo()
{
    $id_client = $_POST['id_client'];

    $queries = new Queries;

    $stmt = "SELECT bid.*, colabs.email
        FROM u803991314_main.clients AS colabs
        INNER JOIN u803991314_main.clients_billing_data as bid ON bid.id_clients = colabs.id_clients
        WHERE colabs.id_clients  = $id_client";

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
function updateColabProp()
{
    $id_colab = $_POST['id_colab'];
    $active = $_POST['active'];

    $queries = new Queries;

    $stmt = "UPDATE u803991314_main.colaborators SET status = $active WHERE id_colaborator = $id_colab";


    if ($queries->InsertData($stmt)) {


        //--- --- ---//
        $data = array(
            'response' => true,
            'message'                => 'Actualizado exitosamente'
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Ocurrió un error'
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

            $colab_code = substr($curp, 0, 4) . "-" . $prenum . $lastUserID . "-" . $pre_str;
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

function getColabInfo()
{
    $colabId = $_POST['id_colab'];
    $queries = new Queries;
    // Asegúrate de tener una conexión PDO configurada
    global $pdo; // Usa tu variable de conexión global
    try {
        // Consultar datos del colaborador principal
        $stmt = "SELECT 
                c.id_colaborator,
                c.colaborator_code,
                c.name,
                c.lastname,
                c.curp,
                c.rfc,
                c.nss,
                c.business_mail,
                c.password_access,
                ca.street,
                ca.int_number,
                ca.ext_number,
                ca.colony,
                ca.delegation AS city,
                ca.postal_code AS zipcode,
                ca.state,
                cc.principal_cellphone AS phonenumber,
                cc.email,
                rcp.id_user_profiles AS position,
                rcs.id_subsidiary AS subsidiary
            FROM u803991314_main.colaborators AS c
            LEFT JOIN u803991314_main.colaborators_addresess AS ca
                ON c.id_colaborators_addresess = ca.id_colaborators_addresess
            LEFT JOIN u803991314_main.colaborators_contact AS cc
                ON c.id_colaborators_contact = cc.id_colaborators_contact
            LEFT JOIN u803991314_main.relationship_colab_prof AS rcp
                ON c.id_colaborator = rcp.id_colaborator
            LEFT JOIN u803991314_main.relationship_colabs_subs AS rcs
                ON c.id_colaborator = rcs.id_colaborator
            WHERE c.id_colaborator = $colabId
        ";
        $colabData = $queries->getData($stmt);

        if ($colabData) {
            echo json_encode([
                'response' => true,
                'data' => $colabData,
            ]);
        } else {
            echo json_encode([
                'response' => false,
                'message' => 'No se encontró el colaborador con el ID proporcionado.',
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'response' => false,
            'message' => 'Error al obtener los datos del colaborador: ' . $e->getMessage(),
        ]);
    }
}

function updateColab()
{
    $id_colab = $_POST['id_colab'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $curp = $_POST['curp'];
    $rfc = $_POST['rfc'];
    $nss = $_POST['nss'];
    $subsidiary = $_POST['subsidiary'];
    $position = $_POST['position'];
    $assigned_mail = $_POST['assigned_mail'];
    $password = $_POST['password'];

    $queries = new Queries;

    // Actualizar datos del colaborador
    $stmt = "UPDATE u803991314_main.colaborators 
             SET name = :name, lastname = :lastname, curp = :curp, rfc = :rfc, 
                 nss = :nss, business_mail = :assigned_mail, password_access = :password 
             WHERE id_colaborator = :id_colab";
    $params = [
        ":name" => $name,
        ":lastname" => $lastname,
        ":curp" => $curp,
        ":rfc" => $rfc,
        ":nss" => $nss,
        ":assigned_mail" => $assigned_mail,
        ":password" => $password,
        ":id_colab" => $id_colab,
    ];
    $updateColab = $queries->executeQuery($stmt, $params);

    // Actualizar relación con la sucursal
    $stmtSubs = "UPDATE u803991314_main.relationship_colabs_subs 
                 SET id_subsidiary = :subsidiary 
                 WHERE id_colaborator = :id_colab";
    $paramsSubs = [
        ":subsidiary" => $subsidiary,
        ":id_colab" => $id_colab,
    ];
    $updateSubs = $queries->executeQuery($stmtSubs, $paramsSubs);

    // Actualizar relación con el puesto
    $stmtPos = "UPDATE u803991314_main.relationship_colab_prof 
                SET id_user_profiles = :position 
                WHERE id_colaborator = :id_colab";
    $paramsPos = [
        ":position" => $position,
        ":id_colab" => $id_colab,
    ];
    $updatePos = $queries->executeQuery($stmtPos, $paramsPos);

    if ($updateColab && $updateSubs && $updatePos) {
        $data = [
            "response" => true,
            "message" => "Cambios guardados con éxito.",
        ];
    } else {
        $data = [
            "response" => false,
            "message" => "Ocurrió un error al guardar los cambios.",
        ];
    }

    echo json_encode($data);
}
function deleteColab()
{
    $id_colab = $_POST['id_colab'];
    $queries = new Queries();

    // Validar el ID recibido
    if (empty($id_colab) || !is_numeric($id_colab)) {
        echo json_encode([
            'response' => false,
            'message' => 'ID de colaborador inválido.',
        ]);
        return;
    }

    try {
        // Eliminar relaciones en tablas dependientes
        $stmtDeleteProf = "DELETE FROM relationship_colab_prof WHERE id_colaborator = :id_colab";
        $queries->executeQuery($stmtDeleteProf, [':id_colab' => $id_colab]);

        $stmtDeleteSubs = "DELETE FROM relationship_colabs_subs WHERE id_colaborator = :id_colab";
        $queries->executeQuery($stmtDeleteSubs, [':id_colab' => $id_colab]);

        // Eliminar el colaborador
        $stmt = "DELETE FROM colaborators WHERE id_colaborator = :id_colab";
        $result = $queries->executeQuery($stmt, [':id_colab' => $id_colab]);

        if ($result) {
            echo json_encode([
                'response' => true,
                'message' => 'Colaborador eliminado con éxito.',
            ]);
        } else {
            echo json_encode([
                'response' => false,
                'message' => 'No se pudo eliminar al colaborador.',
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'response' => false,
            'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
        ]);
    }
}




function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
