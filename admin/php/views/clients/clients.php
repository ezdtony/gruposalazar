<?php
$getCollaborators = $colabs_model->getAllColabs();
$getSates = $colabs_model->getAllStates();
$getSubsidiary = $colabs_model->getSubsidiary();
$getPositions = $colabs_model->getPositions();
?>
<h1 class="h2">Clientes</h1>

<div class="row">
    <div class="col-xxl-12 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Clientes registrados
                </h2>

                <!-- Link -->
                <a class="small fw-bold" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#newClientModal">
                    Registrar clientes
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle mb-0" id="tableColabs">
                    <thead class="thead-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th class="text-end">Contraseña acceso</th>
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
                                <td><?= $colab->colaborator_code ?></td>
                                <td><?= $colab->name ?> <?= $colab->lastname ?></td>
                                <td><?= $colab->business_mail ?></td>
                                <td class="text-end">
                                    <div class="fw-bold"><?= $colab->password_access ?></div>
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
<script src="js/functions/clients.js"></script>
<?php
include 'modals/newClient.php';
?>