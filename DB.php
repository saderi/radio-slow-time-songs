<?php

$DB = new DB(
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME
);


class DB 
{
	
	protected $DB_HOST;
	protected $DB_USER;
    protected $DB_PASS;
    protected $DB_NAME;
	protected $connection;

    /**
     * Constructor
     * Set up mysql list.
     *
     * @param string $DB_HOST MySQL hostname
     * @param string $DB_NAME The name of the database
     * @param string $DB_USER MySQL database username
     * @param string $DB_PASS MySQL database password
     */
    function __construct($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME) {
        $this->DB_HOST = $DB_HOST;
        $this->DB_USER = $DB_USER;
        $this->DB_PASS = $DB_PASS;
        $this->DB_NAME = $DB_NAME;
    }

    function connection()
    {
        $connection = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_NAME);
        // Check connection
        if ($connection->connect_error) {
            return false;
        }
        return $connection;
    }

    function get_results($query)
    {
        $connection = $this->connection();
        return $connection->query($query);
    }

}
