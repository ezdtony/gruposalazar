<?php
$getCollaborators = $colabs_model->getAllClientsCredits();
$getAllClients = $colabs_model->getAllClients();
?>
<h1 class="h2">Créditos</h1>

<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Créditos a clientes
                </h2>

                <!-- Link -->
                <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#newClientCredit">
                    Registrar crédito
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle mb-0" id="tableColabs">
                    <thead class="thead-light">
                        <tr>
                            <th>Cliente</th>
                            <th>Crédito total</th>
                            <th>Crédito disponible</th>
                            <th class="text-end">Historial de compras</th>
                        </tr>
                    </thead>

                    <tbody id="tbodyColabs">
                        <?php foreach ($getCollaborators as $colab) : ?>
                            <tr>
                                <!-- <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-circle avatar-xs me-2">
                                        <img src="https://d33wubrfki0l68.cloudfront.net/790b7dd581a3ac4fd0410afad0fb12c6e93c9e7a/b0657/assets/images/profiles/profile-07.jpeg" alt="..." class="avatar-img" width="30" height="30" />
                                    </div>

                                    <div class="d-flex flex-column">
                                        <span class="fw-bold d-block">Lester William</span>
                                        <span class="fs-6 text-muted">24 minutes ago</span>
                                    </div>
                                </div>
                            </td> -->
                                <td><?= $colab->client_name ?> </td>
                                <td>$ <?= $colab->credit_ammount ?></td>
                                <td>$ <?= $colab->credit_line ?></td>
                                <td class="text-end">
                                    <div class="fw-bold"><button type="button"class="btn btn-primary"><i class="fa-solid fa-info"></i></button>
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
<?php
include 'modals/newClientCredit.php';
?>