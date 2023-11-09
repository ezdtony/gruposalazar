<?php
include_once dirname(__DIR__.'',1 )."/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}


function getUserInfo()
{
    $user = $_POST['user'];
    $password = $_POST['password'];

    $queries = new Queries;

        $stmt = "SELECT colab.*, up.description AS user_profile
        FROM u803991314_main.colaborators AS colab
        INNER JOIN u803991314_main.relationship_colab_prof AS rel_col_prof ON rel_col_prof.id_colaborator = colab.id_colaborator
        INNER JOIN u803991314_main.user_profiles AS up ON rel_col_prof.id_user_profiles = up.id_user_profiles
         WHERE (colaborator_code = '$user' OR business_mail = '$user') AND password_access = '$password'";    
         
    $getUserInfo = $queries->getData($stmt);

    if (!empty($getUserInfo)) {

        foreach ($getUserInfo as $key) {
            $_SESSION['user_code']=$key->colaborator_code;
            $_SESSION['user_name']=$key->name." ".$key->lastname;
            $_SESSION['id_user']=$key->id_colaborator;
            $_SESSION['business_mail']=$key->business_mail;
            $_SESSION['user_profile']=$key->user_profile;

            /* $_SESSION['id_area']=$key->id_areas; */
            /* $_SESSION['id_areas_level']=$key->id_niveles_areas; */
            /* $_SESSION['txt_area']=$key->descripcion_area; */
            /* $_SESSION['txt_area_level']=$key->puesto_area; */
        }
        //--- --- ---//
        $data = array(
            'response' => true,
            'data'                => $getUserInfo,
            'session' => $_SESSION
        );
        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => 'Ningún usuario coincide con estos datos!!'
        );
        //--- --- ---//
    }

    echo json_encode($data);
}