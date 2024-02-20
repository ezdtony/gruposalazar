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
function getStocks()
{

    $queries = new Queries;

    $id_subsidiary = $_POST['id_subsidiary'];

    $colsSearch = [
        'br.brand',
        'product_short_name',
        'product_barcode',
        'brand'
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
    CASE 
        WHEN subs_stk.stock IS NULL THEN '-'
        ELSE subs_stk.stock
    END
        AS subs_stock,
        br.brand,
        subs.id_subsidiary,
    prods.*
    FROM u803991314_main.products AS prods
    INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
    INNER JOIN u803991314_main.subsidiary AS subs ON  subs.id_subsidiary = $id_subsidiary
    LEFT JOIN u803991314_main.subsidiary_stocks AS subs_stk ON subs.id_subsidiary = subs_stk.id_subsidiary AND prods.id_prducts = subs_stk.prducts_id_prducts
    $where 
    ORDER BY product_name ASC
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

        $sqlAllProds = "SELECT COUNT(prods.id_prducts) AS founded
            FROM u803991314_main.products AS prods
    INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
    INNER JOIN u803991314_main.subsidiary AS subs ON  subs.id_subsidiary = $id_subsidiary
    LEFT JOIN u803991314_main.subsidiary_stocks AS subs_stk ON subs.id_subsidiary = subs_stk.id_subsidiary AND prods.id_prducts = subs_stk.prducts_id_prducts
            ";
        $getTotalProducts = $queries->getData($sqlAllProds);
        if (!empty($getTotalProducts)) {
            $totalProds = ($getTotalProducts[0]->founded);
        }


        foreach ($getOrder as $order) {



            $html .= '
            <tr id="trStocks' . $order->id_prducts . '">
            <td class="name fw-bold" id="td_id_prducts_' . $order->id_prducts . '">
                 ' . $order->product_short_name . '
            </td>
            <td class="name fw-bold" id="td_product_barcode_' . $order->id_prducts . '">
                 ' . $order->product_barcode . '
            </td>
            <td class="name fw-bold" id="td_product_name_' . $order->id_prducts . '">
                 ' . $order->product_name . '
            </td>
            <td class="name fw-bold" id="td_product_name_' . $order->id_prducts . '">
            ' . $order->brand . '
            </td>
            <td class="name fw-bold td edit-stock" id="tdEditStock' . $order->id_prducts . '" data-id-product="' . $order->id_prducts . '" data-id-subsidiary="' . $order->id_subsidiary . '" data-stock="' . $order->subs_stock . '"  id="td_product_name_' . $order->id_prducts . '">
            ' . $order->subs_stock . '
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
