<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once dirname(__DIR__ . '', 4) . '/vendor/phpmailer/phpmailer/src/Exception.php';
include_once dirname(__DIR__ . '', 4) . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
include_once dirname(__DIR__ . '', 4) . '/vendor/phpmailer/phpmailer/src/SMTP.php';

require_once dirname(__DIR__ . '', 3) . '/assets/fpdf/fpdf.php';

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
    $_id_subsidiary = $_POST['id_subsidiary'];



    $sql = "SELECT  
        prods.*, stk.stock AS sub_stock
        FROM u803991314_main.products AS prods
        LEFT JOIN u803991314_main.subsidiary_stocks AS stk ON stk.prducts_id_prducts = prods.id_prducts AND stk.id_subsidiary = $_id_subsidiary
        WHERE product_barcode = '$product' ";
    $getProducts = $queries->getData($sql);

    $total_stock = 0;

    if (!empty($getProducts)) {

        $id_prducts = $getProducts[0]->id_prducts;
        $sqlGetDiscount = "SELECT DISTINCT percentage, offer_name
                FROM u803991314_main.products AS prods
                INNER JOIN u803991314_main.relationship_products_tags AS rpt ON rpt.id_prducts = prods.id_prducts
                INNER JOIN u803991314_main.relationship_offers_tags AS rot ON rpt.id_tags = rot.id_tags
                INNER JOIN u803991314_main.offers AS offr ON offr.id_offers = rot.id_offers
                WHERE prods.id_prducts = $id_prducts
                ";

        $getDiscount = $queries->getData($sqlGetDiscount);

        $prod_price_sell = round($getProducts[0]->price, 2);
        $prod_price_sell_og = round($getProducts[0]->price, 2);


        if (!empty($getDiscount)) {
            $prod_discount = $getDiscount[0]->percentage;
            $prod_price_sell = round($getProducts[0]->price, 2);

            $montoDescuento = ($prod_price_sell * $prod_discount) / 100;

            // Cálculo del precio final
            $prod_price_sell = round($prod_price_sell - $montoDescuento, 2);
        }


        $totalResults = count($getProducts);


        $data = array(
            'response' => true,
            'prod_data' => $getProducts,
            'prod_price' => $prod_price_sell
        );
    } else {
        $data = array(
            'response' => false,
            'message' => "Producto no encontrado",
        );
    }



    echo json_encode($data);
}


function SaveOrderCash()
{

    $queries = new Queries;

    $id_client = $_POST['id_client'];
    $id_offer = $_POST['id_offer'];
    $id_payment_method = $_POST['id_payment_method'];
    $id_subsidiary = $_POST['id_subsidiary'];
    $pikup_subsidiary = $_POST['pikup_subsidiary'];
    $ammount = $_POST['ammount'];
    $products = $_POST['products'];

    $today = date('Y-m-d H:i:s');

    $sql = "INSERT INTO u803991314_main.orders(
        id_clients,
        id_orders_status_types,
        id_offers,
        id_payment_methods,
        id_subsidiary,
        pikup_subsidiary,
        ammount,
        order_date,
        id_user_registered
    ) VALUES(
            $id_client,
            1,
            $id_offer,
            $id_payment_method,
            $id_subsidiary,
            $pikup_subsidiary,
            '$ammount',
            '$today',
            $_SESSION[id_user]
    )";
    $insert = $queries->InsertData($sql);


    if (!empty($insert)) {
        $last_id = $insert['last_id'];
        $order_id = $insert['last_id'];

        $order_code = 'TFGS-'  . $order_id . "-" . substr(time(), -4);
        $sqlOrCD = "UPDATE u803991314_main.orders SET order_code = '$order_code' WHERE id_orders = $order_id";
        $queries->InsertData($sqlOrCD);

        foreach ($products as $prod) {

            $id_prod = $prod['id_product'];
            $prod_quantity = $prod['quantity'];
            $prod_price = $prod['price'];

            $sql = "INSERT INTO u803991314_main.order_details(
                id_orders,
                id_prducts,
                price,
                quantity
            ) VALUES(
                    $last_id,
                    $id_prod,
                    '$prod_price',
                    $prod_quantity
            )";

            if ($queries->InsertData($sql)) {
                $sqlGetStock = "SELECT stock FROM u803991314_main.subsidiary_stocks WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_prod";
                $getStock = $queries->getData($sqlGetStock);
                $actualStock = $getStock[0]->stock;

                $new_Stock = $actualStock - $prod_quantity;
                $sql = "UPDATE u803991314_main.subsidiary_stocks SET stock = $new_Stock WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_prod";
                $queries->InsertData($sql);
            }
        }

        $data = array(
            'response' => true,
            'order_id' => $order_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => "Error al guardar orden",
        );
    }



    echo json_encode($data);
}

function SaveOrderCreditCard()
{

    $queries = new Queries;

    $id_client = $_POST['id_client'];
    $id_offer = $_POST['id_offer'];
    $id_payment_method = $_POST['id_payment_method'];
    $id_subsidiary = $_POST['id_subsidiary'];
    $pikup_subsidiary = $_POST['pikup_subsidiary'];
    $ammount = $_POST['ammount'];
    $products = $_POST['products'];
    $ticket_id = $_POST['ticket_id'];

    $today = date('Y-m-d H:i:s');

    $sql = "INSERT INTO u803991314_main.orders(
        id_clients,
        id_orders_status_types,
        id_offers,
        id_payment_methods,
        id_subsidiary,
        pikup_subsidiary,
        ammount,
        ticket_id_tpv,
        order_date,
        id_user_registered
    ) VALUES(
            $id_client,
            1,
            $id_offer,
            $id_payment_method,
            $id_subsidiary,
            $pikup_subsidiary,
            '$ammount',
            '$ticket_id',
            '$today',
            $_SESSION[id_user]
    )";
    $insert = $queries->InsertData($sql);


    if (!empty($insert)) {
        $last_id = $insert['last_id'];

        foreach ($products as $prod) {

            $id_prod = $prod['id_product'];
            $prod_quantity = $prod['quantity'];
            $prod_price = $prod['price'];

            $sql = "INSERT INTO u803991314_main.order_details(
                id_orders,
                id_prducts,
                price,
                quantity
            ) VALUES(
                    $last_id,
                    $id_prod,
                    '$prod_price',
                    $prod_quantity
            )";

            if ($queries->InsertData($sql)) {
                $sqlGetStock = "SELECT stock FROM u803991314_main.subsidiary_stocks WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_prod";
                $getStock = $queries->getData($sqlGetStock);
                $actualStock = $getStock[0]->stock;

                $new_Stock = $actualStock - $prod_quantity;
                $sql = "UPDATE u803991314_main.subsidiary_stocks SET stock = $new_Stock WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_prod";
                $queries->InsertData($sql);
            }
        }

        $data = array(
            'response' => true,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => "Error al guardar orden",
        );
    }



    echo json_encode($data);
}
function SaveOrderCredit()
{

    $queries = new Queries;

    $id_client = $_POST['id_client'];
    $id_offer = $_POST['id_offer'];
    $id_payment_method = $_POST['id_payment_method'];
    $id_subsidiary = $_POST['id_subsidiary'];
    $pikup_subsidiary = $_POST['pikup_subsidiary'];
    $ammount = $_POST['ammount'];
    $products = $_POST['products'];
    $credit_client = $_POST['credit_client'];
    $credit_deadlines = $_POST['credit_deadlines'];
    $var_interests = $_POST['var_interests'];

    $today = date('Y-m-d H:i:s');

    $sql = "INSERT INTO u803991314_main.orders(
        id_clients,
        id_orders_status_types,
        id_offers,
        id_payment_methods,
        id_subsidiary,
        pikup_subsidiary,
        ammount,
        order_date,
        id_user_registered
    ) VALUES(
            $id_client,
            1,
            $id_offer,
            $id_payment_method,
            $id_subsidiary,
            $pikup_subsidiary,
            '$ammount',
            '$today',
            $_SESSION[id_user]
    )";
    $insert = $queries->InsertData($sql);


    if (!empty($insert)) {
        $last_id = $insert['last_id'];

        foreach ($products as $prod) {

            $id_prod = $prod['id_product'];
            $prod_quantity = $prod['quantity'];
            $prod_price = $prod['price'];

            $sql = "INSERT INTO u803991314_main.order_details(
                id_orders,
                id_prducts,
                price,
                quantity
            ) VALUES(
                    $last_id,
                    $id_prod,
                    '$prod_price',
                    $prod_quantity
            )";

            if ($queries->InsertData($sql)) {
                $sqlGetStock = "SELECT stock FROM u803991314_main.subsidiary_stocks WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_prod";
                $getStock = $queries->getData($sqlGetStock);
                $actualStock = $getStock[0]->stock;

                $new_Stock = $actualStock - $prod_quantity;
                $sql = "UPDATE u803991314_main.subsidiary_stocks SET stock = $new_Stock WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_prod";
                $queries->InsertData($sql);
            }
        }

        $today = date('Y-m-d H:i:s');
        $id_order = $last_id;
        $sqlInsertCredit = "INSERT INTO u803991314_main.credit_purchases(
            id_clients,
            id_orders,
            id_clients_credits,
            id_credits_deadlines,
            total_purchase,
            interests,
            active_interests,
            datelog
        ) VALUES(
            $id_client,
            $id_order,
            $credit_client,
            $credit_deadlines,
            '$ammount',
            '0.03',
            $var_interests,
            '$today'
        )";
        $insertCreditPurchase = $queries->InsertData($sqlInsertCredit);

        if (!empty($insertCreditPurchase)) {
            $id_credit_purchase = $insertCreditPurchase['last_id'];
            date_default_timezone_set('America/Mexico_City');
            $sqlGetDeadline = "SELECT * FROM  u803991314_main.credits_deadlines WHERE id_credits_deadlines = $credit_deadlines";
            $getDeadline = $queries->getData($sqlGetDeadline);
            $months_term = $getDeadline[0]->months_term;

            $payment_month = $ammount / $months_term;


            $today_date = date("Y-m-d");
            //sumo 1 mes


            for ($i = 0; $i < $months_term; $i++) {

                $paym_date = date("Y-m-d", strtotime($today_date . "+ " . $i + 1 . " month"));

                $sqlInsertDetail = "INSERT INTO u803991314_main.credit_purchase_detail (
                                        id_credit_purchases,
                                        amount_payable,
                                        payment_date,
                                        payment_status,
                                        datelog) 
                                        
                                        VALUES(
                                        $id_credit_purchase,
                                        '$payment_month',
                                        '$paym_date',
                                        1,
                                        '$today'
                                    )";
                $insertDetailCredit = $queries->InsertData($sqlInsertDetail);
            }

            $sqlUpdateCredit = "UPDATE u803991314_main.clients_credits SET credit_ammount = credit_ammount - $ammount WHERE id_clients_credits = $credit_client";
            $queries->InsertData($sqlUpdateCredit);
        }
        $data = array(
            'response' => true,
            'SQL' => $sqlGetDeadline
        );
    } else {
        $data = array(
            'response' => false,
            'message' => "Error al guardar orden",
        );
    }



    echo json_encode($data);
}

