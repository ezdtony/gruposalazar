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
function updateStatusTransfer()
{

    $queries = new Queries;

    $id_prods_transfer = $_POST['id_transfer'];
    $transferStatus = $_POST['transferStatus'];

    $sqlGetStock = "SELECT * FROM u803991314_main.prods_transfer AS pr_tr
    WHERE id_prods_transfer = $id_prods_transfer";

    $existStock = $queries->getData($sqlGetStock);

    if (!empty($existStock)) {

        $id_subs_or = $existStock[0]->id_subs_or;
        $id_subs_des = $existStock[0]->id_subs_des;

        $sqlUpdateProdsTransfer = "UPDATE u803991314_main.prods_transfer SET id_prods_transfer_status = $transferStatus WHERE id_prods_transfer = $id_prods_transfer";
        $queries->insertData($sqlUpdateProdsTransfer);

        $sqlGetStockUpdate = "SELECT * FROM u803991314_main.prods_transfer_detail AS pr_tr
    WHERE id_prods_transfer = $id_prods_transfer";

        $existStock = $queries->getData($sqlGetStockUpdate);

        if (!empty($existStock)) {
            foreach ($existStock as $exiStock) {
                $id_product = $exiStock->id_products;
                $quantity = $exiStock->quantity;
                $id_prods_transfer_detail = $exiStock->id_prods_transfer_detail;

                $sqlUpdateStockDetail = "UPDATE u803991314_main.prods_transfer_detail
                     SET 
                     completed = 1
                     WHERE id_prods_transfer_detail = $id_prods_transfer_detail";
                $queries->insertData($sqlUpdateStockDetail);


                $sqlUpdateStockDetailOrigin = "UPDATE u803991314_main.subsidiary_stocks
                SET 
                stock = stock - '$quantity'
                WHERE id_subsidiary = $id_subs_or AND prducts_id_prducts = $id_product";
                $queries->insertData($sqlUpdateStockDetailOrigin);


                $sqlGetStockUpdate = "SELECT * FROM u803991314_main.subsidiary_stocks
                WHERE id_subsidiary = $id_subs_des AND prducts_id_prducts = $id_product";

                $existStock = $queries->getData($sqlGetStockUpdate);
                if (!empty($existStock)) {
                    $sqlUpdateStockDetailDest = "UPDATE u803991314_main.subsidiary_stocks
                     SET 
                     stock = stock + '$quantity'
                     WHERE id_subsidiary = $id_subs_des AND prducts_id_prducts = $id_product";
                    $queries->insertData($sqlUpdateStockDetailDest);
                } else {
                    $sqlUpdateStockDetail = "INSERT INTO u803991314_main.subsidiary_stocks(id_subsidiary, stock, prducts_id_prducts)
                     VALUES($id_subs_des, $quantity, $id_product)";
                    $queries->insertData($sqlUpdateStockDetail);
                }
            }
        }

        $data = array(
            'response' => true,
            'message' => 'El stock actualizó el correctamente!!'
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al agregar el stock!!'
        );
    }



    echo json_encode($data);
}

