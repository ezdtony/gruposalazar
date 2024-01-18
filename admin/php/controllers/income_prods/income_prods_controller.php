<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}


function searchProductIncome()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    //$product_name = $_POST['product_name'];
    $colsSearch = [
        'brand',
        'product_name',
        'product_short_name',
        'product_code',
        'product_barcode',
        'sku'
    ];
    $limit =  5;


    $where = "";
    if (isset($_POST['searchProd']) && ($_POST['searchProd'] != '')) {
        $searchProd = $_POST['searchProd'];
        $where .= " WHERE (";
        for ($i = 0; $i < count($colsSearch); $i++) {
            $where .= $colsSearch[$i] . " LIKE '%" . addslashes($searchProd) . "%' OR ";
        }
        $where = substr($where, 0, -3);
        $where .= ") AND active_item = 1 ";
    }
    //echo $limit;
    $html = "";





    $sql = "SELECT  brand,
    prods.*
    FROM u803991314_main.products AS prods
    INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
    $where 
    ORDER BY prods.product_name ASC
    LIMIT $limit
    ";
    $getProducts = $queries->getData($sql);

    $total_stock = 0;

    if (!empty($getProducts)) {
        $totalResults = count($getProducts);


        $html .= '<ul class="list-group liProductSearch">';
        foreach ($getProducts as $product) {
            $html .= '<li class="list-group-item itemProductSearch" data-max-stock="' . $product->ideal_stock . '" data-id-product="' . $product->id_prducts . '" data-product-price-sell="' . $product->price . '" data-product-price-buy="' . $product->purchase_price . '"   data-product-brand="' . $product->brand . '"  data-product-code="' . $product->product_code . '" data-product-name="' . $product->product_name . '" >' . $product->product_name . '</li>';
        }
        $html .= '</ul>';

        $data = array(
            'response' => true,
            'html' => $html,
        );
    } else {
        $html = '<ul class="list-group">
        <li class="list-group-item">NINGÚN PRODUCTO COINCIDE CON EL CRITERIO DE BÚSQUEDA</li>
        </ul>';
        $data = array(
            'response' => false,
            'html' => $html
        );
    }



    echo json_encode($data);
}
function insertIncomeOrder()
{

    $queries = new Queries;

    $id_subsidiary = $_POST['id_subsidiary'];
    $products_income = $_POST['products_income'];
    $colsSearch = [
        'brand',
        'product_name',
        'product_short_name',
        'product_code',
        'product_barcode',
        'sku'
    ];
    $limit =  5;
    $sqlInsertProductsIncome = "INSERT INTO u803991314_main.income_orders (
        id_subsidiary,
        id_income_status,
        id_colaborator,
        date_income,
        date_register,
        datelog
    )
    VALUES (
        $id_subsidiary,
        1,
        $_SESSION[id_user],
        NOW(),
        NOW(),
        NOW()
    )
    ";

    $insert = $queries->insertData($sqlInsertProductsIncome);


    if (!empty($insert)) {
        $id_income_orders = $insert['last_id'];

        for ($i = 0; $i < count($products_income); $i++) {
            $id_product = $products_income[$i][0];
            $quantity = $products_income[$i][1];

            $sqlInsertProductsIncomeDetail = "INSERT INTO u803991314_main.income_details (
                id_income_orders,
                id_prducts,
                id_income_status,
                quantity
            )
            VALUES(
                $id_income_orders,
                $id_product,
                1,
                $quantity

            )";
            $insertDetail = $queries->insertData($sqlInsertProductsIncomeDetail);
        }

        $data = array(
            'response' => true,
            'message' => 'Se ha registrado la orden de entrada!!'
        );
        
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al registrar la orden'
        );
    }



    echo json_encode($data);
}
