<?php

class Articles
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

    public function getStates()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.estados AS states
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }

    public function getThirdArticles()
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
        WHERE 
        (prods.id_prducts = 1638 OR prods.id_prducts = 3467 OR prods.id_prducts = 1554 )
        GROUP BY prods.id_prducts
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getSubsidiarys()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT 
        CONCAT(street, ' #', ext_number, ', Col. ', colony, ' ', delegation, ' C.P. ', postal_code, ', ', state ) AS address_subs, 
        subs.*
        FROM u803991314_main.subsidiary AS subs
        INNER JOIN u803991314_main.subsidiary_address AS sbs_add ON sbs_add.id_subsidiary_address = subs.id_subsidiary_address
        
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
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
    public function getOffers()
    {
        $today = date('Y-m-d');
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT * 
        FROM u803991314_main.offers 
        WHERE start_date <= '$today' AND end_date >= '$today' AND id_offers > 1
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
