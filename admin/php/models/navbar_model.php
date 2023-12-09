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
    public function getNavbarSubItems($id_dashboard_items_navbar)
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM  u803991314_main.dashboard_subitems_navbar AS sub_navit
        WHERE id_dashboard_items_navbar = $id_dashboard_items_navbar
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
}
