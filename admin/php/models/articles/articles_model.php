<?php

class Articles
{
    public function getAllArticles()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.products AS prods
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
