<?php

class IndexModel
{
    public function getSales()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.orders
        ";

        $getSites = $queries->getData($sql_colabs);
        $totalSales = count($getSites);

        return ($totalSales );
    }
    public function getTotalAmmounSales()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT SUM(ammount) AS total_sales
        FROM u803991314_main.orders
        ";

        $getSites = $queries->getData($sql_colabs);
        $totalSales = round($getSites[0]->total_sales, 2);

        return ($totalSales );
    }

    public function getTodaySales()
    {
        $today = date('Y-m-d');
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.orders WHERE DATE(order_date) = '$today'
        ";

        $getSites = $queries->getData($sql_colabs);
        $totalSales = count($getSites);

        return ($totalSales );
    }
    public function getMonthSales()
    {
        $month = date('m');
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.orders WHERE MONTH(order_date) = '$month'
        ";

        $getSites = $queries->getData($sql_colabs);
        $totalSales = count($getSites);

        return ($totalSales );
    }

    public function getClients()
    {
        $month = date('m');
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.clients WHERE status = 1
        ";

        $getSites = $queries->getData($sql_colabs);
        $totalSales = count($getSites);

        return ($totalSales );
    }

    public function getProfits()
    {
        $month = date('m');
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT sum(ammount)
        FROM u803991314_main.orders AS ords
        WHERE id_orders_status_types= 1
        ";

        $getSites = $queries->getData($sql_colabs);
        $totalSales = count($getSites);

        return ($totalSales );
    }

    public function getLastSales()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT DATE(order_date) as order_date_simple, subsidiary_name, ord.* 
        FROM u803991314_main.orders AS ord
        INNER JOIN u803991314_main.subsidiary AS subs ON ord.id_subsidiary = subs.id_subsidiary
        ORDER BY id_orders DESC
        LIMIT 5
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getTopSales()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT SUM(quantity*ord_det.price) as ammount_prod, SUM(quantity) as quantity, prds.product_name, ord_det.price
        FROM u803991314_main.order_details AS ord_det
        INNER JOIN u803991314_main.products AS prds ON prds.id_prducts = ord_det.id_prducts
        GROUP BY ord_det.id_prducts
        ORDER BY quantity DESC
        LIMIT 10
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
}