function getCreditSalazarClient()
{

    $queries = new Queries;

    $id_client = $_POST['id_client'];
    $total_sale = $_POST['total_sale'];



    $sql = "SELECT * FROM u803991314_main.clients_credits
    WHERE id_clients = $id_client";
    $creditInfo = $queries->getData($sql);

    $html = '';
    if (!empty($creditInfo)) {


        $sql = "SELECT * FROM u803991314_main.credits_deadlines
    WHERE status= 1";
        $creditDeadlines = $queries->getData($sql);

        $html .= '<div class="card" style="max-width: 100%;">';
        $html .= '<div class="row g-0">';
        $html .= '<div class="col-md-12">';
        $html .= '<div class="card-body">';
        $html .= '<h3 class="card-title mb-2">Crédito Salazar</h3><br>';
        $html .= '<h3 class="card-title mb-2">Total: $ ' . $total_sale . 'MXN</h3>';

        $html .= '<p class="card-text mb-0">Realizar compra con Crédito Salazar.</p><br>';

        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="credit_client">Seleccione crédito</label>';
        $html .= '<select id="credit_client" class="form-control">';
        $html .= '<option selected disabled value="">Seleccione una opción</option>';

        foreach ($creditInfo as $prod) {
            $html .= '<option data-aviable-ammount="' . $prod->credit_ammount . '" value="' . $prod->id_clients_credits . '"> (' . ($prod->credit_code) . ') Crédito de $ ' . ($prod->credit_line) . ' MXN</option>';
        }

        $html .= '</select>';
        $html .= '</div>';


        $html .= '<div class="mb-3">';
        $html .= '<label class="form-label" for="credit_deadlines">Plazo de compra</label>';
        $html .= '<select id="credit_deadlines" class="form-control">';
        $html .= '<option selected disabled value="">Seleccione una opción</option>';

        foreach ($creditDeadlines as $prod) {
            $html .= '<option value="' . $prod->id_credits_deadlines . '"  data-months="' . $prod->months_term . '">' . ($prod->deadline_description) . '</option>';
        }

        $html .= '</select>';
        $html .= '</div>';

        $html .= '<div class="form-check form-switch">';
        $html .= '<input class="form-check-input" type="checkbox" role="switch" id="check_interests">';
        $html .= '<label class="form-check-label" for="check_interests">Habiliar intereses</label>';
        $html .= '</div>';
        $html .= '<button type="button" class="btn btn-success" id="btnSaveSaleCredit">Imprimir ticket y guardar venta</button>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $data = array(
            'response' => true,
            'html' => $html
        );
    } else {
        $data = array(
            'response' => false,
            'message' => "Error al obtener información del crédito",
        );
    }



    echo json_encode($data);
}

function getSalesTable()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    //$product_name = $_POST['product_name'];
    $colsSearch = [
        'order_date',
        'subs.subsidiary_name',
        'CONCAT (colab.name, ´ ´, colab.lastname)'
    ];
    $limit =  isset($_POST['limit']) ? $_POST['limit'] : 10;
    $actualPage =  isset($_POST['actualPage']) ? $_POST['actualPage'] : 0;

    if (!$actualPage) {
        $begin = 0;
        $actualPage = 1;
    } else {
        $begin = ($actualPage - 1) * $limit;
    }
    $where = "";
    if (isset($_POST['searchInput']) && ($_POST['searchInput'] != '')) {
        $searchInput = $_POST['searchInput'];
        $where .= " WHERE (";
        for ($i = 0; $i < count($colsSearch); $i++) {
            $where .= $colsSearch[$i] . " LIKE '%" . addslashes($searchInput) . "%' OR ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }


    if ($limit > 0) {
        $limit = " LIMIT $begin,  $limit";
    } else {
        $limit = "";
    }
    //echo $limit;
    $html = "";





    $sql = "SELECT SQL_CALC_FOUND_ROWS
    CASE
        WHEN colab.colaborator_code IS NULL THEN 'N/A'
        ELSE CONCAT(colab.name, ' ', colab.lastname)
        END
        AS colab_reg,
        id_orders,
    ord.ammount,
    ord.factura_generada,
    stty.admin_status_description,
    payment_method_description,
    DATE(order_date) AS order_date,
    subs.subsidiary_name
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered
    $where 
    GROUP BY ord.order_date DESC
    
    $limit
    ";

    $getProducts = $queries->getData($sql);

    $total_stock = 0;

    if (!empty($getProducts)) {
        $totalResults = count($getProducts);

        $sqlAllProdsFiltered = "SELECT FOUND_ROWS() AS founded";
        $getTotalProductsFiltered = $queries->getData($sqlAllProdsFiltered);
        if (!empty($getTotalProductsFiltered)) {
            $totalFiltered = ($getTotalProductsFiltered[0]->founded);
        }

        $sqlAllProds = "SELECT COUNT(id_orders) AS founded
         FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }


        foreach ($getProducts as $product) {
            $btn_dis = '';
            if ($product->factura_generada) {
                $btn_dis = 'disabled';
            }

            $html .= '
            <tr id="trOrder' . $product->id_orders . '">
            <td class="">' . $product->id_orders . '</td>
            <td class="">' . $product->order_date . '</td>
            <td class=""> $ ' . round($product->ammount, 2) . ' MXN</td>
            <td class="">' . $product->admin_status_description . '</td>
            <td class="">' . $product->payment_method_description . '</td>
            <td class="">' . $product->subsidiary_name . '</td>
            <td class="">' . $product->colab_reg . '</td>
            <td class=""> <div class="fw-bold">
            <button type="button"class="btn btn-primary getSaleDetail" data-id-order="' . $product->id_orders . '"
            data-bs-toggle="modal" data-bs-target="#modalSaleDetail"><i class="fa-solid fa-info"></i></button>
            <button type="button"class="btn btn-primary getSaleTicket" data-id-order="' . $product->id_orders . '"
            ><i class="fa-solid fa-print"></i></button>
            <button type="button"class="btn btn-danger generateFactura" ' . $btn_dis . ' id="btnGenFact' . $product->id_orders . '" data-id-order="' . $product->id_orders . '"
            ><i class="fa-solid fa-print"></i></button>
            </div></td>
        </tr>';
        }

        $pagNum = 1;
        if (($actualPage - 4) > 1) {
            $pagNum = $actualPage - 4;
        }
        $totalPages = ceil($totalProds / $_POST['limit']);

        $pagination = '';

        $stopNav = $pagNum + 9;
        if ($stopNav > $totalPages) {
            $stopNav = $totalPages;
        }
        $pagination .= '<nav>';
        $pagination .= '<ul class="nav nav-pills">';

        for ($i = $pagNum; $i <= $stopNav; $i++) {
            $active = $i == $actualPage ? "active" : "";
            $pagination .= '<li class="nav-item">';
            $pagination .= '<a class="nav-link changePage ' . $active . '" aria-current="page" href="#">' . $i . '</a>';
            $pagination .= '</li>';
        }







        $pagination .= '</ul>';
        $pagination .= '</nav>';

        $data = array(
            'response' => true,
            'html' => $html,
            'totalProds' => $totalProds,
            'totalResults' => $totalResults,
            'totalFiltered' => $totalFiltered,
            'totalPages' => $totalPages,
            'paginationNav' => $pagination
        );
    } else {

        $html .= '
                    </tbody>
                </table>';
        $data = array(
            'response' => false,
            'html' => $html
        );
    }



    echo json_encode($data);
}

