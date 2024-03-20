<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}


function searchProduct()
{

    $queries = new Queries;

    $product = $_POST['searchProd'];



    $sql = "SELECT  
    prods.*
    FROM u803991314_main.products AS prods
    WHERE product_barcode = '$product'
    ";
    $getProducts = $queries->getData($sql);

    $total_stock = 0;

    if (!empty($getProducts)) {
        $totalResults = count($getProducts);


        $data = array(
            'response' => true,
            'prod_data' => $getProducts,
        );
    } else {
        $data = array(
            'response' => false
        );
    }



    echo json_encode($data);
}
function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
