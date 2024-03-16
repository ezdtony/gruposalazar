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
        FROM u803991314_main.clients AS colabs
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
