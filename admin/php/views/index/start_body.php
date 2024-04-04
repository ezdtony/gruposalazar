<?php

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<h1 class="h2">Panel principal</h1>

<div class="row">
    <div class="col-lg-6 col-xxl-3 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <!-- Title -->
                        <h5 class="text-uppercase text-muted fw-semibold mb-2">
                            Clientes
                        </h5>

                        <!-- Subtitle -->
                        <h2 class="mb-0"><?php echo $index_model->getClients() ?></h2>
                    </div>
                    <div class="col-auto">
                        <!-- Icon -->
                        <svg viewBox="0 0 24 24" height="30" width="30" class="text-primary" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.250 6.000 A2.250 2.250 0 1 0 6.750 6.000 A2.250 2.250 0 1 0 2.250 6.000 Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                            <path d="M4.5,9.75A3.75,3.75,0,0,0,.75,13.5v2.25h1.5l.75,6H6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                            <path d="M17.250 6.000 A2.250 2.250 0 1 0 21.750 6.000 A2.250 2.250 0 1 0 17.250 6.000 Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                            <path d="M19.5,9.75a3.75,3.75,0,0,1,3.75,3.75v2.25h-1.5l-.75,6H18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                            <path d="M9.000 3.750 A3.000 3.000 0 1 0 15.000 3.750 A3.000 3.000 0 1 0 9.000 3.750 Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                            <path d="M17.25,13.5a5.25,5.25,0,0,0-10.5,0v2.25H9l.75,7.5h4.5l.75-7.5h2.25Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                        </svg>
                    </div>
                </div>
                <!-- / .row -->
            </div>
            <div class="card-footer">
                <!--  <div class="row justify-content-between">
                    <div class="col-auto">
                        <p class="fs-6 text-muted text-uppercase mb-0">
                            Today clients
                        </p>

                        <p class="fs-5 fw-bold mb-0">57</p>
                    </div>
                    <div class="col text-end text-truncate">
                        <p class="fs-6 text-muted text-uppercase mb-0">
                            Monthly clients
                        </p>

                        <p class="fs-5 fw-bold mb-0">681</p>
                    </div>
                </div> -->
                <!-- / .row -->
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xxl-3 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <!-- Title -->
                        <h5 class="text-uppercase text-muted fw-semibold mb-2">
                            VENTAS
                        </h5>

                        <!-- Subtitle -->
                        <h2 class="mb-0"><?php echo $index_model->getSales() ?></h2>
                    </div>
                    <div class="col-auto">
                        <!-- Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" height="30" width="30" class="text-primary">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 19.1249H15.921C16.2753 19.125 16.6182 18.9996 16.889 18.7709C17.1597 18.5423 17.3407 18.2253 17.4 17.8759L20.037 2.37593C20.0965 2.02678 20.2776 1.70994 20.5483 1.48153C20.819 1.25311 21.1618 1.12785 21.516 1.12793H22.5"></path>
                            <path stroke="currentColor" stroke-width="1.5" d="M7.875 22.125C7.66789 22.125 7.5 21.9571 7.5 21.75C7.5 21.5429 7.66789 21.375 7.875 21.375"></path>
                            <path stroke="currentColor" stroke-width="1.5" d="M7.875 22.125C8.08211 22.125 8.25 21.9571 8.25 21.75C8.25 21.5429 8.08211 21.375 7.875 21.375"></path>
                            <path stroke="currentColor" stroke-width="1.5" d="M15.375 22.125C15.1679 22.125 15 21.9571 15 21.75C15 21.5429 15.1679 21.375 15.375 21.375"></path>
                            <path stroke="currentColor" stroke-width="1.5" d="M15.375 22.125C15.5821 22.125 15.75 21.9571 15.75 21.75C15.75 21.5429 15.5821 21.375 15.375 21.375"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.9529 14.6251H5.88193C5.21301 14.625 4.5633 14.4014 4.03605 13.9897C3.5088 13.5781 3.13425 13.002 2.97193 12.3531L1.52193 6.55309C1.49426 6.44248 1.49218 6.32702 1.51583 6.21548C1.53949 6.10394 1.58827 5.99927 1.65846 5.90941C1.72864 5.81955 1.81839 5.74688 1.92089 5.69692C2.02338 5.64696 2.13591 5.62103 2.24993 5.62109H19.4839"></path>
                        </svg>
                    </div>
                </div>
                <!-- / .row -->
            </div>
            <div class="card-footer">
                <div class="row justify-content-between">
                    <div class="col-auto">
                        <!-- Label -->
                        <p class="fs-6 text-muted text-uppercase mb-0">
                            Ventas de Hoy
                        </p>

                        <!-- Comment -->
                        <p class="fs-5 fw-bold mb-0"><?php echo $index_model->getTodaySales() ?></p>
                    </div>
                    <div class="col text-end text-truncate">
                        <!-- Label -->
                        <p class="fs-6 text-muted text-uppercase mb-0">
                            Ventas del mes
                        </p>

                        <!-- Comment -->
                        <p class="fs-5 fw-bold mb-0"><?php echo $index_model->getMonthSales() ?></p>
                    </div>
                </div>
                <!-- / .row -->
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xxl-3 d-flex">
        <!-- Card -->
        <div class="card border-0 text-bg-primary flex-fill w-100">
            <div class="card-body">
                <!-- Title -->
                <h4 class="text-uppercase fw-semibold mb-2">Ventas totales</h4>

                <!-- Subtitle -->
                <h2 class="mb-0">$<?php echo $index_model->getTotalAmmounSales() ?></h2>

                <!-- Chart -->
                <div class="chart-container h-70px">
                    <canvas id="currentBalanceChart" width="243" height="70" style="
                      display: block;
                      box-sizing: border-box;
                      height: 70px;
                      width: 243px;
                    "></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xxl-3 d-flex">
        <!-- Card -->
        <!--  <div class="card border-0 flex-fill w-100">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        
                        <h5 class="text-uppercase text-muted fw-semibold mb-2">
                            Ganancias
                        </h5>

                        
                        <h2 class="mb-0">$717,214</h2>
                    </div>
                    <div class="col-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="30" width="30" class="text-primary">
                            <defs>
                                <style>
                                    .a {
                                        fill: none;
                                        stroke: currentColor;
                                        stroke-linecap: round;
                                        stroke-linejoin: round;
                                        stroke-width: 1.5px;
                                    }
                                </style>
                            </defs>
                            <title>monitor-graph-line</title>
                            <polygon class="a" points="15 23.253 9 23.253 9.75 18.753 14.25 18.753 15 23.253"></polygon>
                            <line class="a" x1="6.75" y1="23.253" x2="17.25" y2="23.253"></line>
                            <rect class="a" x="0.75" y="0.753" width="22.5" height="18" rx="3" ry="3"></rect>
                            <path class="a" d="M18.75,5.253H16.717a1.342,1.342,0,0,0-.5,2.588l2.064.825a1.342,1.342,0,0,1-.5,2.587H15.75"></path>
                            <line class="a" x1="17.25" y1="5.253" x2="17.25" y2="4.503"></line>
                            <line class="a" x1="17.25" y1="12.003" x2="17.25" y2="11.253"></line>
                            <path class="a" d="M.75,11.253,4.72,7.284a.749.749,0,0,1,1.06,0L7.72,9.223a.749.749,0,0,0,1.06,0l3.97-3.97"></path>
                            <line class="a" x1="0.75" y1="15.753" x2="23.25" y2="15.753"></line>
                        </svg>
                    </div>
                </div>
                
            </div>
            <div class="card-footer">
                <div class="row justify-content-between">
                    <div class="col-auto">
                        
                        <p class="fs-6 text-muted text-uppercase mb-0">
                            Today earnings
                        </p>

                        
                        <p class="fs-5 fw-bold mb-0">£2,230</p>
                    </div>
                    <div class="col text-end text-truncate">
                        
                        <p class="fs-6 text-muted text-uppercase mb-0">
                            Monthly earnings
                        </p>

                        
                        <p class="fs-5 fw-bold mb-0">$158,990</p>
                    </div>
                </div>
                
            </div>
        </div> -->
    </div>

