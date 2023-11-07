<?php
include_once dirname(__DIR__.'',1 )."/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}


getUserInfo();

function getUserInfo()
{
    //$user = $_POST['user'];
    //$password = $_POST['password'];

    $queries = new Queries;

        $stmt = "SELECT * 
        FROM u803991314_main.colaborators";    
         
    $getUserInfo = $queries->getData($stmt);

    if (!empty($getUserInfo)) {

        foreach ($getUserInfo as $key) {
            $_SESSION['user']=$key->name." ".$key->lastname;
            $_SESSION['id_user']=$key->id_colaborator;
            /* $_SESSION['id_area']=$key->id_areas; */
            /* $_SESSION['id_areas_level']=$key->id_niveles_areas; */
            /* $_SESSION['txt_area']=$key->descripcion_area; */
            /* $_SESSION['txt_area_level']=$key->puesto_area; */
        }
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getUserInfo
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