function getSalesTableFactura()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    //$product_name = $_POST['product_name'];
    $colsSearch = [
        'order_date',
        'order_code',
        'subs.subsidiary_name',
        "CONCAT (colab.name, ' ', colab.lastname)"
    ];
    $limit =  isset($_POST['limit']) ? $_POST['limit'] : 10;
    $actualPage =  isset($_POST['actualPage']) ? $_POST['actualPage'] : 0;

    if (!$actualPage) {
        $begin = 0;
        $actualPage = 1;
    } else {
        $begin = ($actualPage - 1) * $limit;
    }
    $where = "";
    if (isset($_POST['searchInput']) && ($_POST['searchInput'] != '')) {
        $searchInput = $_POST['searchInput'];
        $where .= " WHERE (";
        for ($i = 0; $i < count($colsSearch); $i++) {
            $where .= $colsSearch[$i] . " LIKE '%" . addslashes($searchInput) . "%' OR ";
        }
        $where = substr($where, 0, -3);
        $where .= ") ";
    }


    if ($limit > 0) {
        $limit = " LIMIT $begin,  $limit";
    } else {
        $limit = "";
    }
    //echo $limit;
    $html = "";





    $sql = "SELECT SQL_CALC_FOUND_ROWS
    CASE
        WHEN colab.colaborator_code IS NULL THEN 'N/A'
        ELSE CONCAT(colab.name, ' ', colab.lastname)
        END
        AS colab_reg,
        id_orders,
    ord.ammount,
    order_code,
    factura_generada,
    stty.admin_status_description,
    payment_method_description,
    DATE(order_date) AS order_date,
    subs.subsidiary_name
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered
    $where 
    GROUP BY ord.order_date DESC
    
    $limit
    ";

    $getProducts = $queries->getData($sql);

    $total_stock = 0;

    if (!empty($getProducts)) {
        $totalResults = count($getProducts);

        $sqlAllProdsFiltered = "SELECT FOUND_ROWS() AS founded";
        $getTotalProductsFiltered = $queries->getData($sqlAllProdsFiltered);
        if (!empty($getTotalProductsFiltered)) {
            $totalFiltered = ($getTotalProductsFiltered[0]->founded);
        }

        $sqlAllProds = "SELECT COUNT(id_orders) AS founded
         FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }


        foreach ($getProducts as $product) {

            $btn_dis = '';
            if ($product->factura_generada) {
                $btn_dis = 'disabled';
            }

            $html .= '
            <tr id="trOrder' . $product->id_orders . '">
            <td class="">' . $product->id_orders . '</td>
            <td class="">' . $product->order_code . '</td>
            <td class="">' . $product->order_date . '</td>
            <td class=""> $ ' . round($product->ammount, 2) . ' MXN</td>
            <td class="">' . $product->admin_status_description . '</td>
            <td class="">' . $product->payment_method_description . '</td>
            <td class="">' . $product->subsidiary_name . '</td>
            <td class="">' . $product->colab_reg . '</td>
            <td class=""> <div class="fw-bold">
            <button type="button"class="btn btn-primary getSaleDetail" data-id-order="' . $product->id_orders . '"
            data-bs-toggle="modal" data-bs-target="#modalSaleDetail"><i class="fa-solid fa-info"></i></button>
            <button type="button"class="btn btn-danger generateFactura" ' . $btn_dis . ' id="btnGenFact' . $product->id_orders . '" data-id-order="' . $product->id_orders . '"
            data-bs-toggle="modal" data-bs-target="#modalReceptorData"
            ><i class="fa-solid fa-print"></i></button>
            </div></td>
        </tr>';
        }

        $pagNum = 1;
        if (($actualPage - 4) > 1) {
            $pagNum = $actualPage - 4;
        }
        $totalPages = ceil($totalProds / $_POST['limit']);

        $pagination = '';

        $stopNav = $pagNum + 9;
        if ($stopNav > $totalPages) {
            $stopNav = $totalPages;
        }
        $pagination .= '<nav>';
        $pagination .= '<ul class="nav nav-pills">';

        for ($i = $pagNum; $i <= $stopNav; $i++) {
            $active = $i == $actualPage ? "active" : "";
            $pagination .= '<li class="nav-item">';
            $pagination .= '<a class="nav-link changePage ' . $active . '" aria-current="page" href="#">' . $i . '</a>';
            $pagination .= '</li>';
        }







        $pagination .= '</ul>';
        $pagination .= '</nav>';

        $data = array(
            'response' => true,
            'html' => $html,
            'totalProds' => $totalProds,
            'totalResults' => $totalResults,
            'totalFiltered' => $totalFiltered,
            'totalPages' => $totalPages,
            'paginationNav' => $pagination
        );
    } else {

        $html .= '
                    </tbody>
                </table>';
        $data = array(
            'response' => false,
            'html' => $html
        );
    }



    echo json_encode($data);
}


function getOnlineSalesTable()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    //$product_name = $_POST['product_name'];
    $colsSearch = [
        'order_date',
        'subs.subsidiary_name',
        'CONCAT (colab.name, ´ ´, colab.lastname)'
    ];
    $limit =  isset($_POST['limit']) ? $_POST['limit'] : 10;
    $actualPage =  isset($_POST['actualPage']) ? $_POST['actualPage'] : 0;

    if (!$actualPage) {
        $begin = 0;
        $actualPage = 1;
    } else {
        $begin = ($actualPage - 1) * $limit;
    }
    $where = "";
    if (isset($_POST['searchInput']) && ($_POST['searchInput'] != '')) {
        $searchInput = $_POST['searchInput'];
        $where .= " WHERE (";
        for ($i = 0; $i < count($colsSearch); $i++) {
            $where .= $colsSearch[$i] . " LIKE '%" . addslashes($searchInput) . "%' OR ";
        }
        $where = substr($where, 0, -3);
        $where .= " AND online = 1) ";
    }


    if ($limit > 0) {
        $limit = " LIMIT $begin,  $limit";
    } else {
        $limit = "";
    }
    //echo $limit;
    $html = "";





    $sql = "SELECT SQL_CALC_FOUND_ROWS
    CASE
        WHEN colab.colaborator_code IS NULL THEN 'N/A'
        ELSE CONCAT(colab.name, ' ', colab.lastname)
        END
        AS colab_reg,
        id_orders,
    ord.ammount,
    ord.id_orders_status_types,
    stty.admin_status_description,
    stty.btstr_class,
    payment_method_description,
    DATE(order_date) AS order_date,
    subs.subsidiary_name
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered
    $where 
    GROUP BY ord.order_date DESC
    
    $limit
    ";

    $getProducts = $queries->getData($sql);

    $total_stock = 0;

    if (!empty($getProducts)) {
        $totalResults = count($getProducts);

        $sqlAllProdsFiltered = "SELECT FOUND_ROWS() AS founded";
        $getTotalProductsFiltered = $queries->getData($sqlAllProdsFiltered);
        if (!empty($getTotalProductsFiltered)) {
            $totalFiltered = ($getTotalProductsFiltered[0]->founded);
        }

        $sqlAllProds = "SELECT COUNT(id_orders) AS founded
         FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered 
    WHERE online = 1";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }


        foreach ($getProducts as $product) {
            $prop_enabled = '';
            if ($product->id_orders_status_types != 2) {
                $prop_enabled = 'disabled';
            }

            $prop_enabled_success = '';
            if ($product->id_orders_status_types == 1) {
                $prop_enabled_success = 'disabled';
            }

            $html .= '
            <tr id="trOrder' . $product->id_orders . '">
            <td class="">' . $product->id_orders . '</td>
            <td class="">' . $product->order_date . '</td>
            <td class=""> $ ' . round($product->ammount, 2) . ' MXN</td>
            <td class=""><p style="font-size:1rem !important" class="badge text-bg-' . $product->btstr_class . ' rounded-pill">' . $product->admin_status_description . '</p></td>
            <td class="">' . $product->payment_method_description . '</td>
            <td class=""> <div class="fw-bold">
            <button type="button"class="btn btn-primary getSaleDetail" data-id-order="' . $product->id_orders . '"
            data-bs-toggle="modal" data-bs-target="#modalSaleDetail"><i class="fa-solid fa-info"></i></button>
            <button type="button"class="btn btn-primary getSaleTicket" data-id-order="' . $product->id_orders . '"
            ><i class="fa-solid fa-print"></i></button>
            </div></td>
            <td class=""> <div class="fw-bold">
            <button type="button" ' . $prop_enabled . ' class="btn btn-info saleReady" data-id-order="' . $product->id_orders . '"><i class="fa-solid fa-check"></i></button>
            </div></td>
            <td class=""> <div class="fw-bold">
            <button type="button" ' . $prop_enabled_success . ' class="btn btn-success saleDelivered" data-id-order="' . $product->id_orders . '"><i class="fa-solid fa-check"></i></button>
            </div></td>
        </tr>';
        }

        $pagNum = 1;
        if (($actualPage - 4) > 1) {
            $pagNum = $actualPage - 4;
        }
        $totalPages = ceil($totalProds / $_POST['limit']);

        $pagination = '';

        $stopNav = $pagNum + 9;
        if ($stopNav > $totalPages) {
            $stopNav = $totalPages;
        }
        $pagination .= '<nav>';
        $pagination .= '<ul class="nav nav-pills">';

        for ($i = $pagNum; $i <= $stopNav; $i++) {
            $active = $i == $actualPage ? "active" : "";
            $pagination .= '<li class="nav-item">';
            $pagination .= '<a class="nav-link changePage ' . $active . '" aria-current="page" href="#">' . $i . '</a>';
            $pagination .= '</li>';
        }







        $pagination .= '</ul>';
        $pagination .= '</nav>';

        $data = array(
            'response' => true,
            'html' => $html,
            'totalProds' => $totalProds,
            'totalResults' => $totalResults,
            'totalFiltered' => $totalFiltered,
            'totalPages' => $totalPages,
            'paginationNav' => $pagination
        );
    } else {

        $html .= '
                    </tbody>
                </table>';
        $data = array(
            'response' => false,
            'html' => $html
        );
    }



    echo json_encode($data);
}
function setOrderReady()
{


    $queries = new Queries;
    $id_order = $_POST['id_sale'];

    $saleinfo = array();
    $sqlGetDataOrder = "SELECT 
    order_code,
    shipping_name_client,
    shipping_address,
    order_phone,
    order_mail,
    home_delivery,
    subs_address.*,
    subs.subsidiary_name,
    subs.subsidiary_phone,
    subs.subsidiary_second_phone
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    INNER JOIN u803991314_main.subsidiary_address AS subs_address ON subs_address.id_subsidiary_address = subs.id_subsidiary_address
    WHERE ord.id_orders = $id_order
    ";
    $saleinfo = $queries->getData($sqlGetDataOrder);

    $html = '';
    if (!empty($saleinfo)) {

        $order_code = $saleinfo[0]->order_code;
        $shipping_name_client = $saleinfo[0]->shipping_name_client;
        $shipping_address = $saleinfo[0]->shipping_address;
        $order_phone = $saleinfo[0]->order_phone;
        $order_mail = $saleinfo[0]->order_mail;
        $home_delivery = $saleinfo[0]->home_delivery;


        $subsidiary_name = $saleinfo[0]->subsidiary_name;
        $subsidiary_phone = $saleinfo[0]->subsidiary_phone;


        $txt_entrega = "Por favor acude a la dirección <strong>" . $shipping_address . "</strong> para la entrega en sucursal";
        if ($home_delivery) {
            $txt_entrega = "Nuestro personal acudirá a tu dirección <strong> " . $shipping_address . "</strong> para entregarlo en la comodidad de tu domicilio";
        }

        $sqlGetDataOrder = "UPDATE u803991314_main.orders SET id_orders_status_types = 4
        WHERE id_orders = $id_order
        ";
        $saleinfo = $queries->InsertData($sqlGetDataOrder);

        sendMailConfirmationOrderReady($order_code, $shipping_name_client, $txt_entrega, $order_phone, $order_mail, $subsidiary_name, $subsidiary_phone);



        $data = array(
            'response' => true,
        );
    } else {

        $data = array(
            'response' => false,
        );
    }



    echo json_encode($data);
}

