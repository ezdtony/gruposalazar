<?php

class stockSubsidiary 
{
    public function getAllSuppliers()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.suppliers AS supp
        WHERE active_supplier = 1
        ";

        $getSuppliers = $queries->getData($sql_colabs);

        return ($getSuppliers);
    }
    public function getAllBrands()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.brands AS br
        ";

        $getSuppliers = $queries->getData($sql_colabs);

        return ($getSuppliers);
    }

    public function getAllStates()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.estados AS states
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getAllSubsidiary()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.subsidiary AS sbs
        ORDER BY subsidiary_name
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }

}
