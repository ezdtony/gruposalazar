<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once dirname(__DIR__ . '', 4) . '/vendor/phpmailer/phpmailer/src/Exception.php';
include_once dirname(__DIR__ . '', 4) . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
include_once dirname(__DIR__ . '', 4) . '/vendor/phpmailer/phpmailer/src/SMTP.php';

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}


function getProductsTable()
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
        $where .= ") AND active_item = 1 ";
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
        WHEN sb_stk.prducts_id_prducts = prods.id_prducts THEN SUM(sb_stk.stock)
        ELSE 0
    END
    AS total_stock, brand,
    prods.*
    FROM u803991314_main.products AS prods
    INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
    LEFT JOIN u803991314_main.subsidiary_stocks AS sb_stk ON sb_stk.prducts_id_prducts = prods.id_prducts
    $where 
    GROUP BY prods.id_prducts
    
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

        $sqlAllProds = "SELECT COUNT(id_prducts) AS founded
         FROM u803991314_main.products AS prods
        INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
        LEFT JOIN u803991314_main.subsidiary_stocks AS sb_stk ON sb_stk.prducts_id_prducts = prods.id_prducts";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }


        foreach ($getProducts as $product) {

            $percentage = 0;
            if ($product->total_stock > 0) {
                $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
            }

            $html .= '
            <tr id="trProduct' . $product->id_prducts . '">
            <td class="name fw-bold" id="tdproduct_short_nameId' . $product->id_prducts . '">
                 ' . $product->product_short_name . ' <!-- /  ' . $product->product_code . ' -->
            </td>
            <td class="name fw-bold" id="tdproduct_barcodeId' . $product->id_prducts . '">
                 ' . $product->product_barcode . '
            </td>
            <td class="name fw-bold" id="tdthumbnailId' . $product->id_prducts . '">

                <div class="avatar avatar-sm" style="margin-right:10px">
                    <div class="images">
                        <img src="' . $product->thumbnail . '" alt="" width="50px">
                    </div>
                </div>
            </td>
            <td class="name fw-bold" id="tdproduct_nameId' . $product->id_prducts . '">
                 ' . $product->product_name . '
            </td>
            <td class="name fw-bold" id="tdbrandId' . $product->id_prducts . '">
                 ' . $product->brand . '
            </td>
            <td class="price text-end" id="tdpurchase_priceId' . $product->id_prducts . '">
                $ ' . number_format($product->purchase_price, 2, ' . ') . '
            </td>
            <td class="price text-end" id="tdpriceId ' . $product->id_prducts . '">
                $ ' . number_format($product->price, 2, ' . ') . '
            </td>
            <!--  <td class="quantity text-end">
                 ' . $product->stock . '
            </td> -->
            <td class="barcode fw-bold text-center">
                <button type="button" data-barcode="' . $product->product_barcode . '" id="btnBarcode ' . $product->id_prducts . '" class="btn btn-secondary btnGenerateBarcode" data-bs-toggle="modal" data-bs-target="#viewBarcode">
                    <i class="fa-solid fa-barcode"></i>
                </button>
            </td>
            <td class="sales">
                <div class="d-flex justify-content-between align-items-center text-center">
                    <div class="progress d-flex flex-grow-1">
                        <div class="progress-bar" id="ProgressProd' . $product->id_prducts . '" role="progressbar" style="width:  $percentage%" aria-valuenow="' . $product->total_stock . '" aria-valuemin="0" aria-valuemax=" ' . $product->ideal_stock . '"></div>
                    </div>
                    <span id="txtPercentage' . $product->id_prducts . '" class="ms-3 text-muted"> ' . $percentage . '%</span>
                </div>
                <button title="Ver stock en sucursales" data-bs-toggle="modal" data-bs-target="#subsidiaryStocks" data-product-name=" ' . $product->product_short_name . ' /  ' . $product->product_code . ' |  ' . $product->product_name . '" data-id-product=" ' . $product->id_prducts . '" type="button" class="btn btn-info btn-sm btnSeeStockSubsidiary"><i class="fa-solid fa-cubes"></i></button>
            </td>
            <td class="fw-bold text-center">
            
            <button class="btn btn-primary btn-sm prodAddTags" data-id-product="' . $product->id_prducts . '" data-bs-toggle="modal" data-bs-target="#addTags" title="Etiquetas" style="display:inline-block !important"><i class="fa-solid fa-tags"></i></button>
                <div class="dropdown" style="display:inline-block !important">
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
                    
                        <a href="javascript: void(0);" data-id-product="' . $product->id_prducts . '" class="dropdown-item editProduct" data-bs-toggle="modal" data-bs-target="#modalEditArticle">
                            Editar
                        </a>
                        <!--  <a href="javascript: void(0);" class="dropdown-item">
                            Editar stock en sucursales
                        </a> -->
                        <a href="javascript: void(0);" class="dropdown-item deleteProduct" data-id-product="' . $product->id_prducts . '" style="color:red !important;">
                            Borrar
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
function getProductsShop()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    //$product_name = $_POST['product_name'];
    $colsSearch = [
        'br.brand',
        'product_name',
        'product_short_name',
        'product_code',
        'product_barcode',
        'ct.categories_description',
        'sku'
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
        $where .= ") AND active_item = 1 ";
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
        WHEN sb_stk.prducts_id_prducts = prods.id_prducts THEN SUM(sb_stk.stock)
        ELSE 0
    END
    AS total_stock, brand,
    prods.*
    FROM u803991314_main.products AS prods
    INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
    LEFT JOIN u803991314_main.subsidiary_stocks AS sb_stk ON sb_stk.prducts_id_prducts = prods.id_prducts
    INNER JOIN u803991314_main.relationship_products_categories AS rpc ON rpc.id_prducts = prods.id_prducts
    INNER JOIN u803991314_main.categories AS ct ON ct.id_categories = rpc.id_categories
    $where 
    GROUP BY prods.id_prducts
    
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

        $sqlAllProds = "SELECT COUNT(id_prducts) AS founded
         FROM u803991314_main.products AS prods
        INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
        LEFT JOIN u803991314_main.subsidiary_stocks AS sb_stk ON sb_stk.prducts_id_prducts = prods.id_prducts";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }


        foreach ($getProducts as $product) {
            $total_stock = $product->total_stock;
            $percentage = 0;
            if ($product->total_stock > 0) {
                $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
            }

            if ($product->thumbnail == 'NULL' || $product->thumbnail == '') {
                $image_prod = 'images/sin-imagen.png';
            } else {
                $archive_route = str_replace('..', 'admin', $product->thumbnail);
                $image_prod = $archive_route;
                $file_exs = dirname(__DIR__ . '', 3) . str_replace('..', '', $product->thumbnail);
                /* echo $file_exs; */

                if (file_exists($file_exs)) {
                    $image_prod = $archive_route;
                } else {
                    $image_prod = 'images/sin-imagen.png';
                }
            }
            $enabled = "";
            $html_stock = '<p class="text-muted">Disponible en stock: ' . $total_stock . '</p>';
            if ($total_stock <= 0) {
                $enabled = "disabled";
                $html_stock = '<p class="text-muted" style="color:red !important">Sin stock disponible</p>';
            }

            $html .= '
            <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <a class="product-item">
                        <img src="' . $image_prod . '" class="img-fluid product-thumbnail">
                        <h3 class="product-title">' . $product->product_name . '</h3>
                        '.$html_stock.'
                        <strong class="product-price">$' . round($product->price, 2) . '</strong>

                        <button '.$enabled.' class="icon-cross addCartProd"  data-id-product="' . $product->id_prducts . '" data-product-price="' . round($product->price, 2) . '" data-stock="' . $total_stock . '">
                            <img src="images/cross.svg" class="img-fluid">
                        </button>
                    </a>
                </div>';
        }
        $html .= '<button type="button" class="btn btn-primary loadMore">Cargar más productos</button>';

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


function getProductsCart()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    $cart_shop = $_POST['cart_shop'];


    $html = "";


    $totalSale = 0;
    $cart_index = 0;
    foreach ($cart_shop as $cart) {
        $id_product = $cart['id_product'];
        $quantity = $cart['quantity'];

        $sql = "SELECT
                CASE 
                    WHEN sb_stk.prducts_id_prducts = prods.id_prducts THEN SUM(sb_stk.stock)
                    ELSE 0
                END
                AS total_stock, brand,
                prods.*
                FROM u803991314_main.products AS prods
                INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
                LEFT JOIN u803991314_main.subsidiary_stocks AS sb_stk ON sb_stk.prducts_id_prducts = prods.id_prducts
                INNER JOIN u803991314_main.relationship_products_categories AS rpc ON rpc.id_prducts = prods.id_prducts
                INNER JOIN u803991314_main.categories AS ct ON ct.id_categories = rpc.id_categories
                WHERE prods.id_prducts = $id_product
    ";
        $getProducts = $queries->getData($sql);



        $percentage = 0;
        foreach ($getProducts as $product) {

            if ($product->total_stock > 0) {
                $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
            }

            if ($product->thumbnail == 'NULL' || $product->thumbnail == '') {
                $image_prod = 'images/sin-imagen.png';
            } else {
                $archive_route = str_replace('..', 'admin', $product->thumbnail);
                $image_prod = $archive_route;
                $file_exs = dirname(__DIR__ . '', 3) . str_replace('..', '', $product->thumbnail);
                /* echo $file_exs; */

                if (file_exists($file_exs)) {
                    $image_prod = $archive_route;
                } else {
                    $image_prod = 'images/sin-imagen.png';
                }
            }
            $total_prod = $quantity * round($product->price, 2);
            $totalSale = $totalSale + $total_prod;
            $html .= '
            <tr>
            <td class="product-thumbnail">
                <img src="' . $image_prod . '" alt="Imagen" class="img-fluid">
            </td>
            <td class="product-name">
                <h2 class="h5 text-black">' . $product->product_name . '</h2>
            </td>
            <td>$' . round($product->price, 2) . '</td>
            <td>
                <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-black decrease"  type="button">&minus;</button>
                    </div>
                    <input type="text" class="form-control text-center quantity-amount" data-price="' . round($product->price, 2) . '" value="' . $quantity . '" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                    <div class="input-group-append">
                        <button class="btn btn-outline-black increase" type="button">&plus;</button>
                    </div>
                </div>

            </td>
            <td class="total-prod" data-id-product="' . $id_product . '" data-cart-index="'.$cart_index.'" data-product-quantity="' . $quantity . '"  data-price="' . round($product->price, 2) . '"  data-total-prod="' . $total_prod . '">$' . $total_prod . '</td>
            <td><a class="btn btn-black btn-sm removeCartPRod" data-id-product="' . $product->id_prducts . '" >X</a></td>
        </tr>
            ';
            $cart_index++;
        }
    }





    $total_stock = 0;

    if (!empty($getProducts)) {

        $totalSale = round($totalSale, 2);
        $data = array(
            'response' => true,
            'html' => $html,
            'totalSale' => $totalSale,
        );

        /* $data = array(
            'response' => true,
            'html' => $html,
            'totalProds' => $totalProds,
            'totalResults' => $totalResults,
            'totalFiltered' => $totalFiltered,
            'totalPages' => $totalPages,
            'paginationNav' => $pagination
        ); */
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
function getProductsCheckout()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    $cart_shop = $_POST['cart_shop'];


    $html = "";


    $totalSale = 0;
    foreach ($cart_shop as $cart) {
        $id_product = $cart['id_product'];
        $quantity = $cart['quantity'];

        $sql = "SELECT
                CASE 
                    WHEN sb_stk.prducts_id_prducts = prods.id_prducts THEN SUM(sb_stk.stock)
                    ELSE 0
                END
                AS total_stock, brand,
                prods.*
                FROM u803991314_main.products AS prods
                INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
                LEFT JOIN u803991314_main.subsidiary_stocks AS sb_stk ON sb_stk.prducts_id_prducts = prods.id_prducts
                INNER JOIN u803991314_main.relationship_products_categories AS rpc ON rpc.id_prducts = prods.id_prducts
                INNER JOIN u803991314_main.categories AS ct ON ct.id_categories = rpc.id_categories
                WHERE prods.id_prducts = $id_product
    ";
        $getProducts = $queries->getData($sql);



        $percentage = 0;
        foreach ($getProducts as $product) {

            if ($product->total_stock > 0) {
                $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
            }

            if ($product->thumbnail == 'NULL' || $product->thumbnail == '') {
                $image_prod = 'images/sin-imagen.png';
            } else {
                $archive_route = str_replace('..', 'admin', $product->thumbnail);
                $image_prod = $archive_route;
                $file_exs = dirname(__DIR__ . '', 3) . str_replace('..', '', $product->thumbnail);
                /* echo $file_exs; */

                if (file_exists($file_exs)) {
                    $image_prod = $archive_route;
                } else {
                    $image_prod = 'images/sin-imagen.png';
                }
            }
            $total_prod = $quantity * round($product->price, 2);
            $totalSale = $totalSale + $total_prod;
            $html .= '
                        <tr>
                            <td>' . $product->product_name . ' <strong class="mx-2">x</strong> ' . $quantity . '</td>
                            <td>$' . $total_prod . '</td>
                        </tr>
                        ';
        }
    }





    $total_stock = 0;

    if (!empty($getProducts)) {

        $totalSale = round($totalSale, 2);
        $data = array(
            'response' => true,
            'html' => $html,
            'totalSale' => $totalSale,
        );

        /* $data = array(
            'response' => true,
            'html' => $html,
            'totalProds' => $totalProds,
            'totalResults' => $totalResults,
            'totalFiltered' => $totalFiltered,
            'totalPages' => $totalPages,
            'paginationNav' => $pagination
        ); */
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

function saveClientOrderHomeDelivery()
{

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    $cart_shop = $_POST['cart_shop'];
    $total_sale = $_POST['total_sale'];
    $order_code = 'TLSLZR-' . mb_strtoupper($_POST['id_order']);

    $client_name = $_POST['client_name'];
    $client_lastname = $_POST['client_lastname'];
    $name_client = $client_name . ' ' . $client_lastname;
    $client_address = $_POST['client_address'];
    $client_colony = $_POST['client_colony'];
    $client_zipcode = $_POST['client_zipcode'];
    $client_state = $_POST['client_state'];
    $client_city = $_POST['client_city'];
    $client_email = $_POST['client_email'];
    $client_phone = $_POST['client_phone'];
    $order_notes = $_POST['order_notes'];
    $id_payment_methods = 2;
    $id_subsidiary = 1;

    $sqlGetSubsidiaryInfo = "SELECT sub_add.*,
    sub.subsidiary_name,
    sub.subsidiary_phone,
    sub.subsidiary_second_phone
    FROM u803991314_main.subsidiary AS sub
    INNER JOIN u803991314_main.subsidiary_address AS sub_add ON sub_add.id_subsidiary_address = sub.id_subsidiary_address
    WHERE sub.id_subsidiary = 1";
    $getSubsidiaryInfo = $queries->getData($sqlGetSubsidiaryInfo);

    $subsidiary_phone = $getSubsidiaryInfo[0]->subsidiary_phone;


    $client_address_complete = $client_address . ', ' . $client_colony . ', C.P. ' . $client_zipcode . ', ' . $client_city . ', ' . $client_state;

    $today = date('Y-m-d H:i:s');

    $sql = "INSERT INTO u803991314_main.orders (
    id_clients,
    id_orders_status_types,
    id_offers,
    id_payment_methods,
    id_subsidiary,
    order_code,
    pikup_subsidiary,
    ammount,
    shipping_name_client,
    shipping_address,
    order_phone,
    order_mail,
    shipping_notes,
    order_date
    ) VALUES (
        2,
        2,
        1,
        $id_payment_methods,
        $id_subsidiary,
        '$order_code',
        1,
        '$total_sale',
        '$name_client',
        '$client_address_complete',
        '$client_phone',
        '$client_email',
        '$order_notes',
        '$today'
    )
";
    $saveOrderIndex = $queries->InsertData($sql);
    if (!empty($saveOrderIndex)) {
        $id_order = $saveOrderIndex['last_id'];

        $totalSale = 0;
        foreach ($cart_shop as $cart) {
            $id_product = $cart['id_product'];
            $quantity = $cart['quantity'];
            $price = $cart['price'];

            $sql = "INSERT INTO u803991314_main.order_details (
                id_orders,
                id_prducts,
                price,
                quantity
            )VALUES (
                $id_order,
                $id_product,
                '$price',
                $quantity
            )
                ";
            $queries->InsertData($sql);

            $sql = "UPDATE u803991314_main.subsidiary_stocks SET stock = stock - '$quantity' WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_product";
            $queries->InsertData($sql);
            $percentage = 0;
            /* foreach ($getProducts as $product) {

                if ($product->total_stock > 0) {
                    $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
                }

                if ($product->thumbnail == 'NULL' || $product->thumbnail == '') {
                    $image_prod = 'images/sin-imagen.png';
                } else {
                    $archive_route = str_replace('..', 'admin', $product->thumbnail);
                    $image_prod = $archive_route;
                    $file_exs = dirname(__DIR__ . '', 3) . str_replace('..', '', $product->thumbnail);
                   

                    if (file_exists($file_exs)) {
                        $image_prod = $archive_route;
                    } else {
                        $image_prod = 'images/sin-imagen.png';
                    }
                }
                $total_prod = $quantity * round($product->price, 2);
                $totalSale = $totalSale + $total_prod;
                $html .= '
                        <tr>
                            <td>' . $product->product_name . ' <strong class="mx-2">x</strong> ' . $quantity . '</td>
                            <td>$' . $total_prod . '</td>
                        </tr>
                        ';
            } */
        }
        $data = array(
            'response' => true,
            'message' => 'Su órden ha sido registrada, y se encuentra en proceso de validación',
            'order_code' => $order_code,
            'id_order' => $id_order,
            'addressShip' => $client_address_complete,
            'subsidiary_phone' => $subsidiary_phone
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al registrar la orden en nuestra base de datos'
        );
    }

    echo json_encode($data);
}

function saveClientOrderSubsidiaryDelivery()
{

    $queries = new Queries;


    //$id_product = $_POST['id_product'];
    $cart_shop = $_POST['cart_shop'];
    $total_sale = $_POST['total_sale'];
    $order_code = 'TLSLZR-' . mb_strtoupper($_POST['id_order']);

    $client_name = $_POST['client_name'];
    $client_lastname = $_POST['client_lastname'];
    $name_client = $client_name . ' ' . $client_lastname;
    $client_email = $_POST['client_email'];
    $client_phone = $_POST['client_phone'];
    $order_notes = $_POST['order_notes'];
    $id_subsidiary = $_POST['id_subsidiary'];
    $subsidiary_name = $_POST['subsidiary_name'];
    $id_payment_methods = $_POST['payment_method'];;

    $sqlGetSubsidiaryInfo = "SELECT sub_add.*,
    sub.subsidiary_name,
    sub.subsidiary_phone,
    sub.subsidiary_second_phone
    FROM u803991314_main.subsidiary AS sub
    INNER JOIN u803991314_main.subsidiary_address AS sub_add ON sub_add.id_subsidiary_address = sub.id_subsidiary_address
    WHERE sub.id_subsidiary = $id_subsidiary";
    $getSubsidiaryInfo = $queries->getData($sqlGetSubsidiaryInfo);

    $client_address = $getSubsidiaryInfo[0]->street . " " . $getSubsidiaryInfo[0]->int_number;
    $client_colony = " Col. " . $getSubsidiaryInfo[0]->colony;
    $client_zipcode = $getSubsidiaryInfo[0]->postal_code;
    $client_state = $getSubsidiaryInfo[0]->state;
    $client_city = $getSubsidiaryInfo[0]->delegation;
    $subsidiary_name = $getSubsidiaryInfo[0]->subsidiary_name;
    $subsidiary_phone = $getSubsidiaryInfo[0]->subsidiary_phone;



    $client_address_complete = $client_address . ', ' . $client_colony . ', C.P. ' . $client_zipcode . ', ' . $client_city . ', ' . $client_state;

    $today = date('Y-m-d H:i:s');

    $sql = "INSERT INTO u803991314_main.orders (
    id_clients,
    id_orders_status_types,
    id_offers,
    id_payment_methods,
    id_subsidiary,
    order_code,
    pikup_subsidiary,
    ammount,
    shipping_name_client,
    shipping_address,
    order_phone,
    order_mail,
    shipping_notes,
    order_date
    ) VALUES (
        2,
        2,
        1,
        $id_payment_methods,
        1,
        '$order_code',
        1,
        '$total_sale',
        '$name_client',
        '$client_address_complete',
        '$client_phone',
        '$client_email',
        '$order_notes',
        '$today'
    )
";
    $saveOrderIndex = $queries->InsertData($sql);
    if (!empty($saveOrderIndex)) {
        $id_order = $saveOrderIndex['last_id'];

        $totalSale = 0;
        foreach ($cart_shop as $cart) {
            $id_product = $cart['id_product'];
            $quantity = $cart['quantity'];
            $price = $cart['price'];

            $sql = "INSERT INTO u803991314_main.order_details (
                id_orders,
                id_prducts,
                price,
                quantity
            )VALUES (
                $id_order,
                $id_product,
                '$price',
                $quantity
            )
                ";
            $queries->InsertData($sql);

            $sql = "UPDATE u803991314_main.subsidiary_stocks SET stock = stock - '$quantity' WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_product";
            $queries->InsertData($sql);
            $percentage = 0;
            /* foreach ($getProducts as $product) {

                if ($product->total_stock > 0) {
                    $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
                }

                if ($product->thumbnail == 'NULL' || $product->thumbnail == '') {
                    $image_prod = 'images/sin-imagen.png';
                } else {
                    $archive_route = str_replace('..', 'admin', $product->thumbnail);
                    $image_prod = $archive_route;
                    $file_exs = dirname(__DIR__ . '', 3) . str_replace('..', '', $product->thumbnail);
                   

                    if (file_exists($file_exs)) {
                        $image_prod = $archive_route;
                    } else {
                        $image_prod = 'images/sin-imagen.png';
                    }
                }
                $total_prod = $quantity * round($product->price, 2);
                $totalSale = $totalSale + $total_prod;
                $html .= '
                        <tr>
                            <td>' . $product->product_name . ' <strong class="mx-2">x</strong> ' . $quantity . '</td>
                            <td>$' . $total_prod . '</td>
                        </tr>
                        ';
            } */
        }
        $data = array(
            'response' => true,
            'message' => 'Su órden ha sido registrada, y se encuentra en proceso de validación',
            'order_code' => $order_code,
            'id_order' => $id_order,
            'addressShip' => $client_address_complete,
            'subsidiary_name' => $subsidiary_name,
            'subsidiary_phone' => $subsidiary_phone
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al registrar la orden en nuestra base de datos'
        );
    }

    echo json_encode($data);
}

