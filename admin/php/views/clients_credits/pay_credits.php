<?php
$getCollaborators = $colabs_model->getAllClientsCredits();
$getAllClients = $colabs_model->getAllClients();
$getCreditsDetailPays = $colabs_model->getCreditsDetailPays();
?>
<h1 class="h2">Créditos</h1>

<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Pagos a crédito
                </h2>

                <!-- Link -->
                <!--  <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#newClientCredit">
                    Registrar crédito
                </a> -->
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle mb-0" id="tablePayCredits">
                    <thead class="thead-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Cantidad de pago</th>
                            <th>Fecha de Pago</th>
                            <th>Plan de Pago</th>
                            <th>Status</th>
                            <th class="text-end">Marcar como pagado</th>
                        </tr>
                    </thead>

                    <tbody id="tbodyColabs">
                        <?php foreach ($getCreditsDetailPays as $crpay) :
                            $today  = date('Y-m-d');
                            $enable_btn_pay = '';


                            $span = "<span class='legend-circle bg-info'></span>";
                            $txt_stat = "Al corriente";

                            if ($crpay->payment_status == 0) {
                                $span = "<span class='legend-circle bg-danger'></span>";
                                $txt_stat = "Con atraso";
                            }
                            if ($crpay->payment_status == 2) {
                                $span = "<span class='legend-circle bg-success'></span>";
                                $txt_stat = "Pagado";
                                $enable_btn_pay = 'disabled';
                            }

                            if ($today > $crpay->payment_date && $crpay->payment_status == 1) {
                                $span = "<span class='legend-circle bg-danger'></span>";
                                $txt_stat = "Con atraso";

                                $colabs_model->updatePaymentDet($crpay->id_credit_purchase_detail, 0);
                            }
                        ?>
                            <tr>
                                <td><?= $crpay->client_name ?> </td>
                                <td>$ <?= round($crpay->amount_payable, 2) ?> </td>
                                <td><?= $crpay->payment_date ?></td>
                                <td><?= $crpay->deadline_description ?></td>
                                <td><?php echo $span ?> <?php echo $txt_stat ?> </td>
                                <td class="text-end">
                                    <div class="fw-bold">
                                        <button <?=$enable_btn_pay?> type="button" class="btn btn-primary payCreditDetail"
                                            data-id=" <?= $crpay->id_credit_purchase_detail ?>"><i class="fas fa-hand-holding-usd"></i></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- / .table-responsive -->
        </div>
    </div>
</div>
<script src="js/functions/credits.js"></script>
<script src="js/functions/payCredits.js"></script>
<?php
//include 'modals/newClientCredit.php';
//include 'modals/purchaseHistory.php';
?>