function setOrderDelivered()
{


    $queries = new Queries;
    $id_order = $_POST['id_sale'];

    $saleinfo = array();
    $sqlGetDataOrder = "SELECT 
    order_code,
    shipping_name_client,
    shipping_address,
    order_phone,
    order_mail,
    home_delivery,
    subs_address.*,
    subs.subsidiary_name,
    subs.subsidiary_phone,
    subs.subsidiary_second_phone
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    INNER JOIN u803991314_main.subsidiary_address AS subs_address ON subs_address.id_subsidiary_address = subs.id_subsidiary_address
    WHERE ord.id_orders = $id_order
    ";
    $saleinfo = $queries->getData($sqlGetDataOrder);

    $html = '';
    if (!empty($saleinfo)) {

        $order_code = $saleinfo[0]->order_code;
        $shipping_name_client = $saleinfo[0]->shipping_name_client;
        $order_mail = $saleinfo[0]->order_mail;


        $sqlGetDataOrder = "UPDATE u803991314_main.orders SET id_orders_status_types = 1
        WHERE id_orders = $id_order
        ";
        $saleinfo = $queries->InsertData($sqlGetDataOrder);

        sendMailConfirmationOrderDelivered($order_code, $shipping_name_client, $order_mail);



        $data = array(
            'response' => true,
        );
    } else {

        $data = array(
            'response' => false,
        );
    }



    echo json_encode($data);
}

function sendMailConfirmationOrderReady($order_code, $shipping_name_client, $txt_entrega, $order_phone, $order_mail, $subsidiary_name, $subsidiary_phone)
{


    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function


    //Load Composer's autoloader
    require dirname(__DIR__ . '', 4) . '/vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'eshop.grupo.salazar@gruposalazar.com.mx';                     //SMTP username
        $mail->Password   = 'E8%V7w5#ke';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('eshop.grupo.salazar@gruposalazar.com.mx', utf8_decode('TIENDA EN LÍNEA GRUPO SALAZAR'));
        $mail->addAddress($order_mail, $shipping_name_client);     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo('eshop.grupo.salazar@gruposalazar.com.mx', utf8_decode('TIENDA EN LÍNEA GRUPO SALAZAR'));
        //$mail->addCC('soporte@gruposalazar.com.mx');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        $mail->SMTPDebug = false;
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = utf8_decode('Tu Órden ' . $order_code . ' está Lista');
        $mail->Body    = getHTMLMailOrderReady($order_code, $shipping_name_client, $txt_entrega, $subsidiary_name, $subsidiary_phone);


        $mail->send();



        $data = array(
            'response' => true,
            'message' => 'Su órden ha sido registrada, y se encuentra en proceso de validación'
        );
    } catch (Exception $e) {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al envíar el correo de confirmación'
        );
    }
}


function sendMailConfirmationOrderDelivered($order_code, $shipping_name_client, $order_mail)
{


    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function


    //Load Composer's autoloader
    require dirname(__DIR__ . '', 4) . '/vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'eshop.grupo.salazar@gruposalazar.com.mx';                     //SMTP username
        $mail->Password   = 'E8%V7w5#ke';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('eshop.grupo.salazar@gruposalazar.com.mx', utf8_decode('TIENDA EN LÍNEA GRUPO SALAZAR'));
        $mail->addAddress($order_mail, $shipping_name_client);     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo('eshop.grupo.salazar@gruposalazar.com.mx', utf8_decode('TIENDA EN LÍNEA GRUPO SALAZAR'));
        //$mail->addCC('soporte@gruposalazar.com.mx');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        $mail->SMTPDebug = false;
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = utf8_decode('Tu Órden ' . $order_code . ' fue entregada');
        $mail->Body    = getHTMLMailOrderDelivered($order_code, $shipping_name_client);


        $mail->send();



        $data = array(
            'response' => true,
            'message' => 'Su órden ha sido registrada, y se encuentra en proceso de validación'
        );
    } catch (Exception $e) {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al envíar el correo de confirmación'
        );
    }
}

