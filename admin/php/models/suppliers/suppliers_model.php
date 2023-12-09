<?php

class Suppliers
{
    public function getAllArticles()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT 
        CASE 
            WHEN sb_stk.prducts_id_prducts = prods.id_prducts THEN SUM(sb_stk.stock)
            ELSE 0
        END
        AS total_stock, 
        prods.*
        FROM u803991314_main.products AS prods
        LEFT JOIN u803991314_main.subsidiary_stocks AS sb_stk ON sb_stk.prducts_id_prducts = prods.id_prducts
        WHERE active_item = 1
        GROUP BY prods.id_prducts
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }

    public function getAllBrands()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.brands AS states
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getAllMU()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.measurement_units AS mu
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
}
