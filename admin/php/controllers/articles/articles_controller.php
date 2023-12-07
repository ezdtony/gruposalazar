<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
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
    if (empty($prod_info)) {
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

function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
