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
            $html .= '<li class="list-group-item itemProductSearch" data-max-stock="' . $product->ideal_stock . '" data-id-product="' . $product->id_prducts . '" data-id-income-order="" data-product-price-sell="' . $product->price . '" data-product-price-buy="' . $product->purchase_price . '"   data-product-brand="' . $product->brand . '"  data-product-code="' . $product->product_code . '" data-product-name="' . $product->product_name . '" >' . $product->product_name . '</li>';
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
function getOrderDetails()
{

    $queries = new Queries;

    $id_order = $_POST['id_order'];
    $sqlInsertProductsIncome = "SELECT prod.*, br.brand, icd.*
    FROM u803991314_main.income_orders AS ico
    INNER JOIN u803991314_main.income_details AS icd ON ico.id_income_orders = icd.id_income_orders
    INNER JOIN u803991314_main.products AS prod ON icd.id_prducts = prod.id_prducts
    LEFT JOIN u803991314_main.brands AS br ON br.id_brands = prod.id_brands
    WHERE ico.id_income_orders = $id_order
    ";

    $getData = $queries->getData($sqlInsertProductsIncome);
    $total_order = 0;
    $total_items = 0;
    $html = '';
    if (!empty($getData)) {

        foreach ($getData as $detail) {
            $total_order= $total_order+$detail->purchase_price;
            $total_items++;
            $html .= '<tr>';
            $html .= '<td>' . $detail->quantity . '</td>';
            $html .= '<td>' . $detail->product_code . '</td>';
            $html .= '<td>' . $detail->product_name . '</td>';
            $html .= '<td>' . $detail->brand . '</td>';
            $html .= '<td>' . $detail->purchase_price . '</td>';
            $html .= '<td>' . $detail->price . '</td>';


            $html .= '</tr>';
        }
        $total_order = round($total_order, 2);
        $data = array(
            'response' => true,
            'html' => $html,
            'total_order' => $total_order,
            'total_items' => $total_items
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al obtener detalles la orden'
        );
    }



    echo json_encode($data);
}
function getIncomeTable()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    //$product_name = $_POST['product_name'];
    $colsSearch = [
        'date_income',
        "CONCAT(colab.name, ' ', colab.lastname)",
        'subsidiary_name',
        'stat.status_description'
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
        $where .= ")";
    }


    if ($limit > 0) {
        $limit = " LIMIT $begin,  $limit";
    } else {
        $limit = "";
    }
    //echo $limit;
    $html = "";





    $sql = "SELECT SQL_CALC_FOUND_ROWS
   CONCAT(colab.name, ' ', colab.lastname) AS colaborator_name, status_description, subsidiary_name,
   bootstrap_class,
    inco.*
    FROM u803991314_main.income_orders AS inco
    INNER JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = inco.id_colaborator
    INNER JOIN u803991314_main.income_status AS stat ON stat.id_income_status = inco.id_income_status
    INNER JOIN u803991314_main.subsidiary AS subs ON subs.id_subsidiary = inco.id_subsidiary
    $where 
    
    $limit
    ";
    //echo $sql;
    //GROUP BY inco.id_income_orders
    $getProducts = $queries->getData($sql);

    $total_stock = 0;

    if (!empty($getProducts)) {
        $totalResults = count($getProducts);

        $sqlAllProdsFiltered = "SELECT FOUND_ROWS() AS founded";
        $getTotalProductsFiltered = $queries->getData($sqlAllProdsFiltered);
        if (!empty($getTotalProductsFiltered)) {
            $totalFiltered = ($getTotalProductsFiltered[0]->founded);
        }

        $sqlAllProds = "SELECT COUNT(id_income_orders) AS founded
    FROM u803991314_main.income_orders AS inco
    INNER JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = inco.id_colaborator
    INNER JOIN u803991314_main.income_status AS stat ON stat.id_income_status = inco.id_income_status
    INNER JOIN u803991314_main.subsidiary AS subs ON subs.id_subsidiary = inco.id_subsidiary";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }

        foreach ($getProducts as $product) {


            $html .= '
            <tr id="trIncomeOrder' . $product->id_income_orders . '">
            <td class="name fw-bold" id="tdsubsidiary_name' . $product->id_income_orders . '">
                 ' . $product->subsidiary_name . '
            </td>
            <td class="name fw-bold" id="tddate_income' . $product->id_income_orders . '">
                 ' . $product->date_income . '
            </td>
            <td class="name fw-bold" id="tdcolaborator_name' . $product->id_income_orders . '">
                 ' . $product->colaborator_name . '
            </td>
            <td class="name fw-bold" id="tdstatus_description' . $product->id_income_orders . '">
            <ul class="list-inline"><li class="list-inline-item d-inline-flex align-items-center">
            <span class="legend-circle bg-' . $product->status_description . '"></span>  ' . $product->status_description . '
        </li>
        </ul>
            </td>
            <td class="fw-bold text-center">
                <div class="dropdown">
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
                    <a href="javascript: void(0);" data-id-income-order="' . $product->id_income_orders . '" class="dropdown-item showBreakdownOrder" data-bs-toggle="modal" data-bs-target="#modalshowBreakdownOrder">
                            Ver productos
                        </a>
                    <a href="javascript: void(0);" data-id-income-order="' . $product->id_income_orders . '" class="dropdown-item showBreakdownOrder" data-bs-toggle="modal" data-bs-target="#modalshowBreakdownOrder">
                            Editar
                        </a>
                        <a href="javascript: void(0);" class="dropdown-item deleteOrder" data-id-income-order="' . $product->id_income_orders . '" style="color:red !important;">
                            Eliminar
                        </a>
                    </div>
                </div>
            </td>
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

function getOrders()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    //$order_name = $_POST['product_name'];
    $colsSearch = [
        'subs.subsidiary_name',
        'date_register',
        'name',
        'lastname',
        "CONCAT(subs.subsidiary_prefix, '-00',incor.id_income_orders)",
        'status_description'
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
        $where .= ")";
    }


    if ($limit > 0) {
        $limit = " LIMIT $begin,  $limit";
    } else {
        $limit = "";
    }
    //echo $limit;
    $html = "";





    $sql = "SELECT SQL_CALC_FOUND_ROWS
    subs.subsidiary_name,
    CONCAT(subs.subsidiary_prefix, '-00',incor.id_income_orders) AS order_code,
    CONCAT(name, ' ',lastname) AS username,
    stat.status_description, bootstrap_class,
    incor.*
    FROM u803991314_main.income_orders AS incor
    INNER JOIN u803991314_main.subsidiary AS subs ON subs.id_subsidiary = incor.id_subsidiary
    INNER JOIN u803991314_main.income_status AS stat ON stat.id_income_status = incor.id_income_status
    INNER JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = incor.id_colaborator
    $where 
    ORDER BY date_register DESC
    $limit
    
    ";


    $getOrder = $queries->getData($sql);

    $total_stock = 0;

    if (!empty($getOrder)) {
        $totalResults = count($getOrder);

        $sqlAllProdsFiltered = "SELECT FOUND_ROWS() AS founded";
        $getTotalProductsFiltered = $queries->getData($sqlAllProdsFiltered);
        if (!empty($getTotalProductsFiltered)) {
            $totalFiltered = ($getTotalProductsFiltered[0]->founded);
        }

        $sqlAllProds = "SELECT COUNT(id_income_orders) AS founded
            FROM u803991314_main.income_orders AS incor
            INNER JOIN u803991314_main.subsidiary AS subs ON subs.id_subsidiary = incor.id_subsidiary
            INNER JOIN u803991314_main.income_status AS stat ON stat.id_income_status = incor.id_income_status
            INNER JOIN u803991314_main.colaborators AS colab ON colab.id_colaborator = incor.id_colaborator";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }


        foreach ($getOrder as $order) {



            $html .= '
            <tr id="trOrder' . $order->id_income_orders . '">
            <td class="name fw-bold" id="td_order_code_Id' . $order->id_income_orders . '">
                 ' . $order->order_code . '
            </td>
            <td class="name fw-bold" id="td_subsidiary_Id' . $order->id_income_orders . '">
                 ' . $order->subsidiary_name . '
            </td>
            <td class="name fw-bold" id="td_date_register_Id' . $order->id_income_orders . '">
                 ' . $order->date_register . '
            </td>
            <td class="name fw-bold" id="td_username_Id' . $order->id_income_orders . '">
            ' . $order->username . '
       </td>
            <td class="name fw-bold" id="td_status_description_Id' . $order->id_income_orders . '">
            <span class="legend-circle bg-' . $order->bootstrap_class . '"></span> ' . $order->status_description . '
            </td>
            </td>
            <td class="name fw-bold">
            <button type="button" data-bs-toggle="modal" data-bs-target="#orderIncomeDetailModal" data-id-order-income="' . $order->id_income_orders . '" class="btn btn-primary orderDetails"><i class="fa-solid fa-list-ol"></i></button>
            </td>
            
            <td class="fw-bold text-center">
                <div class="dropdown">
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
                        <a href="javascript: void(0);" data-id-income-orders="' . $order->id_income_orders . '" class="dropdown-item editProduct" data-bs-toggle="modal" data-bs-target="#modalEditArticle">
                            Editar
                        </a>
                        <!--  <a href="javascript: void(0);" class="dropdown-item">
                            Editar stock en sucursales
                        </a> -->
                        <a href="javascript: void(0);" class="dropdown-item deleteProduct" data-id-income-orders="' . $order->id_income_orders . '" style="color:red !important;">
                            Eliminar
                        </a>
                    </div>
                </div>
            </td>
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