function getHTMLMailOrderReady($order_code, $shipping_name_client, $txt_entrega, $subsidiary_name, $subsidiary_phone)
{

    $hmtl = '<!DOCTYPE html>
    <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
    
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!--><!--<![endif]-->
        <style>
            * {
                box-sizing: border-box;
            }
    
            body {
                margin: 0;
                padding: 0;
            }
    
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: inherit !important;
            }
    
            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
            }
    
            p {
                line-height: inherit
            }
    
            .desktop_hide,
            .desktop_hide table {
                mso-hide: all;
                display: none;
                max-height: 0px;
                overflow: hidden;
            }
    
            .image_block img+div {
                display: none;
            }
    
            @media (max-width:700px) {
                .desktop_hide table.icons-inner {
                    display: inline-block !important;
                }
    
                .icons-inner {
                    text-align: center;
                }
    
                .icons-inner td {
                    margin: 0 auto;
                }
    
                .image_block div.fullWidth {
                    max-width: 100% !important;
                }
    
                .mobile_hide {
                    display: none;
                }
    
                .row-content {
                    width: 100% !important;
                }
    
                .stack .column {
                    width: 100%;
                    display: block;
                }
    
                .mobile_hide {
                    min-height: 0;
                    max-height: 0;
                    max-width: 0;
                    overflow: hidden;
                    font-size: 0px;
                }
    
                .desktop_hide,
                .desktop_hide table {
                    display: table !important;
                    max-height: none !important;
                }
            }
        </style>
    </head>
    
    <body style="background-color: #f9f9f9; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
        <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f9f9f9;">
            <tbody>
                <tr>
                    <td>
                        <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e5eef9; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 20px; padding-top: 20px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:70px;line-height:70px;font-size:1px;">&#8202;</div>
                                                        <table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="width:100%;">
                                                                    <div class="alignment" align="center" style="line-height:10px">
                                                                        <div style="max-width: 93px;"><img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/4971/check-icon.png" style="display: block; height: auto; border: 0; width: 100%;" width="93" alt="Check Icon" title="Check Icon" height="auto"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="text_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:25px;padding-left:20px;padding-right:20px;padding-top:10px;">
                                                                    <div style="font-family: Georgia, Times New Roman, serif">
                                                                        <div class style="font-size: 14px; font-family: Georgia, Times, Times New Roman, serif; mso-line-height-alt: 16.8px; color: #2f2f2f; line-height: 1.2;">
                                                                            <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:42px;">tu compra está lista</span></p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="text_block block-4" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-left:30px;padding-right:30px;padding-top:10px;">
                                                                    <div style="font-family: sans-serif">
                                                                        <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 21px; color: #2f2f2f; line-height: 1.5;">
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 30px; letter-spacing: normal;"><span style="font-size:20px;">Estimado <strong><u>' . $shipping_name_client . '</u></strong>,</span></p>
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 21px; letter-spacing: normal;">&nbsp;</p>
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 30px; letter-spacing: normal;"><span style="font-size:20px;">Te informamos que tu órden con el código <strong>' . $order_code . ' </strong>
                                                                            ya se encuentra lista para su entrega. ' . $txt_entrega . '<strong><span style>.</span></strong></span></p>
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 21px; letter-spacing: normal;">&nbsp;</p>
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 30px; letter-spacing: normal;"><span style="color:#000000;font-size:20px;">Ante cualquier duda o aclaración no dudes en contactarnos a través de los siguientes medios:</span></p>
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 30px; letter-spacing: normal;"><span style="color:#000000;font-size:20px;">Teléfono: ' . $subsidiary_phone . '</span></p>
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 30px; letter-spacing: normal;"><span style="color:#000000;font-size:20px;">Correo:  soporte@gruposalazar.com.mx</span></p>
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 21px; letter-spacing: normal;">&nbsp;</p>
                                                                            <p style="margin: 0; font-size: 20px; text-align: center; mso-line-height-alt: 30px; letter-spacing: normal;"><span style="color:#000000;font-size:20px;">Una vez más, Grupo Salazar agradece tu preferencia por adquirir tus productos con nosotros.</span></p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div class="spacer_block block-5" style="height:70px;line-height:70px;font-size:1px;">&#8202;</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                        <table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
                                                                    <div class="alignment" align="center" style="line-height:10px">
                                                                        <div class="fullWidth" style="max-width: 442px;"><img src="https://d0518c3adf.imgdist.com/pub/bfra/crn8p0k6/hc7/n24/yvk/navbar_logo_lg.png" style="display: block; height: auto; border: 0; width: 100%;" width="442" alt="Yourlogo Light" title="Yourlogo Light" height="auto"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table><!-- End -->
    </body>
    
    </html>';

    return $hmtl;
}
function getHTMLMailFactura()
{

    $hmtl = '<!DOCTYPE html>
    <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
    
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
        <link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet" type="text/css"><!--<![endif]-->
        <style>
            * {
                box-sizing: border-box;
            }
    
            body {
                margin: 0;
                padding: 0;
            }
    
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: inherit !important;
            }
    
            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
            }
    
            p {
                line-height: inherit
            }
    
            .desktop_hide,
            .desktop_hide table {
                mso-hide: all;
                display: none;
                max-height: 0px;
                overflow: hidden;
            }
    
            .image_block img+div {
                display: none;
            }
    
            sup,
            sub {
                line-height: 0;
                font-size: 75%;
            }
    
            @media (max-width:700px) {
                .desktop_hide table.icons-inner {
                    display: inline-block !important;
                }
    
                .icons-inner {
                    text-align: center;
                }
    
                .icons-inner td {
                    margin: 0 auto;
                }
    
                .image_block div.fullWidth {
                    max-width: 100% !important;
                }
    
                .mobile_hide {
                    display: none;
                }
    
                .row-content {
                    width: 100% !important;
                }
    
                .stack .column {
                    width: 100%;
                    display: block;
                }
    
                .mobile_hide {
                    min-height: 0;
                    max-height: 0;
                    max-width: 0;
                    overflow: hidden;
                    font-size: 0px;
                }
    
                .desktop_hide,
                .desktop_hide table {
                    display: table !important;
                    max-height: none !important;
                }
    
                .reverse {
                    display: table;
                    width: 100%;
                }
    
                .reverse .column.first {
                    display: table-footer-group !important;
                }
    
                .reverse .column.last {
                    display: table-header-group !important;
                }
    
                .row-5 td.column.first .border {
                    padding: 35px 0 35px 25px;
                    border-top: 0;
                    border-right: 0px;
                    border-bottom: 0;
                    border-left: 0;
                }
    
                .row-5 td.column.last .border {
                    padding: 0 0 5px;
                    border-top: 0;
                    border-right: 0px;
                    border-bottom: 0;
                    border-left: 0;
                }
            }
        </style><!--[if mso ]><style>sup, sub { font-size: 100% !important; } sup { mso-text-raise:10% } sub { mso-text-raise:-10% }</style> <![endif]-->
    </head>
    
    <body class="body" style="background-color: #f2f2f2; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
        <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f2f2f2;">
            <tbody>
                <tr>
                    <td>
                        <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 7px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfbfb; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:7px;line-height:7px;font-size:1px;">&#8202;</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfbfb; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-left: 8px; padding-right: 10px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="padding-left:15px;width:100%;padding-right:0px;">
                                                                    <div class="alignment" align="left" style="line-height:10px">
                                                                        <div style="max-width: 264.8px;"><img src="https://a9643fabd5.imgdist.com/pub/bfra/70ubjxk6/6kg/2u5/hbv/navbar_logo_lg.png" style="display: block; height: auto; border: 0; width: 100%;" width="264.8" alt="Logo" title="Logo" height="auto"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfbfb; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 7px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="divider_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:5px;padding-top:5px;">
                                                                    <div class="alignment" align="center">
                                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <tr>
                                                                                <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 3px solid #F2F2F2;"><span style="word-break: break-word;">&#8202;</span></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfbfb; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr class="reverse">
                                                    <td class="column column-1 first" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 35px; padding-left: 25px; padding-top: 35px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="border">
                                                            <table class="heading_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                <tr>
                                                                    <td class="pad" style="padding-left:5px;padding-top:10px;text-align:center;width:100%;">
                                                                        <h1 style="margin: 0; color: #fe7062; direction: ltr; font-family: Cabin, Arial, Helvetica Neue, Helvetica, sans-serif; font-size: 13px; font-weight: 700; letter-spacing: 1px; line-height: 120%; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 15.6px;"><span class="tinyMce-placeholder" style="word-break: break-word;"></span></h1>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table class="heading_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                <tr>
                                                                    <td class="pad" style="padding-bottom:10px;padding-left:5px;padding-right:5px;padding-top:5px;text-align:center;width:100%;">
                                                                        <h1 style="margin: 0; color: #2f2e41; direction: ltr; font-family: Cabin, Arial, Helvetica Neue, Helvetica, sans-serif; font-size: 29px; font-weight: 400; letter-spacing: 1px; line-height: 120%; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 34.8px;"><strong>Gracias por tu compra</strong></h1>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <table class="paragraph_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                <tr>
                                                                    <td class="pad" style="padding-bottom:10px;padding-left:5px;padding-right:5px;padding-top:10px;">
                                                                        <div style="color:#393d47;direction:ltr;font-family:Cabin, Arial, Helvetica Neue, Helvetica, sans-serif;font-size:15px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:left;mso-line-height-alt:22.5px;">
                                                                            <p style="margin: 0;">En Grupo Salazar agradecemos nuevamente por tu preferencia. Recuerda que puedes adquirir tus productos en cualquiera de nuestras sucursales físicas o realizar tu compra en nuestra nueva tienda en línea, que puedes encontrar en este link: <a href="https://gruposalazar.com.mx/" target="_blank" title="Tienda en Línea Grupo Salazar" style="text-decoration: underline; color: #8a3b8f;" rel="noopener">https://gruposalazar.com.mx</a></p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                    <td class="column column-2 last" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="border">
                                                            <table class="image_block block-1" width="100%" border="0" cellpadding="20" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                <tr>
                                                                    <td class="pad">
                                                                        <div class="alignment" align="center" style="line-height:10px">
                                                                            <div class="fullWidth" style="max-width: 282.2px;"><img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/7056/illustration_png-03.png" style="display: block; height: auto; border: 0; width: 100%;" width="282.2" alt="Resume" title="Resume" height="auto"></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfbfb; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:10px;line-height:10px;font-size:1px;">&#8202;</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-7" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-8" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfbfb; background-size: auto; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 15px; padding-left: 25px; padding-right: 25px; padding-top: 25px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="heading_block block-1" width="100%" border="0" cellpadding="5" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <h1 style="margin: 0; color: #2f2e41; direction: ltr; font-family: Cabin, Arial, Helvetica Neue, Helvetica, sans-serif; font-size: 23px; font-weight: 400; letter-spacing: 1px; line-height: 120%; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 27.599999999999998px;"><span class="tinyMce-placeholder" style="word-break: break-word;">Tu factura</span></h1>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-left:5px;padding-right:5px;padding-top:10px;">
                                                                    <div style="color:#393d47;direction:ltr;font-family:Cabin, Arial, Helvetica Neue, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:150%;text-align:left;mso-line-height-alt:21px;">
                                                                        <p style="margin: 0;">La factura electrónica de tu compra se encuentra adjunta en este correo, así como el archivo XML. Ante cualquier duda o aclaración, no dudes en contactarnos.</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-9" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-10" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="divider_block block-1" width="100%" border="0" cellpadding="5" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <div class="alignment" align="center">
                                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <tr>
                                                                                <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #7B7B7B;"><span style="word-break: break-word;">&#8202;</span></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-11" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; background-color: #ffffff; width: 680px; margin: 0 auto;" width="680">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="icons_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: center; line-height: 0;">
                                                            <tr>
                                                                <td class="pad" style="vertical-align: middle; color: #1e0e4b; font-family: Inter, sans-serif; font-size: 15px; padding-bottom: 5px; padding-top: 5px; text-align: center;"><!--[if vml]><table align="center" cellpadding="0" cellspacing="0" role="presentation" style="display:inline-block;padding-left:0px;padding-right:0px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;"><![endif]-->
                                                                    <!--[if !vml]><!-->
                                                                    <table class="icons-inner" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; padding-left: 0px; padding-right: 0px;" cellpadding="0" cellspacing="0" role="presentation"><!--<![endif]-->
                                                                        <tr>
                                                                            <td style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 6px;"><a href="http://designedwithbeefree.com/" target="_blank" style="text-decoration: none;"><img class="icon" alt="Beefree Logo" src="https://d1oco4z2z1fhwp.cloudfront.net/assets/Beefree-logo.png" height="auto" width="34" align="center" style="display: block; height: auto; margin: 0 auto; border: 0;"></a></td>
                                                                            <td style="font-family: Inter, sans-serif; font-size: 15px; font-weight: undefined; color: #1e0e4b; vertical-align: middle; letter-spacing: undefined; text-align: center; line-height: normal;"><a href="http://designedwithbeefree.com/" target="_blank" style="color: #1e0e4b; text-decoration: none;">Designed with Beefree</a></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table><!-- End -->
    </body>
    
    </html>';

    return $hmtl;
}

