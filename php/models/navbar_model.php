<?php

class Navbar
{
    public function getCategories()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT DISTINCT
        cat.*
        FROM u803991314_main.categories AS cat
        INNER JOIN u803991314_main.relationship_products_categories AS rpc ON rpc.id_categories = cat.id_categories
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getBrands()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT DISTINCT
        br.*
        FROM u803991314_main.brands AS br
        INNER JOIN u803991314_main.products AS prd ON prd.id_brands = br.id_brands
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
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
