<?php
$getCollaborators = $colabs_model->getAllClients();

$getSates = $colabs_model->getAllStates();
$getRegimenesFiscales = $sales_model->getRegimenesFiscales();
$usosCFDI = $sales_model->usosCFDI();
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
                    Registrar cliente
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle mb-0" id="tableColabs">
                    <thead class="thead-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Contraseña</th>
                            <th>Editar</th>
                            <!-- <th>Borrar</th> -->
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
                                <td><?= $colab->name ?> <?= $colab->lastname ?></td>
                                <td><?= $colab->cellphone ?></td>
                                <td><?= $colab->email ?></td>
                                <td>
                                    <div class="fw-bold"><?= $colab->password ?></div>
                                </td>
                                <td>
                                    <button type="button" class="btn text-bg-primary-soft btnEditClient" data-bs-toggle="modal" data-id-client="<?= $colab->id_clients ?>" data-bs-target="#editClientModal"><i class="fa-solid fa-pen-to-square"></i></i></button>
                                </td>
                               <!--  <td>
                                    <button type="button" class="btn text-bg-danger-soft deleteClient" data-id-client="<?= $colab->id_clients ?>"><i class="fa-solid fa-trash"></i></button>
                                </td> -->
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
include 'modals/editClient.php';
?>