<?php

namespace PHPCodex\Framework\Traits\Services;

use PHPCodex\Framework\Support\PDO\DatabaseResult;
use PHPCodex\Framework\Support\PDO\DatabaseResultSet;

use \PDO;
use \stdClass;

trait DatabaseConnection
{

    /**
     * @var string
     *
     * When we're trying to create a database connection,
     * we need a connection name so we can grab the
     * associated key-value pairs.
     */
    protected $connectionName;

    /**
     * @var
     *
     * This PDO object is what we will use to query on.
     */
    protected $pdo;

    /**
     * DatabaseConnection constructor.
     * @param string $connectionName
     *
     * Create our connection.
     */
    public function __construct($connectionName = 'default')
    {
        $this->connectionName = $connectionName;
        $this->create();
    }

    /**
     * @param $detail
     * @return mixed|null|string
     *
     * Our method to gather the connection data from
     * our configuration file.
     */
    public function getConnectionDetail($detail)
    {
        return config('database.connection.' . $this->connectionName . '.' . $detail) ?? '';
    }

    /**
     * @return string
     *
     * This method will generate our DSN string for PDO.
     */
    public function getConnectionString() : string
    {
        $connectionString   = $this->getConnectionDetail('driver');

        if ($connectionString == 'mysql') {

            $connectionString .= ':host=' . $this->getConnectionDetail('host');
            $connectionString .= ';port=' . $this->getConnectionDetail('port');
            $connectionString .= ';dbname=' . $this->getConnectionDetail('database');

        } elseif ($connectionString == 'sqlite') {

            $connectionString .= ':' . $this->getConnectionDetail('database');

        } elseif ($connectionString == 'odbc') {

            $connectionString .= ':Driver={SQL Server};Server=' . $this->getConnectionDetail('host');
            $connectionString .= ':Database=' . $this->getConnectionDetail('database');

        } elseif ($connectionString == 'sqlsrv') {

            //No implementation yet.

        } else {

            //Unsupported driver.

        }

        return $connectionString;
    }

    /**
     * @return PDO
     *
     * This will connect to our database and return the
     * PDO object, allowing us to query against it.
     */
    private function create()
    {
        $this->pdo = new pdo(
            $this->getConnectionString(),
            $this->getConnectionDetail('username'),
            $this->getConnectionDetail('password'),
            $this->getConnectionDetail('options')
        );
    }

    /**
     * @param string $q
     * @param array $params
     * @return mixed
     *
     * Run a query on our chosen database connection
     * and return any results found.
     */
    public function query(string $query, array $parameters = [])
    {
        $stream = $this->pdo->prepare($query);
        $stream->execute(array_values($parameters));

        $result = new DatabaseResultSet;
        $result->query = $query;
        $result->parameters = $parameters;

        while ($row = $stream->fetch(PDO::FETCH_ASSOC)) {
            $result->add(new DatabaseResult($row));
        }

        return $result;
    }
}