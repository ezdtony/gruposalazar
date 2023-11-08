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
}
