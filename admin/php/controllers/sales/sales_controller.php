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
    $_id_subsidiary = $_POST['id_subsidiary'];



    $sql = "SELECT  
        prods.*, stk.stock AS sub_stock
        FROM u803991314_main.products AS prods
        LEFT JOIN u803991314_main.subsidiary_stocks AS stk ON stk.prducts_id_prducts = prods.id_prducts AND stk.id_subsidiary = $_id_subsidiary
        WHERE product_barcode = '$product' ";
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
            NOW(),
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
            NOW(),
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
            NOW()
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
                                        NOW()
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
            'message' => "Error al guardar orden",
        );
    }



    echo json_encode($data);
}
function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
