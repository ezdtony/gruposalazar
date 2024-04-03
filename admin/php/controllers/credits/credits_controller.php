<?php
include_once dirname(__DIR__ . '', 2) . "/models/petitions.php";

session_start();
date_default_timezone_set('America/Mexico_City');

if (!empty($_POST['mod'])) {
    $function = $_POST['mod'];
    $function();
}

function saveClientCredit()
{

    $id_client = $_POST['id_client'];
    $ammount = $_POST['ammount'];

    $queries = new Queries;

    $stmt = "INSERT INTO u803991314_main.clients_credits (
        id_clients,
        credit_ammount,
        credit_line,
        datelog
    ) VALUES (
        $id_client,
        '$ammount',
        '$ammount',
        NOW()
    )";

    $insertAddress = $queries->InsertData($stmt);

    if (!empty($insertAddress)) {
        $idCredit = $insertAddress['last_id'];
        $credit_code = "CRSLZ-" . $idCredit . "-" . rand(0, 20);
        $sqlUpdateCode = "UPDATE u803991314_main.clients_credits SET credit_code = '$credit_code' WHERE id_clients_credits = $idCredit";
        $queries->InsertData($sqlUpdateCode);

        $data = array(
            'response' => true,
            'id_colab'                => $idCredit,
            'message'                => 'Se ha registrado el crédito con éxito!!!',
        );
        //--- --- ---//

        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}
function getPurchaseHistoryCredit()
{


    $id_clients_credits = $_POST['id_clients_credits'];

    $queries = new Queries;

    $stmtCP = "SELECT DATE(cp.datelog) AS purchase_date, cp.* FROM u803991314_main.credit_purchases AS cp WHERE id_clients_credits = $id_clients_credits";

    $getCreditPurchases = $queries->getData($stmtCP);
    $html = '';
    if (!empty($getCreditPurchases)) {
        foreach ($getCreditPurchases as $purchases) {
            $purchase_date = $purchases->purchase_date;
            $id_orders = $purchases->id_orders;
            $total_purchase = round($purchases->total_purchase, 2);
            $html .= '<div class="table-responsive">';
            $html .= '    <table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th class="table-dark text-white" scope="col" colspan="3">Compra #' . $id_orders . ' | Total de compra: $ ' . $total_purchase . ' MXN  | Fecha: ' . $purchase_date . '</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th colspan="3">PAGOS</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th>CANTIDAD</th>';
            $html .= '<th>FECHA PAGO</th>';
            $html .= '<th>STATUS</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';


            $id_credit_purchases = $purchases->id_credit_purchases;
            $stmtCD = "SELECT * FROM u803991314_main.credit_purchase_detail WHERE id_credit_purchases = $id_credit_purchases";
            $getCreditPDetails = $queries->getData($stmtCD);
            foreach ($getCreditPDetails as $purchase_details) {
                $payment_date = $purchase_details->payment_date;
                $amount_payable = round($purchase_details->amount_payable, 2);
                $payment_status = $purchase_details->payment_status;
                $txt_payment_status = "N/A";
                switch ($payment_status) {
                    case '1':
                        $txt_payment_status = "POR PAGAR";
                        break;

                    default:
                        $txt_payment_status = "N/A";
                        break;
                }

                $html .= '<tr>';
                $html .= '<td>' . $payment_date . '</td>';
                $html .= '<td> $ ' . $amount_payable . ' MXN</td>';
                $html .= '<td>' . $txt_payment_status . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
            $html .=
                '</div>';
        }
        $data = array(
            'response' => true,
            'html' => $html
        );
        //--- --- ---//

        //--- --- ---//
    } else {
        //--- --- ---//
        $data = array(
            'response' => false,
            'message'                => ''
        );
        //--- --- ---//
    }

    echo json_encode($data);
}

function generateRandomString($length)
{
    return substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