function getHTMLMailOrderDelivered($order_code, $shipping_name_client)
{
    $html = '<!DOCTYPE html>
    <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
    
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@100;200;300;400;500;600;700;800;900" rel="stylesheet" type="text/css"><!--<![endif]-->
        <style>
            * {
                box-sizing: border-box;
            }
    
            body {
                margin: 0;
                padding: 0;
            }
    
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: inherit !important;
            }
    
            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
            }
    
            p {
                line-height: inherit
            }
    
            .desktop_hide,
            .desktop_hide table {
                mso-hide: all;
                display: none;
                max-height: 0px;
                overflow: hidden;
            }
    
            .image_block img+div {
                display: none;
            }
    
            @media (max-width:640px) {
                .desktop_hide table.icons-outer {
                    display: inline-table !important;
                }
    
                .desktop_hide table.icons-inner {
                    display: inline-block !important;
                }
    
                .icons-inner {
                    text-align: center;
                }
    
                .icons-inner td {
                    margin: 0 auto;
                }
    
                .image_block div.fullWidth {
                    max-width: 100% !important;
                }
    
                .mobile_hide {
                    display: none;
                }
    
                .row-content {
                    width: 100% !important;
                }
    
                .stack .column {
                    width: 100%;
                    display: block;
                }
    
                .mobile_hide {
                    min-height: 0;
                    max-height: 0;
                    max-width: 0;
                    overflow: hidden;
                    font-size: 0px;
                }
    
                .desktop_hide,
                .desktop_hide table {
                    display: table !important;
                    max-height: none !important;
                }
    
                .reverse {
                    display: table;
                    width: 100%;
                }
    
                .reverse .column.last {
                    display: table-header-group !important;
                }
    
                .row-5 td.column.last .border {
                    padding: 25px 20px;
                    border-top: 0;
                    border-right: 0px;
                    border-bottom: 0;
                    border-left: 0;
                }
    
                .row-4 .column-1 .block-1.spacer_block {
                    height: 40px !important;
                }
    
                .row-1 .column-1 {
                    padding: 20px 20px 5px !important;
                }
    
                .row-1 .column-3 {
                    padding: 5px 20px 25px !important;
                }
            }
        </style>
    </head>
    
    <body style="background-color: #e6e6e6; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
        <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e6e6e6;">
            <tbody>
                <tr>
                    <td>
                        <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 620px; margin: 0 auto;" width="620">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-left: 20px; padding-right: 20px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="icons_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: center;">
                                                            <tr>
                                                                <td class="pad" style="vertical-align: middle; color: #000000; font-family: inherit; font-size: 14px; font-weight: 400; text-align: center;">
                                                                    <table class="icons-outer" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-table;">
                                                                        <tr>
                                                                            <td style="vertical-align: middle; text-align: center; padding-top: 0px; padding-bottom: 0px; padding-left: 0px; padding-right: 0px;"><a href="https://example.com" target="_self" style="text-decoration: none;"><img class="icon" src="https://d0518c3adf.imgdist.com/pub/bfra/crn8p0k6/1tz/c6t/unj/navbar_logo_lg_1.png" alt="Logo" height="auto" width="103" align="center" style="display: block; height: auto; margin: 0 auto; border: 0;"></a></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-left: 20px; padding-right: 20px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="empty_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <div></div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="column column-3" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-left: 20px; padding-right: 20px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="empty_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <div></div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 620px; margin: 0 auto;" width="620">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 10px; padding-left: 20px; padding-right: 20px; padding-top: 40px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="heading_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-top:10px;text-align:center;width:100%;">
                                                                    <h1 style="margin: 0; color: #393d47; direction: ltr; font-family: Playfair Display, Georgia, serif; font-size: 38px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 45.6px;"><strong>Gracias por tu preferencia</strong><span class="tinyMce-placeholder">!</span></h1>
                                                                    <h3 style="margin: 0; color: #393d47; direction: ltr; font-family: Playfair Display, Georgia, serif; font-size: 38px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 45.6px;">Tu órden con el código <strong>' . $order_code . '</strong> ha sido entregada existosamente<span class="tinyMce-placeholder">!</span></h3>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="padding-top:30px;width:100%;padding-right:0px;padding-left:0px;">
                                                                    <div class="alignment" align="center" style="line-height:10px">
                                                                        <div class="fullWidth" style="max-width: 232px;"><img src="https://d0518c3adf.imgdist.com/pub/bfra/crn8p0k6/k7k/jwb/heu/2558190.png" style="display: block; height: auto; border: 0; width: 100%;" width="232" alt="Confirm subscription" title="Confirm subscription" height="auto"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; background-color: #f5f5f5; border-left: 20px solid #FFFFFF; border-right: 20px solid #FFFFFF; width: 620px; margin: 0 auto;" width="620">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 25px; padding-left: 20px; padding-right: 20px; padding-top: 25px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="heading_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="text-align:center;width:100%;">
                                                                    <h2 style="margin: 0; color: #393d47; direction: ltr; font-family: Playfair Display, Georgia, serif; font-size: 30px; font-weight: 400; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 36px;">Apreciable ' . $shipping_name_client . '.</h2>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-2" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <div style="color:#101112;direction:ltr;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:center;mso-line-height-alt:19.2px;">
                                                                        <p style="margin: 0; margin-bottom: 16px;">Queremos agradecerte de todo corazón por tu reciente compra en Grupo Salazar. Nos complace saber que has elegido nuestros productos y esperamos que tu adquisición satisfaga tus necesidades.</p>
                                                                        <p style="margin: 0; margin-bottom: 16px;">En Grupo Salazar, nos esforzamos por ofrecer productos de la más alta calidad y un servicio excepcional. Tu apoyo y confianza significan mucho para nosotros, y estamos comprometidos a seguir brindándote la mejor experiencia de compra posible.</p>
                                                                        <p style="margin: 0; margin-bottom: 16px;">Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en ponerte en contacto con nosotros. Estamos aquí para ayudarte en lo que necesites.</p>
                                                                        <p style="margin: 0;">Además, nos encantaría escuchar tus comentarios sobre tu experiencia de compra y los productos adquiridos. Tu opinión nos ayuda a mejorar continuamente. Gracias nuevamente por tu compra. Esperamos verte pronto en <a href="https://www.gruposalazar.com.mx" target="_blank" rel="noopener" style="text-decoration: underline; color: #7747FF;">nuestra tienda en línea Grupo Salazar</a></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; background-color: #1b46c6; border-left: 20px solid #FFFFFF; border-right: 20px solid #FFFFFF; width: 620px; margin: 0 auto;" width="620">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:40px;line-height:40px;font-size:1px;">&#8202;</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 620px; margin: 0 auto;" width="620">
                                            <tbody>
                                                <tr class="reverse">
                                                    <td class="column column-1 last" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 25px; padding-left: 20px; padding-right: 20px; padding-top: 25px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="border">
                                                            <table class="icons_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: center;">
                                                                <tr>
                                                                    <td class="pad" style="vertical-align: middle; color: #000000; font-family: inherit; font-size: 14px; font-weight: 400; text-align: center;">
                                                                        <table class="icons-outer" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-table;">
                                                                            <tr>
                                                                                <td style="vertical-align: middle; text-align: center; padding-top: 0px; padding-bottom: 0px; padding-left: 0px; padding-right: 0px;"><a href="https://example.com" target="_self" style="text-decoration: none;"><img class="icon" src="https://d0518c3adf.imgdist.com/pub/bfra/crn8p0k6/1tz/c6t/unj/navbar_logo_lg_1.png" alt="Logo" height="auto" width="275" align="center" style="display: block; height: auto; margin: 0 auto; border: 0;"></a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table><!-- End -->
    </body>
    
    </html>';

    return $html;
}
function getSaleDetail()
{

    $queries = new Queries;

    $id_order = $_POST['id_sale'];

    $sqlOrderIndex = "SELECT 
    CONCAT(cl.name, ' ', cl.lastname) AS client_name,
    CASE
    WHEN colab.colaborator_code IS NULL THEN 'N/A'
    ELSE CONCAT(colab.name, ' ', colab.lastname)
    END
    AS colab_reg,
    id_orders,
    ord.ammount,
    stty.admin_status_description,
    payment_method_description,
    DATE(order_date) AS order_date,
    subs.subsidiary_name
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered
    INNER JOIN u803991314_main.clients AS cl ON ord.id_clients = cl.id_clients
    WHERE id_orders = $id_order";
    $saleinfo = $queries->getData($sqlOrderIndex);

    $html = '';
    if (!empty($saleinfo)) {
        $html .= '<h4> ID de Compra: ' . $id_order . '</h4>';
        $html .= '<h4> Total: ' . round($saleinfo[0]->ammount, 2) . '</h4>';
        $html .= '<h4> Método de pago: ' . ($saleinfo[0]->payment_method_description) . '</h4>';
        $html .= '<h4> Sucursal: ' . ($saleinfo[0]->subsidiary_name) . '</h4>';

        $html .= '<div class="table-responsive">';
        $html .= '    <table class="table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="table-dark text-white" scope="col" colspan="4">Compra #' . $id_order . ' | Total de compra: $ ' . round($saleinfo[0]->ammount, 2) . ' MXN  | Fecha: ' . $saleinfo[0]->order_date . '</th>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>PRODUCTO</th>';
        $html .= '<th>CANTIDAD</th>';
        $html .= '<th>PRECIO UNITARIO</th>';
        $html .= '<th>SUBTOTAL</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';



        $sqlOrderDetail = "SELECT prd.product_name, ord_det.*
        FROM u803991314_main.order_details AS ord_det
        INNER JOIN u803991314_main.products AS prd ON ord_det.id_prducts = prd.id_prducts
        WHERE id_orders = $id_order";
        $saleDetail = $queries->getData($sqlOrderDetail);

        if (!empty($saleDetail)) {
            foreach ($saleDetail  as $detail) {
                $html .= '<tr>';
                $html .= '<td>' . $detail->product_name . '</td>';
                $html .= '<td>' . $detail->quantity . '</td>';
                $html .= '<td> $ ' . round($detail->price, 2) . ' MXN</td>';
                $html .= '<td> $ ' . round(($detail->price * $detail->quantity), 2) . ' MXN</td>';
                $html .= '</tr>';
            }
        }
        $html .= '<tfoot>';
        $html .= '<tr>';
        $html .= '<th colspan="3"><strong>TOTAL:</strong></th>';
        $html .= '<th colspan="1"><strong>$ ' . round($saleinfo[0]->ammount, 2) . ' MXN</strong></th>';
        $html .= '</tr>';
        $html .= '</tfoot>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .=
            '</div>';

        $data = array(
            'response' => true,
            'html' => $html,
        );
    } else {

        $html .= '
                    </tbody>
                </table>';
        $data = array(
            'response' => false,
            'html' => $html
        );
    }



    echo json_encode($data);
}
function getSaleDetailFactura()
{

    $queries = new Queries;

    $id_order = $_POST['id_sale'];

    $sqlOrderIndex = "SELECT 
    CONCAT(cl.name, ' ', cl.lastname) AS client_name,
    CASE
    WHEN colab.colaborator_code IS NULL THEN 'N/A'
    ELSE CONCAT(colab.name, ' ', colab.lastname)
    END
    AS colab_reg,
    id_orders,
    ord.ammount,
    stty.admin_status_description,
    payment_method_description,
    DATE(order_date) AS order_date,
    subs.subsidiary_name
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered
    INNER JOIN u803991314_main.clients AS cl ON ord.id_clients = cl.id_clients
    WHERE id_orders = $id_order";
    $saleinfo = $queries->getData($sqlOrderIndex);

    $html = '';
    if (!empty($saleinfo)) {
        $html .= '<h4> ID de Compra: ' . $id_order . '</h4>';
        $html .= '<h4> Total: ' . round($saleinfo[0]->ammount, 2) . '</h4>';
        $html .= '<h4> Método de pago: ' . ($saleinfo[0]->payment_method_description) . '</h4>';
        $html .= '<h4> Sucursal: ' . ($saleinfo[0]->subsidiary_name) . '</h4>';

        $html .= '<div class="table-responsive">';
        $html .= '    <table class="table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="table-dark text-white" scope="col" colspan="4">Compra #' . $id_order . ' | Total de compra: $ ' . round($saleinfo[0]->ammount, 2) . ' MXN  | Fecha: ' . $saleinfo[0]->order_date . '</th>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>PRODUCTO</th>';
        $html .= '<th>CANTIDAD</th>';
        $html .= '<th>PRECIO UNITARIO</th>';
        $html .= '<th>SUBTOTAL</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';



        $sqlOrderDetail = "SELECT prd.product_name, ord_det.*
        FROM u803991314_main.order_details AS ord_det
        INNER JOIN u803991314_main.products AS prd ON ord_det.id_prducts = prd.id_prducts
        WHERE id_orders = $id_order";
        $saleDetail = $queries->getData($sqlOrderDetail);

        if (!empty($saleDetail)) {
            foreach ($saleDetail  as $detail) {
                $html .= '<tr>';
                $html .= '<td>' . $detail->product_name . '</td>';
                $html .= '<td>' . $detail->quantity . '</td>';
                $html .= '<td> $ ' . round($detail->price, 2) . ' MXN</td>';
                $html .= '<td> $ ' . round(($detail->price * $detail->quantity), 2) . ' MXN</td>';
                $html .= '</tr>';
            }
        }
        $html .= '<tfoot>';
        $html .= '<tr>';
        $html .= '<th colspan="3"><strong>TOTAL:</strong></th>';
        $html .= '<th colspan="1"><strong>$ ' . round($saleinfo[0]->ammount, 2) . ' MXN</strong></th>';
        $html .= '</tr>';
        $html .= '</tfoot>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .=
            '</div>';

        $data = array(
            'response' => true,
            'html' => $html,
        );
    } else {

        $html .= '
                    </tbody>
                </table>';
        $data = array(
            'response' => false,
            'html' => $html
        );
    }



    echo json_encode($data);
}

