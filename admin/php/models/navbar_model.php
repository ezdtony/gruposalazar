<?php

class Navbar
{
    public function getAllNavbarItems()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.dashboard_items_navbar AS navit
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
}
