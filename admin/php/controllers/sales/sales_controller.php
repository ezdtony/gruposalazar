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
            </div></td>
            <td class=""> <div class="fw-bold">
            <button type="button"class="btn btn-secondary saleReady" data-id-order="' . $product->id_orders . '"
            data-bs-toggle="modal" data-bs-target="#modalSaleDetail"><i class="fa-solid fa-check"></i></button>
            </div></td>
            <td class=""> <div class="fw-bold">
            <button type="button"class="btn btn-success saleDelivered" data-id-order="' . $product->id_orders . '"
            data-bs-toggle="modal" data-bs-target="#modalSaleDetail"><i class="fa-solid fa-check"></i></button>
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

    $saleinfo= array();
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

    $saleinfo= array();
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
function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
