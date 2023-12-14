<?php

class Suppliers
{
    public function getAllSuppliers()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.suppliers AS supp
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

}