function sendMailConfirmation()
{

    $total_sale = $_POST['total_sale'];
    $client_name = $_POST['client_name'];
    $client_lastname = $_POST['client_lastname'];
    $client_address = $_POST['client_address'];
    $client_state = $_POST['client_state'];
    $client_city = $_POST['client_city'];
    $client_email = $_POST['client_email'];
    $client_phone = $_POST['client_phone'];
    $order_notes = $_POST['order_notes'];
    $cart_shop = $_POST['cart_shop'];
    $order_code = $_POST['order_code'];

    $subsidiary_phone = $_POST['subsidiary_phone'];
    $addressShip = $_POST['addressShip'];
    $name_client = $client_name . ' ' . $client_lastname;
    $text_ship = "<strong>para confirmar que tu pedido está en camino a tu domicilio.</strong> ";
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    $prod_list = getProducts($cart_shop);

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
        $mail->addAddress($client_email, $name_client);     //Add a recipient
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
        $mail->Subject = 'Resumen de su Compra';
        $mail->Body    = getHTMLMailConfirmationClient($client_name, $order_code, $prod_list, $total_sale, $addressShip,  $subsidiary_phone, $text_ship);


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

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    /*  $cart_shop = $_POST['cart_shop'];
    $total_sale = $_POST['total_sale'];
    $order_code = 'TLSLZR-'.mb_strtoupper($_POST['id_order']); */

    $today = date('Y-m-d H:i:s');
    /* 
    $sql = "INSERT INTO u803991314_main.orders (
    id_clients,
    id_orders_status_types,
    id_offers,
    id_payment_methods,
    id_subsidiary,
    order_code,
    pikup_subsidiary,
    ammount,
    shipping_address,
    order_phone,
    order_mail,
    order_date
    ) VALUES (
        2,
        2,
        1,
        4,
        1,
        '$order_code',
        1,
        '$total_sale',
        'CALLE ENTREGA',
        'TELEFONO ENTREGA',
        'CORREO ENTREGA',
        '$today'
    )
";
    $saveOrderIndex = $queries->InsertData($sql);
    if (!empty($saveOrderIndex)) {
        $id_order = $saveOrderIndex['last_id'];

        $totalSale = 0;
        foreach ($cart_shop as $cart) {
            $id_product = $cart['id_product'];
            $quantity = $cart['quantity'];
            $price = $cart['price'];

            $sql = "INSERT INTO u803991314_main.order_details (
                id_orders,
                id_prducts,
                price,
                quantity
            )VALUES (
                $id_order,
                $id_product,
                '$price',
                $quantity
            )
                ";
            $queries->InsertData($sql);
            $percentage = 0;
            /* foreach ($getProducts as $product) {

                if ($product->total_stock > 0) {
                    $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
                }

                if ($product->thumbnail == 'NULL' || $product->thumbnail == '') {
                    $image_prod = 'images/sin-imagen.png';
                } else {
                    $archive_route = str_replace('..', 'admin', $product->thumbnail);
                    $image_prod = $archive_route;
                    $file_exs = dirname(__DIR__ . '', 3) . str_replace('..', '', $product->thumbnail);
                   

                    if (file_exists($file_exs)) {
                        $image_prod = $archive_route;
                    } else {
                        $image_prod = 'images/sin-imagen.png';
                    }
                }
                $total_prod = $quantity * round($product->price, 2);
                $totalSale = $totalSale + $total_prod;
                $html .= '
                        <tr>
                            <td>' . $product->product_name . ' <strong class="mx-2">x</strong> ' . $quantity . '</td>
                            <td>$' . $total_prod . '</td>
                        </tr>
                        ';
            } 
        }
        $data = array(
            'response' => true,
            'message' => 'Su órden ha sido registrada, y se encuentra en proceso de validación',
            'order_code' => $order_code,
            'id_order' => $id_order,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al registrar la orden en nuestra base de datos'
        );
    } */

    echo json_encode($data);
}
function sendMailConfirmationSubsDelivery()
{

    $total_sale = $_POST['total_sale'];
    $client_name = $_POST['client_name'];
    $client_lastname = $_POST['client_lastname'];
    $client_email = $_POST['client_email'];
    $order_notes = $_POST['order_notes'];
    $cart_shop = $_POST['cart_shop'];
    $order_code = $_POST['order_code'];
    $subsidiary_name = $_POST['subsidiary_name'];
    $subsidiary_phone = $_POST['subsidiary_phone'];
    $addressShip = $_POST['addressShip'];
    $name_client = $client_name . ' ' . $client_lastname;
    $text_ship = "para que puedas acudir a la <strong>$subsidiary_name</strong> a recogerla. ";
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    $prod_list = getProducts($cart_shop);

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
        $mail->addAddress($client_email, $name_client);     //Add a recipient
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
        $mail->Subject = 'Resumen de su Compra';
        $mail->Body    = getHTMLMailConfirmationClient($client_name, $order_code, $prod_list, $total_sale, $addressShip, $subsidiary_phone, $text_ship);


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

    $queries = new Queries;

    //$id_product = $_POST['id_product'];
    /*  $cart_shop = $_POST['cart_shop'];
    $total_sale = $_POST['total_sale'];
    $order_code = 'TLSLZR-'.mb_strtoupper($_POST['id_order']); */

    $today = date('Y-m-d H:i:s');
    /* 
    $sql = "INSERT INTO u803991314_main.orders (
    id_clients,
    id_orders_status_types,
    id_offers,
    id_payment_methods,
    id_subsidiary,
    order_code,
    pikup_subsidiary,
    ammount,
    shipping_address,
    order_phone,
    order_mail,
    order_date
    ) VALUES (
        2,
        2,
        1,
        4,
        1,
        '$order_code',
        1,
        '$total_sale',
        'CALLE ENTREGA',
        'TELEFONO ENTREGA',
        'CORREO ENTREGA',
        '$today'
    )
";
    $saveOrderIndex = $queries->InsertData($sql);
    if (!empty($saveOrderIndex)) {
        $id_order = $saveOrderIndex['last_id'];

        $totalSale = 0;
        foreach ($cart_shop as $cart) {
            $id_product = $cart['id_product'];
            $quantity = $cart['quantity'];
            $price = $cart['price'];

            $sql = "INSERT INTO u803991314_main.order_details (
                id_orders,
                id_prducts,
                price,
                quantity
            )VALUES (
                $id_order,
                $id_product,
                '$price',
                $quantity
            )
                ";
            $queries->InsertData($sql);
            $percentage = 0;
            /* foreach ($getProducts as $product) {

                if ($product->total_stock > 0) {
                    $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
                }

                if ($product->thumbnail == 'NULL' || $product->thumbnail == '') {
                    $image_prod = 'images/sin-imagen.png';
                } else {
                    $archive_route = str_replace('..', 'admin', $product->thumbnail);
                    $image_prod = $archive_route;
                    $file_exs = dirname(__DIR__ . '', 3) . str_replace('..', '', $product->thumbnail);
                   

                    if (file_exists($file_exs)) {
                        $image_prod = $archive_route;
                    } else {
                        $image_prod = 'images/sin-imagen.png';
                    }
                }
                $total_prod = $quantity * round($product->price, 2);
                $totalSale = $totalSale + $total_prod;
                $html .= '
                        <tr>
                            <td>' . $product->product_name . ' <strong class="mx-2">x</strong> ' . $quantity . '</td>
                            <td>$' . $total_prod . '</td>
                        </tr>
                        ';
            } 
        }
        $data = array(
            'response' => true,
            'message' => 'Su órden ha sido registrada, y se encuentra en proceso de validación',
            'order_code' => $order_code,
            'id_order' => $id_order,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al registrar la orden en nuestra base de datos'
        );
    } */

    echo json_encode($data);
}
function getHTMLMailConfirmationClient($client_name, $order_code, $prod_list, $total_sale, $addressShip, $subsidiary_phone, $text_ship)
{
    $html = '<!DOCTYPE html>
    <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
    
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css"><!--<![endif]-->
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
    
            @media (max-width:695px) {
    
                .desktop_hide table.icons-inner,
                .social_block.desktop_hide .social-table {
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
    
                .row-3 .column-1 .block-3.paragraph_block td.pad>div {
                    font-size: 18px !important;
                }
    
                .row-5 .column-1 .block-1.paragraph_block td.pad>div,
                .row-5 .column-3 .block-1.paragraph_block td.pad>div {
                    font-size: 12px !important;
                }
    
                .row-5 .column-2 .block-1.paragraph_block td.pad>div {
                    font-size: 10px !important;
                }
    
                .row-6 .column-2 .block-1.paragraph_block td.pad>div,
                .row-6 .column-3 .block-1.paragraph_block td.pad>div,
                .row-7 .column-2 .block-1.paragraph_block td.pad>div {
                    font-size: 14px !important;
                }
    
                .row-7 .column-1 .block-1.paragraph_block td.pad>div {
                    font-size: 17px !important;
                }
    
                .row-9 .column-1 .block-2.paragraph_block td.pad>div {
                    font-size: 11px !important;
                }
            }
        </style>
    </head>
    
    <body style="background-color: #F5F5F5; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
        <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #F5F5F5;">
            <tbody>
                <tr>
                    <td>
                        <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:30px;line-height:30px;font-size:1px;">&#8202;</div>
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
                                        <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; color: #333; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-left: 25px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="padding-top:5px;width:100%;padding-right:0px;padding-left:0px;">
                                                                    <div class="alignment" align="left" style="line-height:10px">
                                                                        <div class="fullWidth" style="max-width: 292.5px;"><img src="https://a9643fabd5.imgdist.com/pub/bfra/70ubjxk6/6kg/2u5/hbv/navbar_logo_lg.png" style="display: block; height: auto; border: 0; width: 100%;" width="292.5" alt="Image" title="Image" height="auto"></div>
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
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #D6E7F0; color: #000000; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 15px; padding-top: 55px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:15px;padding-top:25px;width:100%;padding-right:0px;padding-left:0px;">
                                                                    <div class="alignment" align="center" style="line-height:10px">
                                                                        <div class="fullWidth" style="max-width: 506.25px;"><img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/386/illo_shipped.png" style="display: block; height: auto; border: 0; width: 100%;" width="506.25" alt="Image" title="Image" height="auto"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:5px;padding-left:15px;padding-right:10px;padding-top:20px;">
                                                                    <div style="color:#052D3D;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:38px;line-height:120%;text-align:center;mso-line-height-alt:45.6px;">
                                                                        <p style="margin: 0; word-break: break-word;"><span><strong><span>Tu órden&nbsp; <span style="color: #2190e3;">ha sido recibida!</span></span></strong></span></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-left:40px;padding-right:40px;">
                                                                    <div style="color:#052D3D;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:22px;line-height:150%;text-align:center;mso-line-height-alt:33px;">
                                                                        <p style="margin: 0; word-break: break-word;"><span><span>Hola <strong>' . $client_name . '</strong>,&nbsp; gracias de nuevo por comprar en www.gruposalazar.com.mx</span></span></p>
                                                                        <p style="margin: 0; word-break: break-word;"><span><span> Nos complace informarte que tu órden ha sido recibida y esta 
                                                                        siendo procesada por nuestro personal para su recolección. En cuanto esté lista te enviaremos un correo electrónico ' . $text_ship . '&nbsp; Ante cualquier duda o aclaración no dudes en contactarnos a través de este correo.</span></span></p>
                                                                        <p style="margin: 0; word-break: break-word;">&nbsp;</p>
                                                                        <p style="margin: 0; word-break: break-word;"><span><span>Tu código de órden es: <strong>' . $order_code . '</strong></span></span></p>
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
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; color: #000000; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 15px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="paragraph_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <div style="color:#052d3d;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:20px;line-height:120%;text-align:center;mso-line-height-alt:24px;">
                                                                        <p style="margin: 0; word-break: break-word;"><strong><span>Detalle de tu órden:</span></strong></p>
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
                                        <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #F8F8F8; color: #333; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 15px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                                    <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:14px;line-height:120%;text-align:center;mso-line-height-alt:16.8px;">
                                                                        <p style="margin: 0; word-break: break-word;"><strong>PRODUCTO</strong></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="column column-2" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; border-right: 1px dotted #E8E8E8; padding-bottom: 5px; padding-left: 15px; padding-right: 15px; padding-top: 15px; vertical-align: top; border-top: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                                    <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:14px;line-height:120%;text-align:center;mso-line-height-alt:16.8px;">
                                                                        <p style="margin: 0; word-break: break-word;"><strong>CANT.</strong></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="column column-3" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 15px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                                    <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:14px;line-height:120%;text-align:center;mso-line-height-alt:16.8px;">
                                                                        <p style="margin: 0;">PRECIO</p>
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
                        <table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #F9F9F9; color: #333; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                            ' . $prod_list . '
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
                                        <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #F8F8F8; color: #333; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="75%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; border-right: 1px dotted #E8E8E8; padding-bottom: 5px; padding-left: 15px; padding-right: 15px; padding-top: 15px; vertical-align: top; border-top: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                                    <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:20px;line-height:120%;text-align:center;mso-line-height-alt:24px;">
                                                                        <p style="margin: 0; word-break: break-word;"><strong>TOTAL DE COMPRA:</strong></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="column column-2" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 15px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                                    <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:21px;line-height:120%;text-align:center;mso-line-height-alt:25.2px;">
                                                                        <p style="margin: 0;"><strong>$' . $total_sale . '</strong></p>
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
                        <table class="row row-8" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #D6E7F0; color: #333; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #FFFFFF; border-bottom: 18px solid #D6E7F0; border-left: 18px solid #D6E7F0; border-right: 18px solid #D6E7F0; border-top: 18px solid #D6E7F0; padding-bottom: 10px; padding-left: 15px; padding-top: 5px; vertical-align: top;">
                                                        <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
                                                                    <div class="alignment" align="center" style="line-height:10px">
                                                                        <div style="max-width: 128.925px;"><img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/386/002-shipped.png" style="display: block; height: auto; border: 0; width: 100%;" width="128.925" alt="Image" title="Image" height="auto"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:5px;padding-left:15px;padding-right:15px;padding-top:15px;">
                                                                    <div style="color:#fc7318;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:20px;line-height:120%;text-align:left;mso-line-height-alt:24px;">
                                                                        <p style="margin: 0; word-break: break-word;"><span><strong>DIRECCIÓN DE ENTREGA</strong></span></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:15px;padding-left:15px;padding-right:15px;padding-top:5px;">
                                                                    <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:14px;line-height:150%;text-align:left;mso-line-height-alt:21px;">
                                                                        <p style="margin: 0; word-break: break-word;"><strong>' . $addressShip . '</strong></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #FFFFFF; border-bottom: 18px solid #D6E7F0; border-left: 18px solid #D6E7F0; border-right: 18px solid #D6E7F0; border-top: 18px solid #D6E7F0; padding-bottom: 10px; padding-left: 15px; padding-top: 5px; vertical-align: top;">
                                                        <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:20px;padding-top:20px;width:100%;padding-right:0px;padding-left:0px;">
                                                                    <div class="alignment" align="center" style="line-height:10px">
                                                                        <div style="max-width: 85.95px;"><img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/386/001-receipt.png" style="display: block; height: auto; border: 0; width: 100%;" width="85.95" alt="Image" title="Image" height="auto"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:5px;padding-left:15px;padding-right:15px;padding-top:15px;">
                                                                    <div style="color:#2190E3;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:24px;line-height:120%;text-align:left;mso-line-height-alt:28.799999999999997px;">
                                                                        <p style="margin: 0; word-break: break-word;"><span><strong>RECIBO DE COMPRA </strong></span></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="button_block block-3" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <div class="alignment" align="left"><!--[if mso]>
    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" style="height:42px;width:251px;v-text-anchor:middle;" arcsize="36%" stroke="false" fillcolor="#2190E3">
    <w:anchorlock/>
    <v:textbox inset="0px,0px,0px,0px">
    <center style="color:#ffffff; font-family:Tahoma, Verdana, sans-serif; font-size:16px">
    <![endif]-->
                                                                        <div style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#2190E3;border-radius:15px;width:auto;border-top:0px solid transparent;font-weight:undefined;border-right:0px solid transparent;border-bottom:0px solid transparent;border-left:0px solid transparent;padding-top:5px;padding-bottom:5px;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:16px;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;letter-spacing:normal;"><span style="word-break: break-word; line-height: 32px;"><strong>Tu recibo de compra esta adjunto en este correo</strong></span></span></div><!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
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
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #F0F0F0; color: #000000; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; border-bottom: 18px solid #FFFFFF; border-left: 25px solid #FFFFFF; border-right: 25px solid #FFFFFF; border-top: 18px solid #FFFFFF; padding-bottom: 5px; padding-left: 35px; padding-right: 35px; padding-top: 15px; vertical-align: top;">
                                                        <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:10px;padding-left:15px;padding-right:15px;padding-top:15px;">
                                                                    <div style="color:#052d3d;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:34px;line-height:120%;text-align:center;mso-line-height-alt:40.8px;">
                                                                        <p style="margin: 0; word-break: break-word;"><span><strong><span><span style="color: #fc7318;">¿Alguna pregunta?&nbsp;</span><br></span></strong><span>Estamos para ayudarte</span></span></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:30px;padding-left:10px;padding-right:10px;">
                                                                    <div style="color:#787878;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:18px;line-height:150%;text-align:center;mso-line-height-alt:27px;">
                                                                        <p style="margin: 0; word-break: break-word;">Envíanos un correo a&nbsp;<strong><a style="text-decoration: none; color: #2190E3;" href="#" target="_blank" rel="noopener">soporte@gruposalazar.com.mx</a></strong><br>O llámanos al &nbsp;<span style="color: #2190e3;">' . $subsidiary_phone . '</span></p>
                                                                        <p style="margin: 0; word-break: break-word;"><strong>Lunes a Domingo </strong></p>
                                                                        <p style="margin: 0; word-break: break-word;"><strong>de 8:30 A.M. -&nbsp; 5:30 P.M.&nbsp;</strong></p>
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
                        <table class="row row-10" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; color: #000000; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        <div class="spacer_block block-1" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="row row-11" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 675px; margin: 0 auto;" width="675">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 35px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                        
                                                        <table class="paragraph_block block-2" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:14px;line-height:150%;text-align:center;mso-line-height-alt:21px;">
                                                                        <p style="margin: 0; word-break: break-word;">Grupo Salazar - Todos los derechos reservados</p>
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

    return $html;
}
function getProducts($cart_shop)
{
    $html = "";

    $queries = new Queries;
    $totalSale = 0;
    foreach ($cart_shop as $cart) {
        $id_product = $cart['id_product'];
        $quantity = $cart['quantity'];

        $sql = "SELECT brand,
                prods.*
                FROM u803991314_main.products AS prods
                INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
                INNER JOIN u803991314_main.relationship_products_categories AS rpc ON rpc.id_prducts = prods.id_prducts
                INNER JOIN u803991314_main.categories AS ct ON ct.id_categories = rpc.id_categories
                WHERE prods.id_prducts = $id_product
    ";
        $getProducts = $queries->getData($sql);


        $percentage = 0;
        foreach ($getProducts as $product) {

            /*  if ($product->total_stock > 0) {
                $percentage = number_format((($product->total_stock / $product->ideal_stock) * 100), 0);
            } */

            if ($product->thumbnail == 'NULL' || $product->thumbnail == '') {
                $image_prod = 'images/sin-imagen.png';
            } else {
                $archive_route = str_replace('..', 'admin', $product->thumbnail);
                $image_prod = $archive_route;
                $file_exs = dirname(__DIR__ . '', 3) . str_replace('..', '', $product->thumbnail);
                /* echo $file_exs; */

                if (file_exists($file_exs)) {
                    $image_prod = $archive_route;
                } else {
                    $image_prod = 'images/sin-imagen.png';
                }
            }
            $total_prod = $quantity * round($product->price, 2);
            $totalSale = $totalSale + $total_prod;
            $html .= '
                        <tr>
                            <td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; border-right: 1px dotted #E8E8E8; padding-bottom: 35px; padding-left: 30px; padding-top: 30px; vertical-align: top; border-top: 0px; border-bottom: 0px; border-left: 0px;">
                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                    <tr>
                                        <td class="pad" style="padding-bottom:5px;padding-right:10px;padding-top:10px;">
                                            <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:16px;line-height:120%;text-align:left;mso-line-height-alt:19.2px;">
                                                <p style="margin: 0; word-break: break-word;"><span style="color: #2190e3;"><strong>' . $product->product_name . '&nbsp;</strong></span></p>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="column column-2" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; border-right: 1px dotted #E8E8E8; padding-bottom: 5px; padding-top: 55px; vertical-align: top; border-top: 0px; border-bottom: 0px; border-left: 0px;">
                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                    <tr>
                                        <td class="pad" style="padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                            <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:20px;line-height:120%;text-align:center;mso-line-height-alt:24px;">
                                                <p style="margin: 0; word-break: break-word;">' . $quantity . '</p>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="spacer_block block-2" style="height:50px;line-height:50px;font-size:1px;">&#8202;</div>
                            </td>
                            <td class="column column-3" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 55px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                <table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                    <tr>
                                        <td class="pad" style="padding-right:15px;">
                                            <div style="color:#555555;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;font-size:20px;line-height:120%;text-align:center;mso-line-height-alt:24px;">
                                                <p style="margin: 0; word-break: break-word;">$' . round($product->price, 2) . '</p>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
            ';
        }
    }
    return $html;
}

function saveNewProd()
{

    $prod_code = $_POST['prod_code'];
    $prod_name = $_POST['prod_name'];
    $prod_brand = $_POST['prod_brand'];
    $prod_sku = $_POST['prod_sku'];
    $prod_barcode = $_POST['prod_barcode'];
    $prod_meassure = $_POST['prod_meassure'];
    $prod_purchase_price = $_POST['prod_purchase_price'];
    $prod_price = $_POST['prod_price'];
    $prod_bulk = $_POST['prod_bulk'];
    /* $prod_stock = $_POST['prod_stock']; */
    $prod_min_stock = $_POST['prod_min_stock'];
    $prod_max_stock = $_POST['prod_max_stock'];
    $prod_description = $_POST['prod_description'];
    $prod_image = $_POST['prod_image'];

    $fecha_archivo = date('Y_m_d');
    $hora_archivo = date('H:i:s');
    $fyh = $fecha_archivo . ' ' . $hora_archivo;


    $nm_Archivo_img = "gpo_slzr_prodimg_" . time();
    $extension_img = basename($_FILES["prod_image"]["type"]);

    $directorio_img =  dirname(__DIR__ . '', 4) . '/uploads/prodsimg';

    $archivo_img = $directorio_img . "/" .  $nm_Archivo_img . "." . $extension_img;

    $ruta_sql_img = '../uploads/prodsimg/' .  $nm_Archivo_img . "." . $extension_img;

    $queries = new Queries;


    if (!file_exists($directorio_img)) {
        mkdir($directorio_img, 0777, true);
    }

    if (move_uploaded_file($_FILES["prod_image"]["tmp_name"], $archivo_img)) {

        $sql = "INSERT INTO u803991314_main.products
    (
        id_suppliers,
        id_brands,
        id_measurement_units,
        product_name,
        product_short_name,
        product_code,
        product_barcode,
        sku,
        purchase_price,
        price,
        description,
        thumbnail,
        image,
        image_type,
        min_stock,
        ideal_stock,
        bulk_sell,
        create_date,
        active_item

    ) VALUES(
        1,
        $prod_brand,
        $prod_meassure,
        '$prod_name',
        '$prod_code',
        '$prod_sku',
        '$prod_barcode',
        '$prod_sku',
        '$prod_purchase_price',
        '$prod_price',
        '$prod_description',
        '$ruta_sql_img',
        '$ruta_sql_img',
        '$extension_img',
        '$prod_min_stock',
        '$prod_max_stock',
        $prod_bulk,
        NOW(),
        1
    )";

        $queries = new Queries;
        $insert = $queries->insertData($sql);


        if (!empty($insert)) {
            $last_id = $insert['last_id'];
            $data = array(
                'response' => true,
                'message' => 'Se guardó el producto correctamente',
                'last_id' => $last_id
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'No se guardaron los archivos'
            );
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se guardó el archivo'
        );
    }


    echo json_encode($data);
}

function editImageProd()
{

    $id_prod = $_POST['id_prod'];
    $prod_image = $_POST['prod_image'];

    $fecha_archivo = date('Y_m_d');
    $hora_archivo = date('H:i:s');
    $fyh = $fecha_archivo . ' ' . $hora_archivo;


    $nm_Archivo_img = "gpo_slzr_prodimg_" . time();
    $extension_img = basename($_FILES["prod_image"]["type"]);

    $directorio_img =  dirname(__DIR__ . '', 3) . '/uploads/prodsimg';

    $archivo_img = $directorio_img . "/" .  $nm_Archivo_img . "." . $extension_img;

    $ruta_sql_img = '../uploads/prodsimg/' .  $nm_Archivo_img . "." . $extension_img;

    $queries = new Queries;


    if (!file_exists($directorio_img)) {
        mkdir($directorio_img, 0777, true);
    }

    if (move_uploaded_file($_FILES["prod_image"]["tmp_name"], $archivo_img)) {

        $sql = "UPDATE u803991314_main.products
        SET thumbnail = '$ruta_sql_img', image = '$ruta_sql_img', image_type = '$extension_img' WHERE id_prducts = $id_prod";

        $queries = new Queries;
        $insert = $queries->insertData($sql);


        if (!empty($insert)) {
            $last_id = $insert['last_id'];
            $data = array(
                'response' => true,
                'message' => 'Se guardó el producto correctamente',
                'last_id' => $last_id
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'No se guardaron los archivos'
            );
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se guardó el archivo'
        );
    }


    echo json_encode($data);
}
function editImageOffers()
{

    $id_offer = $_POST['id_offer'];
    $prod_image = $_POST['prod_image'];

    $fecha_archivo = date('Y_m_d');
    $hora_archivo = date('H:i:s');
    $fyh = $fecha_archivo . ' ' . $hora_archivo;


    $nm_Archivo_img = "gpo_slzr_prodimg_" . time();
    $extension_img = basename($_FILES["prod_image"]["type"]);

    $directorio_img =  dirname(__DIR__ . '', 3) . '/uploads/offers';

    $archivo_img = $directorio_img . "/" .  $nm_Archivo_img . "." . $extension_img;

    $ruta_sql_img = '../uploads/offers/' .  $nm_Archivo_img . "." . $extension_img;

    $queries = new Queries;


    if (!file_exists($directorio_img)) {
        mkdir($directorio_img, 0777, true);
    }

    if (move_uploaded_file($_FILES["prod_image"]["tmp_name"], $archivo_img)) {

        $sql = "UPDATE u803991314_main.offers
        SET thumbnail = '$ruta_sql_img' WHERE id_offers = $id_offer";

        $queries = new Queries;
        $insert = $queries->insertData($sql);


        if (!empty($insert)) {

            $data = array(
                'response' => true,
                'message' => 'Se actualizó correctamente la imagen!!!',
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'No se guardaron los archivos'
            );
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se guardó el archivo'
        );
    }


    echo json_encode($data);
}
function getProductStocks()
{

    $id_product = $_POST['id_product'];
    $product_name = $_POST['product_name'];

    $html = "";
    $html .= '<h4>' . $product_name . '</h4>';

    $html .= '  <table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Sucursal</th>
            <th scope="col">Stock</th>
        </tr>
    </thead>
    <tbody>
       ';
    $queries = new Queries;


    $sql = "SELECT sub.id_subsidiary, sub.subsidiary_name, stk.stock
        FROM u803991314_main.subsidiary AS sub
        LEFT JOIN u803991314_main.subsidiary_stocks AS stk  ON sub.id_subsidiary = stk.id_subsidiary AND stk.prducts_id_prducts = $id_product
        LEFT JOIN u803991314_main.products AS prd  ON prd.id_prducts = stk.prducts_id_prducts AND prd.id_prducts = $id_product";

    $queries = new Queries;
    $stocks = $queries->getData($sql);
    $total_stock = 0;

    if (!empty($stocks)) {
        foreach ($stocks as $stock) {
            $total_stock = $total_stock + $stock->stock;
            $html .= '
                    <tr>
                        <th scope="row">' . $stock->subsidiary_name . '</th>
                        <td class="tdStock" id="tdStockSubs' . $stock->id_subsidiary . '" data-stock="' . $stock->stock . '" data-id-subsidiary="' . $stock->id_subsidiary . '" data-id-prod="' . $id_product . '">' . $stock->stock . '</td>
                    </tr>';
        }

        $html .= '</tbody><tfoot>';
        $html .= '<tr>
        <th style="background-color:#a6cce3 !important;" scope="row"><strong>TOTAL</strong></th>
        <td class="tdStock" id="tdTotalStock" style="background-color:#a6cce3 !important;" data-total-stock="' . $total_stock . '"><strong>' . $total_stock . '</strong></td>
        </tr>';
        $html .= '
            </tfoot>
        </table>';

        $data = array(
            'response' => true,
            'html' => $html
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

function updateStocksSubsidiary()
{


    $id_product = $_POST['id_product'];
    $id_subsidiary = $_POST['id_subsidiary'];
    $stock = $_POST['stock'];

    $queries = new Queries;

    $sqlCI = "SELECT * FROM u803991314_main.subsidiary_stocks
    WHERE prducts_id_prducts = $id_product AND id_subsidiary = $id_subsidiary";

    if (empty($queries->getData($sqlCI))) {
        $sqlInsertStock = "INSERT INTO u803991314_main.subsidiary_stocks (
            stock,
            id_subsidiary,
            prducts_id_prducts
            )
            VALUES ('$stock',
            $id_subsidiary,
            $id_product
            )";
        $insert = $queries->InsertData($sqlInsertStock);

        if (!empty($insert)) {
            $last_id = $insert['last_id'];
            $data = array(
                'response' => true,
                'message' => 'Stock actualizado',
                'last_id' => $last_id
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Eror al actualizar stock'
            );
        }
    } else {
        $sql = "UPDATE u803991314_main.subsidiary_stocks
                SET stock = '$stock' WHERE prducts_id_prducts = $id_product AND id_subsidiary = $id_subsidiary";

        $queries = new Queries;
        $insert = $queries->insertData($sql);


        if (!empty($insert)) {
            $last_id = $insert['last_id'];
            $data = array(
                'response' => true,
                'message' => 'Stock actualizado',
                'last_id' => $last_id
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Eror al actualizar stock'
            );
        }
    }

    echo json_encode($data);
}

function getProductInfo()
{


    $id_product = $_POST['id_product'];

    $queries = new Queries;
    $prod_info = array();

    $sqlCI = "SELECT * FROM u803991314_main.products
    WHERE id_prducts = $id_product";
    $prod_info = $queries->getData($sqlCI);
    if (!empty($prod_info)) {
        $data = array(
            'response' => true,
            'message' => '',
            'prod_info' => $prod_info
        );
    } else {
        $data = array(
            'response' => true,
            'message' => 'Error al consultar producto',
            'prod_info' => $prod_info
        );
    }

    echo json_encode($data);
}
function updateProduct()
{

    $id_product = $_POST['id_product'];
    $column_name = $_POST['column_name'];
    $new_val = $_POST['new_val'];

    $queries = new Queries;

    $sqlUpdateProd = "UPDATE u803991314_main.products SET
                 $column_name = '$new_val'
             WHERE id_prducts = $id_product";

    $insert = $queries->InsertData($sqlUpdateProd);

    if (!empty($insert)) {
        $last_id = $insert['last_id'];
        $data = array(
            'response' => true,
            'message' => 'Producto actualizado',
            'last_id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Eror al actualizar Producto'
        );
    }

    echo json_encode($data);
}
function getProductTags()
{

    $id_product = $_POST['id_product'];
    $queries = new Queries;

    $sqlGetTagsProd = "SELECT DISTINCT tag_name FROM u803991314_main.tags AS tag
    INNER JOIN u803991314_main.relationship_products_tags AS rpt ON tag.id_tags = rpt.id_tags
    WHERE rpt.id_prducts = $id_product";

    $getTagsRel = $queries->getData($sqlGetTagsProd);


    $html = '';
    if (!empty($getTagsRel)) {
        foreach ($getTagsRel as $tag_rel) {
            $html .= '<p style="font-size:1rem !important" class="badge text-bg-primary">' . $tag_rel->tag_name . '</p>';
        }

        $data = array(
            'response' => true,
            'message' => 'Producto actualizado',
            'html' => $html
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Eror al actualizar Producto'
        );
    }

    echo json_encode($data);
}
function getOfferTags()
{

    $id_offer = $_POST['id_offer'];
    $queries = new Queries;

    $sqlGetTagsProd = "SELECT DISTINCT tag_name, rpt.id_tags  FROM u803991314_main.tags AS tag
    INNER JOIN u803991314_main.relationship_offers_tags AS rpt ON tag.id_tags = rpt.id_tags
    WHERE rpt.id_offers = $id_offer";

    $getTagsRel = $queries->getData($sqlGetTagsProd);


    $html = '';
    if (!empty($getTagsRel)) {
        foreach ($getTagsRel as $tag_rel) {
            $html .= '<p style="font-size:1rem !important" class="badge text-bg-primary offerTagItem" data-id-offer="' . $id_offer . '" data-id-tag="' . $tag_rel->id_tags . '">' . $tag_rel->tag_name . '</p>';
        }

        $data = array(
            'response' => true,
            'message' => 'Producto actualizado',
            'html' => $html
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Eror al actualizar Producto'
        );
    }

    echo json_encode($data);
}
function insertProductTags()
{

    $id_product = $_POST['id_product'];
    $id_tag = $_POST['id_tag'];
    $queries = new Queries;

    $sqlGetTagsProd = "SELECT *
    FROM u803991314_main.relationship_products_tags AS rpt 
    WHERE rpt.id_prducts = $id_product and rpt.id_tags = $id_tag";
    $getTagsRel = $queries->getData($sqlGetTagsProd);

    if (empty($getTagsRel)) {
        $sqlInsertTag = "INSERT INTO u803991314_main.relationship_products_tags (id_prducts, id_tags)
        VALUES(
            $id_product,
            $id_tag
        )";
        $getTagsRel = $queries->InsertData($sqlInsertTag);
        $data = array(
            'response' => true,
            'message' => 'Producto actualizado',
        );
    } else {
        $data = array(
            'response' => true,
            'message' => 'Producto actualizado',
        );
    }

    echo json_encode($data);
}
function insertOfferTags()
{

    $id_offers = $_POST['id_offer'];
    $id_tag = $_POST['id_tag'];
    $queries = new Queries;

    $sqlGetTagsProd = "SELECT *
    FROM u803991314_main.relationship_offers_tags AS rpt 
    WHERE rpt.id_offers = $id_offers and rpt.id_tags = $id_tag";
    $getTagsRel = $queries->getData($sqlGetTagsProd);

    if (empty($getTagsRel)) {
        $sqlInsertTag = "INSERT INTO u803991314_main.relationship_offers_tags (id_offers, id_tags)
        VALUES(
            $id_offers,
            $id_tag
        )";
        $getTagsRel = $queries->InsertData($sqlInsertTag);
        $data = array(
            'response' => true,
            'message' => 'Oferta actualizada',
        );
    } else {
        $data = array(
            'response' => true,
            'message' => 'Oferta actualizada',
        );
    }

    echo json_encode($data);
}
function removeOfferTags()
{

    $id_offers = $_POST['id_offer'];
    $id_tag = $_POST['id_tag'];
    $queries = new Queries;

    $sqlGetTagsProd = "SELECT *
    FROM u803991314_main.relationship_offers_tags AS rpt 
    WHERE rpt.id_offers = $id_offers and rpt.id_tags = $id_tag";
    $getTagsRel = $queries->getData($sqlGetTagsProd);

    if (!empty($getTagsRel)) {
        $sqlInsertTag = "DELETE FROM u803991314_main.relationship_offers_tags WHERE id_offers = $id_offers and id_tags = $id_tag";
        $getTagsRel = $queries->InsertData($sqlInsertTag);
        $data = array(
            'response' => true,
            'message' => 'Oferta actualizada',
        );
    } else {
        $data = array(
            'response' => true,
            'message' => 'Oferta actualizada',
        );
    }

    echo json_encode($data);
}

function deleteOffer()
{

    $id_offers = $_POST['id_offer'];
    $queries = new Queries;

    $sqlInsertTag = "DELETE FROM u803991314_main.relationship_offers_tags WHERE id_offers = $id_offers";
    $getTagsRel = $queries->InsertData($sqlInsertTag);

    $sqlInsertTag = "DELETE FROM u803991314_main.offers WHERE id_offers = $id_offers";
    $getTagsRel = $queries->InsertData($sqlInsertTag);
    $data = array(
        'response' => true,
        'message' => 'Oferta eliminada',
    );


    echo json_encode($data);
}
function getOfferDetails()
{

    $id_offers = $_POST['id_offer'];
    $queries = new Queries;

    $sqlInsertTag = "SELECT * FROM u803991314_main.relationship_offers_tags WHERE id_offers = $id_offers";
    $getTagsRel = $queries->getData($sqlInsertTag);

    $data = array(
        'response' => true,
        'data' => $getTagsRel
    );


    echo json_encode($data);
}

function saveNewOffer()
{

    $offer_name = $_POST['offer_name'];
    $percentage = $_POST['percentage'];
    $money_discount = $_POST['money_discount'];
    $init_date = $_POST['init_date'];
    $end_date = $_POST['end_date'];
    $offer_details = $_POST['offer_details'];
    $min_ammount = $_POST['min_ammount'];
    $queries = new Queries;

    $sqlInsertTag = "INSERT INTO u803991314_main.offers (
        offer_name,
        offer_details,
        min_ammount,
        start_date,
        end_date,
        percentage,
        money_discount,
        offer_status
    ) VALUES(
        '$offer_name',
        '$offer_details',
        '$min_ammount',
        '$init_date',
        '$end_date',
        '$percentage',
        '$money_discount',
        1
    )
    ";
    $getTagsRel = $queries->insertData($sqlInsertTag);

    $data = array(
        'response' => true,
        'message' => "Oferta guardada!!!"
    );


    echo json_encode($data);
}
function getOfferInfo()
{

    $id_offers = $_POST['id_offer'];
    $queries = new Queries;

    $sqlInsertTag = "SELECT DATE(start_date) AS d_start_date, DATE(end_date) AS d_end_date, offe.* FROM u803991314_main.offers AS offe WHERE id_offers = $id_offers";
    $getTagsRel = $queries->getData($sqlInsertTag);

    $data = array(
        'response' => true,
        'data' => $getTagsRel
    );


    echo json_encode($data);
}
function editOffer()
{

    $newVal = $_POST['newVal'];
    $column_name = $_POST['column_name'];
    $id_offer = $_POST['id_offer'];

    $queries = new Queries;

    $sqlInsertTag = "UPDATE u803991314_main.offers SET $column_name = '$newVal' WHERE id_offers = $id_offer
    ";
    $getTagsRel = $queries->insertData($sqlInsertTag);

    $data = array(
        'response' => true,
        'message' => "Oferta actrualizada!!!"
    );


    echo json_encode($data);
}
function deleteProduct()
{

    $id_product = $_POST['id_product'];

    $queries = new Queries;

    $sqlUpdateProd = "UPDATE u803991314_main.products SET active_item = 0 WHERE id_prducts = $id_product";

    $insert = $queries->InsertData($sqlUpdateProd);

    if (!empty($insert)) {
        $last_id = $insert['last_id'];
        $data = array(
            'response' => true,
            'message' => 'Producto eliminado',
            'last_id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Eror al eliminar Producto'
        );
    }

    echo json_encode($data);
}


function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