function getSaleDataFactura()
{

    $queries = new Queries;

    $id_order = $_POST['id_sale'];


    $prods = [];
    $conceptos = [];

    $subtotal = 0;
    $total = 0;

    $sqlOrderIndex = "SELECT 
    prod.product_name,
    prod.sat_code,
    det.price,
    det.quantity,
    unit.type_measure,
    unit.sat_unity_code,
    ord.order_code,
    pym.codigo_sat AS pay_sat,
    payment_method_description
    FROM u803991314_main.order_details AS det
    INNER JOIN u803991314_main.products AS prod ON prod.id_prducts = det.id_prducts
    INNER JOIN u803991314_main.orders AS ord ON ord.id_orders = det.id_orders
    INNER JOIN u803991314_main.payment_methods AS pym ON pym.id_payment_methods = ord.id_payment_methods
    INNER JOIN u803991314_main.measurement_units AS unit ON unit.id_measurement_units = prod.id_measurement_units
    WHERE det.id_orders = $id_order";
    $saleinfo = $queries->getData($sqlOrderIndex);

    $html = '';
    $order_code = '';
    $valid_info = true;
    if (!empty($saleinfo)) {

        foreach ($saleinfo  as $detail) {
            $order_code = $detail->order_code;
            $pay_sat = $detail->pay_sat;
            $payment_method_description = $detail->payment_method_description;
            $prod_quantity = $detail->quantity;
            $prod_price = $detail->price;
            $productName = $detail->product_name;
            $prodImporte = round(bcmul($prod_price, $prod_quantity, 10), 2);
            $prod_sat_code = $detail->sat_code;
            $prodSatUnity = $detail->type_measure;
            $prod_SatUnityCode = $detail->sat_unity_code;
            $tasa = 0.160000;
            if ($detail->sat_code == '' || $detail->sat_code == NULL) {
                $valid_info = false;
            }

            
            $impuesto_precio =round(bcmul($prod_price, $tasa, 10), 2);
            $precio_sin_iva = $prod_price - $impuesto_precio;

            $impuesto_importe = round(bcmul($prodImporte, $tasa, 10), 2);
            $importe_sin_iva = $prodImporte - $impuesto_importe;

            $subtotal += $importe_sin_iva;
            //$impuesto_importe  = $prodImporte * $tasa;
            

            $total += round($importe_sin_iva + $impuesto_importe, 2);

            $conceptos[] = [
                'Cantidad' => $prod_quantity,
                'CodigoUnidad' => $prod_SatUnityCode,
                'Unidad' => $prodSatUnity,
                'CodigoProducto' => $prod_sat_code,
                'Producto' => $productName,
                'PrecioUnitario' => $precio_sin_iva,
                'Importe' => $importe_sin_iva,
                'ObjetoDeImpuesto' => "02",
                'Impuestos' => [
                    [
                        'TipoImpuesto' => "1",
                        'Impuesto' => "2",
                        'Factor' => "1",
                        'Base' => $prodImporte,
                        'Tasa' => "0.160000",
                        'ImpuestoImporte' => $impuesto_importe,
                    ]
                ]
            ];

        }
    } else {
    }



    $serie = generateUniqueString();
    $folio = generateUniqueNumericString();
    if ($valid_info) {
        $data = array(
            'response' => true,
            'concepts' => $conceptos,
            'subtotal' => number_format($subtotal, 2),
            'total' => number_format($total, 2),
            'order_code' => $order_code,
            'pay_sat' => $pay_sat,
            'payment_method_description' => $payment_method_description,
            'serie' => $serie,
            'folio' => $folio,
            'id_order' => $id_order,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => "La información necesaria de los productos no está completa para poder realizar la facturación. Por favor veirifique las propiedades de los productos de la orden e intente nuevamente"
        );
    }


    echo json_encode($data);
}

function sendMailFactura()
{
    $queries = new Queries;

    $id_order = $_POST['id_order'];


    $pdf = $_POST['stringPDF'];
    $xml = $_POST['stringXML'];
    $CFDI = mb_strtoupper($_POST['CFDI']);
    $order_code = mb_strtoupper($_POST['order_code']);
    $email_receptor = $_POST['email_receptor'];

    $stmt = "UPDATE u803991314_main.orders 
    SET
    factura_generada = 1,
    CFDI = '$CFDI'
    WHERE id_orders = $id_order
    ";
    $queries->InsertData($stmt);

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function


    //Load Composer's autoloader
    require dirname(__DIR__ . '', 4) . '/vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'eshop.grupo.salazar@gruposalazar.com.mx';                     //SMTP username
        $mail->Password   = 'E8%V7w5#ke';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('eshop.grupo.salazar@gruposalazar.com.mx', utf8_decode('GRUPO SALAZAR'));
        $mail->addAddress($email_receptor, '');     //Add a recipient
        //$mail->addAddress("antoniogonzalez.rt@gmail.com", '');
        //$mail->addAddress("vanisalazar18@gmail.com", '');
        
        //$mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo('eshop.grupo.salazar@gruposalazar.com.mx', utf8_decode('GRUPO SALAZAR'));
        //$mail->addCC('soporte@gruposalazar.com.mx');
        $mail->addBCC('facturas@gruposalazar.com.mx');

        //Attachments
        $pdfDecoded = base64_decode($pdf);
        $mail->addStringAttachment($pdfDecoded, "FACTURA COMPRA $order_code  $CFDI" . ".pdf", 'base64', 'application/pdf');
        $mail->addStringAttachment($xml, "$CFDI.xml", '8bit', 'application/xml');

        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        $mail->SMTPDebug = false;
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = utf8_decode('Factura de tu compra ' . $order_code);
        $mail->Body    = getHTMLMailFactura();


        $mail->send();



        $data = array(
            'response' => true,
            'message' => 'Su órden ha sido registrada, y se encuentra en proceso de validación'
        );
    } catch (Exception $e) {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al envíar el correo de confirmación'
        );
    }
    echo json_encode($data);
}

