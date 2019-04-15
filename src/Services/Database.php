<?php

namespace PHPCodex\Framework\Services;

use PHPCodex\Framework\Exceptions\Services\Database\DatabaseDriverNotInstalledException;
use PHPCodex\Framework\Exceptions\Services\Database\DatabaseDriverNotSupportedException;
use PHPCodex\Framework\Services\Database\Drivers\MySQL;

class Database
{
    /**
     * @var array
     * This should be the full list of supported drivers,
     * the driver you wish to use should be installed.
     */
    protected $installed_drivers = [];

    /**
     * @var array
     * This is the list of supported drivers and will
     * point to the driver itself.
     */
    protected $supported_drivers = [
        'mysql'     => mysql::class
    ];

    /**
     * @var null
     * The default database we will query on unless
     * we have specified otherwise.
     */
    protected $use = null;

    /**
     * @var array
     * You could have multiple connections open and this
     * becomes the place-holder for your connections.
     */
    protected $connections = [];

    public function __construct($on = 'default')
    {
        /**
         * Once we capture a list of installed PDO Drivers
         * we can then later check to ensure the driver
         * your trying to use is available.
         */
        $this->installed_drivers = pdo_drivers();

        /**
         * Dtermine our connection name that we're trying
         * to use so we can then pull out the
         * configuration settings.
         */
        $connectionName = ($on == 'default') ? config('database.default') : $on;

        /**
         * Set our default usable database.
         */
        if ($this->use == null) {
            $this->use = $connectionName;
        }

        /**
         * Setup our connection to the database that
         * we're trying to access via the 'driver'
         * such as MySQL, PGSSQL..
         */
        $this->setup($connectionName);
    }

    private function setup($connectionName)
    {
        /**
         * Determine the driver we're using so we
         * can validate this within our pool of
         * installed and supported methods.
         */
        $driver = config('database.connection.' . $connectionName . '.driver');

        if (!in_array($driver, $this->installed_drivers)) {
            throw new DatabaseDriverNotInstalledException($driver);
        }

        if (!isset($this->supported_drivers[$driver])) {
            throw new DatabaseDriverNotSupportedException($driver);
        }

        /**
         * Set this private connection up so we
         * then run queries on this.
         */
        $this->connections[$connectionName] = new $this->supported_drivers[$driver]($connectionName);
    }

    public function query(string $query, array $parameters = [])
    {
        return $this->connections[$this->use]->query($query, $parameters);
    }
}