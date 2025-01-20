<?php

class Colabs
{
    public function getAllColabs()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.colaborators AS colabs
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }

    public function getAllClients()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.clients AS colabs WHERE status = 1
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getCreditsDetailPays()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT cpd.*, cdl.deadline_description,
        UPPER(CONCAT(cli.name, ' ', cli.lastname)) AS client_name
        FROM u803991314_main.credit_purchase_detail AS cpd
        INNER JOIN u803991314_main.credit_purchases AS cps ON cpd.id_credit_purchases = cps.id_credit_purchases
        INNER JOIN u803991314_main.credits_deadlines AS cdl ON cdl.id_credits_deadlines = cps.id_credits_deadlines
        INNER JOIN u803991314_main.clients AS cli ON cli.id_clients = cps.id_clients
        ORDER BY cpd.payment_date DESC
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function updatePaymentDet($id_credit_purchase_detail, $value)
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "UPDATE u803991314_main.credit_purchase_detail SET payment_status = $value WHERE id_credit_purchase_detail = $id_credit_purchase_detail";
        $queries->InsertData($sql_colabs);
    }
    public function getAllClientsCredits()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT UPPER(CONCAT(cl.name, ' ', cl.lastname)) AS client_name, cr.*
        FROM u803991314_main.clients_credits AS cr
        INNER JOIN u803991314_main.clients AS cl ON cl.id_clients = cr.id_clients
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getAllOffers()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.offers 
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getAllTags()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.tags
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
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
    public function getSubsidiary()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.subsidiary AS subs
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
    public function getPositions()
    {
        include_once('php/models/petitions.php');
        $queries = new Queries;
        $sql_colabs = "SELECT *
        FROM u803991314_main.user_profiles AS prof
        ";

        $getSites = $queries->getData($sql_colabs);

        return ($getSites);
    }
}