function getStatusTransfer()
{

    $queries = new Queries;

    $id_prods_transfer = $_POST['id_transfer'];

    $sqlGetStock = "SELECT 
    sub_og.subsidiary_name AS subs_origin,
    sub_des.subsidiary_name AS subs_destination,
    pts.status_transfer,
    pr_tr.id_subs_or,
    pr_tr.id_subs_des
     FROM u803991314_main.prods_transfer AS pr_tr
    INNER JOIN u803991314_main.subsidiary AS sub_og ON sub_og.id_subsidiary = pr_tr.id_subs_or
    INNER JOIN u803991314_main.subsidiary AS sub_des ON sub_des.id_subsidiary = pr_tr.id_subs_des
    INNER JOIN u803991314_main.prods_transfer_status AS pts ON pts.id_prods_transfer_status = pr_tr.id_prods_transfer_status
    WHERE id_prods_transfer = $id_prods_transfer";

    $existStock = $queries->getData($sqlGetStock);

    if (!empty($existStock)) {

        $subs_origin = $existStock[0]->subs_origin;
        $subs_destination = $existStock[0]->subs_destination;
        $status_transfer = $existStock[0]->status_transfer;
        $id_subs_des = $existStock[0]->id_subs_des;
        $id_subs_or = $existStock[0]->id_subs_or;


        $sqlGetProdDetail = "SELECT 
            prd.id_prducts,
            pr_tr.quantity,
            prd.product_name
        FROM u803991314_main.prods_transfer_detail AS pr_tr
        INNER JOIN u803991314_main.products AS prd ON prd.id_prducts = pr_tr.id_products
        WHERE id_prods_transfer = $id_prods_transfer";

        $TtransferDetail = $queries->getData($sqlGetProdDetail);
        $html = "";
        $no_partida = 0;
        if (!empty($TtransferDetail)) {
            foreach ($TtransferDetail as $exiStock) {
                $no_partida++;
                $id_product = $exiStock->id_prducts;
                $quantity = $exiStock->quantity;
                $product_name = $exiStock->product_name;

                $html .= ' <tr>
                                <th scope="row">' . $no_partida . '</th>
                                <td>' . $product_name . '</td>
                                <td>' . $quantity . '</td>
                            </tr>';
            }
        }

        $data = array(
            'response' => true,
            'html' => $html
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al agregar el stock!!'
        );
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

            if ($order->id_prods_transfer_status == 3) {
                $btn_complete = '<button disabled type="button" title="Traspaso completo" class="btn btn-success btnConfirmTransfer"><i class="fa-solid fa-check"></i></button>';
            } else {
                $btn_complete = '<button type="button" title="Traspaso completo" data-id-prod-transfer="' . $order->id_prods_transfer . '" class="btn btn-success btnConfirmTransfer"><i class="fa-solid fa-check"></i></button>';
            }

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
            <td class="name fw-bold">
            <button type="button" title="Detalle de la orden" data-id-prod-transfer="' . $order->id_prods_transfer . '" class="btn btn-info btnTransferDetail" data-bs-toggle="modal" data-bs-target="#modalInfotransfer"><i class="fa-solid fa-info"></i></button>
            </td>
            <td class="name fw-bold">
            ' . $btn_complete . '
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
function insertTransfer()
{

    $queries = new Queries;

    $id_subsidiary_og = $_POST['subsidiary_og'];
    $id_subsidiary_dest = $_POST['subsidiary_des'];
    $products_income = $_POST['products_income'];

    $sqlGetSOGDet = "SELECT * FROM u803991314_main.subsidiary WHERE id_subsidiary = $id_subsidiary_og";
    $getSOGDet = $queries->getData($sqlGetSOGDet);
    $subsidiary_og_prefix = substr($getSOGDet[0]->subsidiary_prefix, 4, 2);


    $sqlGetSDestDet = "SELECT * FROM u803991314_main.subsidiary WHERE id_subsidiary = $id_subsidiary_dest";
    $getSDestDet = $queries->getData($sqlGetSDestDet);
    $subsidiary_des_prefix = substr($getSDestDet[0]->subsidiary_prefix, 4, 2);


    $sqlInsertProductsIncome = "INSERT INTO u803991314_main.prods_transfer (
        id_subs_or,
        id_subs_des,
        id_prods_transfer_status,
        id_colaborator,
        datelog
    )
    VALUES (
        $id_subsidiary_og,
        $id_subsidiary_dest,
        1,
        $_SESSION[id_user],
        NOW()
    )
    ";

    $insert = $queries->insertData($sqlInsertProductsIncome);


    if (!empty($insert)) {
        $id_prods_transfer = $insert['last_id'];
        $transfer_code = "TRF-" . $subsidiary_og_prefix . "-" . $subsidiary_des_prefix . "-0" . $id_prods_transfer;
        $queries->insertData("UPDATE u803991314_main.prods_transfer SET transfer_code = '$transfer_code' WHERE id_prods_transfer = $id_prods_transfer ");

        for ($i = 0; $i < count($products_income); $i++) {
            $id_product = $products_income[$i][0];
            $quantity = $products_income[$i][1];

            $sqlInsertProductsIncomeDetail = "INSERT INTO u803991314_main.prods_transfer_detail (
                id_prods_transfer,
                id_products,
                completed,
                quantity
            )
            VALUES(
                $id_prods_transfer,
                $id_product,
                0,
                $quantity

            )";
            $queries->insertData($sqlInsertProductsIncomeDetail);
        }

        $data = array(
            'response' => true,
            'message' => 'Se ha registrado el traspaso de productos!!'
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al registrar el traspaso'
        );
    }



    echo json_encode($data);
}