</div>


<div class="row">
    <div class="col-xxl-8 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100" data-list='{"valueNames": ["name", "price", "quantity", "amount", {"name": "sales", "attr": "data-sales"}], "page": 5}' id="topSellingProducts">
            <div class="card-header border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Productos más vendidos
                </h2>

                <!-- Dropdown -->
                <div class="dropdown">
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
                        <a href="javascript: void(0);" class="dropdown-item">
                            Action
                        </a>
                        <a href="javascript: void(0);" class="dropdown-item">
                            Another action
                        </a>
                        <a href="javascript: void(0);" class="dropdown-item">
                            Something else here
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table align-middle table-edge table-nowrap mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="name">
                                    Producto
                                </a>
                            </th>
                            <th class="text-end">
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="price">
                                    Precio
                                </a>
                            </th>
                            <th class="text-end">
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="quantity">
                                    Cantidad
                                </a>
                            </th>
                            <th class="text-end">
                                <a href="javascript: void(0);" class="text-muted list-sort" data-sort="amount">
                                    Importe total
                                </a>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="list">
                        <?php $getTopSales = $index_model->getTopSales() ?>
                        <?php foreach ($getTopSales as $top_sale) : ?>
                            <tr>
                                <td class="name fw-bold"><?= $top_sale->product_name ?></td>
                                <td class="price text-end">$<?= round($top_sale->price, 2) ?> </td>
                                <td class="quantity text-end"><?= $top_sale->quantity ?></td>
                                <td class="amount text-end">$ <?= round($top_sale->ammount_prod, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- / .table-responsive -->
        </div>
    </div>
    <div class="col-xxl-4 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    VENTAS recientes
                </h2>

                <!-- Link -->
                <a href="sales_history.php" class="small fw-bold">
                    Ver todas
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>FECHA</th>
                            <th class="text-end">CANTIDAD</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $getLastSales = $index_model->getLastSales() ?>
                        <?php foreach ($getLastSales as $sale) : ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!--  <div class="avatar avatar-circle avatar-xs me-2">
                                        <img src="https://d33wubrfki0l68.cloudfront.net/790b7dd581a3ac4fd0410afad0fb12c6e93c9e7a/b0657/assets/images/profiles/profile-07.jpeg" alt="..." class="avatar-img" width="30" height="30" />
                                    </div> -->

                                        <div class="d-flex flex-column">
                                            <span class="fw-bold d-block"><?= $sale->order_date_simple ?></span>
                                            <span class="fs-6 text-muted"><?= $sale->subsidiary_name ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold"> $ <?= round($sale->ammount, 2) ?> MXN</div>
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


<div class="row">
    <div class="col-xxl-6 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Estado de VENTAS
                </h2>

                <!-- Dropdown -->
                <div class="dropdown">
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
                        <a href="javascript: void(0);" class="dropdown-item">
                            Action
                        </a>
                        <a href="javascript: void(0);" class="dropdown-item">
                            Another action
                        </a>
                        <a href="javascript: void(0);" class="dropdown-item">
                            Something else here
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
            <canvas id="myChart"></canvas>
                <div class="row justify-content-around">
                    
                </div>
                <!-- / .row -->
            </div>
        </div>
    </div>
    <div class="col-xxl-6 d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Ventas por sucursal
                </h2>
            </div>

            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-sm table-borderless align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th class="text-end">Price</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-circle avatar-xs me-2">
                                            <img src="https://d33wubrfki0l68.cloudfront.net/790b7dd581a3ac4fd0410afad0fb12c6e93c9e7a/b0657/assets/images/profiles/profile-07.jpeg" alt="..." class="avatar-img" width="30" height="30" />
                                        </div>

                                        <div class="d-flex flex-column">
                                            <span class="fw-bold d-block">Lester William</span>
                                            <span class="fs-6 text-muted">24 minutes ago</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">$99</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-circle avatar-xs me-2">
                                            <img src="https://d33wubrfki0l68.cloudfront.net/5e2b51ec857b6e9866574263391803f159c8081e/29577/assets/images/profiles/profile-02.jpeg" alt="..." class="avatar-img" width="30" height="30" />
                                        </div>

                                        <div class="d-flex flex-column">
                                            <span class="fw-bold d-block">Gabriella Fletcher</span>
                                            <span class="fs-6 text-muted">3 hours ago</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">$59</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-circle avatar-xs me-2">
                                            <img src="https://d33wubrfki0l68.cloudfront.net/4b8c918c73e2c72876e4bd4ba8c89401bae69d14/5923c/assets/images/profiles/profile-03.jpeg" alt="..." class="avatar-img" width="30" height="30" />
                                        </div>

                                        <div class="d-flex flex-column">
                                            <span class="fw-bold d-block">Marcia Banks</span>
                                            <span class="fs-6 text-muted">9 hours ago</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">$499</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-circle avatar-xs me-2">
                                            <img src="https://d33wubrfki0l68.cloudfront.net/eec1f115f0af81936bbe3a4f4a4d043cd3c0e7e4/34439/assets/images/profiles/profile-09.jpeg" alt="..." class="avatar-img" width="30" height="30" />
                                        </div>

                                        <div class="d-flex flex-column">
                                            <span class="fw-bold d-block">Irina Garcia</span>
                                            <span class="fs-6 text-muted">17 hours ago</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">$149</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-circle avatar-xs me-2">
                                            <img src="https://d33wubrfki0l68.cloudfront.net/102e41d9e1988e0849ecfe402b1d46f4efd3574b/8dc2e/assets/images/profiles/profile-12.jpeg" alt="..." class="avatar-img" width="30" height="30" />
                                        </div>

                                        <div class="d-flex flex-column">
                                            <span class="fw-bold d-block">Javier Griffin</span>
                                            <span class="fs-6 text-muted">1 day ago</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">$125</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col d-flex">
        <!-- Card -->
        <div class="card border-0 flex-fill w-100">
            <div class="card-header border-0 card-header-space-between">
                <!-- Title -->
                <h2 class="card-header-title h4 text-uppercase">
                    Gráfico de ventas
                </h2>

                <ul class="nav" role="tablist">
                    <li class="nav-item" data-toggle="chart" data-target="#salesReportChart" data-dataset="0" role="presentation">
                        <a class="nav-link active chart-legend" href="#" data-bs-toggle="tab" aria-selected="true" role="tab">
                            <span class="legend-circle-lg bg-primary"></span>
                            Income
                        </a>
                    </li>
                    <li class="nav-item" data-toggle="chart" data-target="#salesReportChart" data-dataset="1" role="presentation">
                        <a class="nav-link chart-legend" href="#" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                            <span class="legend-circle-lg bg-dark"></span>
                            Expense
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body d-flex flex-column">
                <!-- Chart -->
                <div class="chart-container flex-grow-1 h-275px">
                    <canvas id="salesReportChart" width="1185" height="275" style="
                      display: block;
                      box-sizing: border-box;
                      height: 275px;
                      width: 1185px;
                    "></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/functions/indexCharts.js"></script>