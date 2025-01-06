<?php

class Products
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

    public function getAllCategories()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT DISTINCT cat.id_categories, cat.categories_description, image_url
        FROM u803991314_main.categories AS cat
        INNER JOIN u803991314_main.relationship_products_categories AS rel_cat ON rel_cat.id_categories = cat.id_categories
        INNER JOIN u803991314_main.products AS prod ON prod.id_prducts = rel_cat.id_prducts
        WHERE active_item = 1
        ORDER BY cat.categories_description
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getAllBrands()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT DISTINCT bra.*
        FROM u803991314_main.products AS prod
        INNER JOIN u803991314_main.brands AS bra ON bra.id_brands = prod.id_brands
        WHERE active_item = 1 
        AND bra.id_brands != 6
        AND bra.id_brands != 9
        AND bra.id_brands != 10
        AND bra.id_brands != 11
        AND bra.id_brands != 7
        AND bra.id_brands != 3
        ORDER BY bra.brand ASC
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }

    public function getBrand($id_brand)
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT DISTINCT bra.*
        FROM u803991314_main.products AS prod
        INNER JOIN u803991314_main.brands AS bra ON bra.id_brands = prod.id_brands
        WHERE active_item = 1 
        AND bra.id_brands != 6
        AND bra.id_brands != 9
        AND bra.id_brands != 10
        AND bra.id_brands != 11
        AND bra.id_brands != 7
        AND bra.id_brands != 3
        AND bra.id_brands = $id_brand
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getCategory($id_category)
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT DISTINCT cat.id_categories, cat.categories_description, image_url
        FROM u803991314_main.categories AS cat
        INNER JOIN u803991314_main.relationship_products_categories AS rel_cat ON rel_cat.id_categories = cat.id_categories
        INNER JOIN u803991314_main.products AS prod ON prod.id_prducts = rel_cat.id_prducts
        WHERE active_item = 1
        AND cat.id_categories = $id_category
        ORDER BY cat.categories_description
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }

    public function getTopProducts()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql = "SELECT 
                SUM(ord_det.quantity) AS total_comprados,  -- Sumar las cantidades compradas
                prods.*, 
                br.brand,
                CASE 
                    WHEN sb_stk.prducts_id_prducts = prods.id_prducts THEN SUM(sb_stk.stock)
                    ELSE 0
                END AS total_stock
            FROM u803991314_main.products AS prods
            INNER JOIN u803991314_main.brands AS br ON br.id_brands = prods.id_brands
            LEFT JOIN u803991314_main.subsidiary_stocks AS sb_stk ON sb_stk.prducts_id_prducts = prods.id_prducts
            INNER JOIN u803991314_main.relationship_products_categories AS rpc ON rpc.id_prducts = prods.id_prducts
            INNER JOIN u803991314_main.categories AS ct ON ct.id_categories = rpc.id_categories
            INNER JOIN u803991314_main.order_details AS ord_det ON ord_det.id_prducts = prods.id_prducts
            GROUP BY prods.id_prducts
            ORDER BY total_comprados DESC
            LIMIT 25
        ";

        $getData = $queries->getData($sql);

        return $getData;
    }

    public function getProductDiscount($id_product)
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql = "SELECT DISTINCT percentage, offer_name
    FROM u803991314_main.products AS prods
    INNER JOIN u803991314_main.relationship_products_tags AS rpt ON rpt.id_prducts = prods.id_prducts
    INNER JOIN u803991314_main.relationship_offers_tags AS rot ON rpt.id_tags = rot.id_tags
    INNER JOIN u803991314_main.offers AS offr ON offr.id_offers = rot.id_offers
    WHERE prods.id_prducts = $id_product
        ";

        $getData = $queries->getData($sql);

        return $getData;
    }

    public function getAllSubsidiaryAdress()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT sbs.*, adss.url_google_maps, adss.see_on_gmaps,
        CONCAT(street, ' #', ext_number, ', Col. ', colony, ', ', delegation, ' C.P. ', postal_code, ', ', state ) AS address_subs
        FROM u803991314_main.subsidiary AS sbs
        INNER JOIN u803991314_main.subsidiary_address AS adss ON adss.id_subsidiary_address = sbs.id_subsidiary_address
        ORDER BY subsidiary_name
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
}
