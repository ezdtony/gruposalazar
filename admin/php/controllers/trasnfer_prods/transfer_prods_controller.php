<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}


function searchProductStock()
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
function saveNewStock()
{

    $queries = new Queries;

    $id_product = $_POST['id_product'];
    $id_subsidiary = $_POST['id_subsidiary'];
    $stock = $_POST['stock'];

    $sqlGetStock = "SELECT * FROM u803991314_main.subsidiary_stocks AS stk
    WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_product";

    $existStock = $queries->getData($sqlGetStock);

    if (!empty($existStock)) {

        $sqlUpdateStock = "UPDATE u803991314_main.subsidiary_stocks SET stock = '$stock' WHERE id_subsidiary = $id_subsidiary AND prducts_id_prducts = $id_product";
        $queries->insertData($sqlUpdateStock);

        if ($queries->insertData($sqlUpdateStock)) {
            $data = array(
                'response' => true,
                'message' => 'El stock actualizó el correctamente!!'
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al actualizar el stock!!'
            );
        }
    } else {
        $sqlInsertStock = "INSERT INTO u803991314_main.subsidiary_stocks
        (
            id_subsidiary,
            prducts_id_prducts,
            stock
        ) VALUES(
            $id_subsidiary,
            $id_product,
            '$stock'
        )";
        if ($queries->insertData($sqlInsertStock)) {
            $data = array(
                'response' => true,
                'message' => 'El stock se agregó correctamente!!'
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al agregar el stock!!'
            );
        }
    }



    echo json_encode($data);
}
function getTransfers()
{

    $queries = new Queries;

    $id_subsidiary = $_POST['id_subsidiary'];

    $colsSearch = [
        'id_prods_transfer'
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
    subs_or.subsidiary_name AS orign_subsidiary,
    subs_des.subsidiary_name AS destinity_subsidiary,
    DATE(datelog) AS date_transf,
    status_transfer,
    ptr.*
    FROM u803991314_main.prods_transfer AS ptr
    INNER JOIN u803991314_main.subsidiary AS subs_or ON  subs_or.id_subsidiary = ptr.id_subs_or
    INNER JOIN u803991314_main.subsidiary AS subs_des ON  subs_des.id_subsidiary = ptr.id_subs_des
    INNER JOIN u803991314_main.prods_transfer_status AS stat ON stat.id_prods_transfer_status = ptr.id_prods_transfer_status
    $where 
    ORDER BY ptr.id_prods_transfer ASC
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

        $sqlAllProds = "SELECT COUNT(ptr.id_prods_transfer) AS founded
            FROM u803991314_main.prods_transfer AS ptr
    INNER JOIN u803991314_main.subsidiary AS subs_or ON  subs_or.id_subsidiary = ptr.id_subs_or
    INNER JOIN u803991314_main.subsidiary AS subs_des ON  subs_des.id_subsidiary = ptr.id_subs_des
    INNER JOIN u803991314_main.prods_transfer_status AS stat ON stat.id_prods_transfer_status = ptr.id_prods_transfer_status
            ";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }


        foreach ($getOrder as $order) {



            $html .= '
            <tr id="trStocks' . $order->id_prods_transfer . '">
            <td class="name fw-bold" id="td_transfer_code_' . $order->id_prods_transfer . '">
                 ' . $order->transfer_code . '
            </td>
            <td class="name fw-bold" id="td_product_barcode_' . $order->id_prods_transfer . '">
                 ' . $order->orign_subsidiary . '
            </td>
            <td class="name fw-bold" id="td_product_name_' . $order->id_prods_transfer . '">
                 ' . $order->destinity_subsidiary . '
            </td>
            <td class="name fw-bold" id="td_product_name_' . $order->id_prods_transfer . '">
            ' . $order->date_transf . '
            </td>
            <td class="name fw-bold" id="td_status_transfer_' . $order->id_prods_transfer . '">
            ' . $order->status_transfer . '
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
