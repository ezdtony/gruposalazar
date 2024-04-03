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
}