function generateUniqueString()
{
    // Obtén el timestamp actual
    $timestamp = microtime(true);  // Timestamp en microsegundos

    // Genera un hash a partir del timestamp
    $hash = md5($timestamp);

    // Convierte los primeros 4 caracteres del hash en un número (hexadecimal a decimal)
    $num = hexdec(substr($hash, 0, 4));

    // Convierte el número en una cadena base 26 usando letras (a-z)
    $letters = '';
    while ($num > 0) {
        $remainder = $num % 26;
        $letters = chr(97 + $remainder) . $letters;
        $num = intdiv($num, 26);
    }

    // Asegúrate de que la cadena sea de 2 letras (rellena con 'a' si es necesario)
    return str_pad($letters, 2, 'a', STR_PAD_LEFT);
}

function generateUniqueNumericString()
{
    // Obtén el timestamp actual en microsegundos
    $timestamp = microtime(true);  // Timestamp en microsegundos

    // Genera un hash a partir del timestamp
    $hash = md5($timestamp);

    // Convierte los primeros 4 caracteres del hash en un número (hexadecimal a decimal)
    $num = hexdec(substr($hash, 0, 4));

    // Toma los últimos 3 dígitos del número para asegurarse de que sea de 3 caracteres
    $numericString = substr($num, -3);

    // Asegúrate de que la cadena sea de 3 dígitos (rellena con ceros a la izquierda si es necesario)
    return str_pad($numericString, 3, '0', STR_PAD_LEFT);
}

function getOnlineSaleDetail()
{

    $queries = new Queries;

    $id_order = $_POST['id_sale'];

    $sqlOrderIndex = "SELECT 
    CONCAT(cl.name, ' ', cl.lastname) AS client_name,
    CASE
    WHEN colab.colaborator_code IS NULL THEN 'N/A'
    ELSE CONCAT(colab.name, ' ', colab.lastname)
    END
    AS colab_reg,
    id_orders,
    ord.ammount,
    ord.shipping_address,
    ord.order_mail,
    ord.order_phone,
    ord.shipping_notes,
    ord.order_code,
    ord.shipping_name_client,
    stty.admin_status_description,
    payment_method_description,
    DATE(order_date) AS order_date,
    subs.subsidiary_name
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    INNER JOIN u803991314_main.payment_methods AS pym ON ord.id_payment_methods = pym.id_payment_methods
    INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
    LEFT JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = ord.id_user_registered
    INNER JOIN u803991314_main.clients AS cl ON ord.id_clients = cl.id_clients
    WHERE id_orders = $id_order";
    $saleinfo = $queries->getData($sqlOrderIndex);

    $html = '';
    if (!empty($saleinfo)) {
        $html .= '<h4> Código de Compra: ' . $saleinfo[0]->order_code . '</h4>';
        $html .= '<h4> Total: $ ' . round($saleinfo[0]->ammount, 2) . '</h4>';
        $html .= '<h4> Método de pago: ' . ($saleinfo[0]->payment_method_description) . '</h4>';
        $html .= '<h4> Nombre de Cliente: ' . ($saleinfo[0]->shipping_name_client) . '</h4>';
        $html .= '<h4> Teléfono de Cliente: ' . ($saleinfo[0]->order_phone) . '</h4>';
        $html .= '<h4> Correo de Cliente: ' . ($saleinfo[0]->order_mail) . '</h4>';
        $html .= '<h4> Dirección de entrega: ' . ($saleinfo[0]->shipping_address) . '</h4>';
        $html .= '<h4> Notas de entrega: ' . ($saleinfo[0]->shipping_notes) . '</h4>';
        $html .= '<h4> Status de órden: ' . ($saleinfo[0]->admin_status_description) . '</h4>';

        $html .= '<div class="table-responsive">';
        $html .= '    <table class="table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th class="table-dark text-white" scope="col" colspan="4">Compra #' . $id_order . ' | Total de compra: $ ' . round($saleinfo[0]->ammount, 2) . ' MXN  | Fecha: ' . $saleinfo[0]->order_date . '</th>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>PRODUCTO</th>';
        $html .= '<th>CANTIDAD</th>';
        $html .= '<th>PRECIO UNITARIO</th>';
        $html .= '<th>SUBTOTAL</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';



        $sqlOrderDetail = "SELECT prd.product_name, ord_det.*
        FROM u803991314_main.order_details AS ord_det
        INNER JOIN u803991314_main.products AS prd ON ord_det.id_prducts = prd.id_prducts
        WHERE id_orders = $id_order";
        $saleDetail = $queries->getData($sqlOrderDetail);

        if (!empty($saleDetail)) {
            foreach ($saleDetail  as $detail) {
                $html .= '<tr>';
                $html .= '<td>' . $detail->product_name . '</td>';
                $html .= '<td>' . $detail->quantity . '</td>';
                $html .= '<td> $ ' . round($detail->price, 2) . ' MXN</td>';
                $html .= '<td> $ ' . round(($detail->price * $detail->quantity), 2) . ' MXN</td>';
                $html .= '</tr>';
            }
        }
        $html .= '<tfoot>';
        $html .= '<tr>';
        $html .= '<th colspan="3"><strong>TOTAL:</strong></th>';
        $html .= '<th colspan="1"><strong>$ ' . round($saleinfo[0]->ammount, 2) . ' MXN</strong></th>';
        $html .= '</tr>';
        $html .= '</tfoot>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .=
            '</div>';

        $data = array(
            'response' => true,
            'html' => $html,
        );
    } else {

        $html .= '
                    </tbody>
                </table>';
        $data = array(
            'response' => false,
            'html' => $html
        );
    }



    echo json_encode($data);
}
function getSalesStatus()
{

    $queries = new Queries;

    $saleinfo = array();
    $sqlOrderIndex = "SELECT 
    admin_status_description,
    COUNT(*) AS status_quantity
    FROM u803991314_main.orders AS ord
    INNER JOIN u803991314_main.orders_status_types AS stty ON ord.id_orders_status_types = stty.id_orders_status_types
    GROUP BY admin_status_description
    ";
    $saleinfo = $queries->getData($sqlOrderIndex);

    $html = '';
    if (!empty($saleinfo)) {
        $data = array(
            'response' => true,
            'data' => $saleinfo,
        );
    } else {

        $data = array(
            'response' => false,
            'data' => $saleinfo
        );
    }



    echo json_encode($data);
}
function getSalesMonth()
{

    $queries = new Queries;

    $saleinfo = array();
    $sqlOrderIndex = "SELECT SUM(quantity*ord_det.price) as ammount_prod, MONTH(orders.order_date) AS month_sale
    FROM u803991314_main.order_details AS ord_det
    INNER JOIN u803991314_main.orders AS orders ON orders.id_orders = ord_det.id_orders
    GROUP BY MONTH(orders.order_date)
    ORDER BY MONTH(orders.order_date) ASC
    LIMIT 12
    ";
    $saleinfo = $queries->getData($sqlOrderIndex);

    $html = '';
    if (!empty($saleinfo)) {
        $data = array(
            'response' => true,
            'data' => $saleinfo,
        );
    } else {

        $data = array(
            'response' => false,
            'data' => $saleinfo
        );
    }



    echo json_encode($data);
}

function printTicket()
{

    $queries = new Queries;

    $id_order = $_POST['id_order'];




    $sql = "SELECT ords.*, colabs.short_name, sbs.subsidiary_name, sbs.id_subsidiary
        FROM u803991314_main.orders AS ords
        INNER JOIN u803991314_main.subsidiary AS sbs ON ords.id_subsidiary = sbs.id_subsidiary
        LEFT JOIN u803991314_main.colaborators AS colabs ON ords.id_user_registered = colabs.id_colaborator
        WHERE id_orders = $id_order ";
    $getInfoOrder = $queries->getData($sql);



    $prodsOrder = array();
    if (!empty($getInfoOrder)) {
        $id_subs = $getInfoOrder[0]->id_subsidiary;
        $sqlGetSubsidiaryInfo = "SELECT sub_add.*,
        sub.subsidiary_name,
        sub.subsidiary_phone,
        sub.subsidiary_second_phone
        FROM u803991314_main.subsidiary AS sub
        INNER JOIN u803991314_main.subsidiary_address AS sub_add ON sub_add.id_subsidiary_address = sub.id_subsidiary_address
        WHERE sub.id_subsidiary = $id_subs";
        $getSubsidiaryInfo = $queries->getData($sqlGetSubsidiaryInfo);

        $sqlOrderDetail = "SELECT prd.product_name, ord_det.*, (ord_det.price*ord_det.quantity) AS prod_import
        FROM u803991314_main.order_details AS ord_det
        INNER JOIN u803991314_main.products AS prd ON ord_det.id_prducts = prd.id_prducts
        WHERE id_orders = $id_order";
        $saleDetail = $queries->getData($sqlOrderDetail);

        $data = array(
            'response' => true,
            'orderDetails' => $getInfoOrder,
            'prodsOrder' => $saleDetail,
            'getSubsidiaryInfo' => $getSubsidiaryInfo
        );
    } else {
        $data = array(
            'response' => false,
            'message' => "Producto no encontrado",
        );
    }



    echo json_encode($data);
}
function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
