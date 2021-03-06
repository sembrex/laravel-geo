<?php

namespace Karomap\GeoLaravel\Database\Connectors;

use Illuminate\Database\Connectors\ConnectionFactory as IlluminateConnectionFactory;
use Karomap\GeoLaravel\Database\MySqlConnection;
use Karomap\GeoLaravel\Database\PostgresConnection;

class ConnectionFactory extends IlluminateConnectionFactory
{
    /**
     * Create a new connection instance.
     *
     * @param  string                          $driver
     * @param  \PDO|\Closure                   $connection
     * @param  string                          $database
     * @param  string                          $prefix
     * @param  array                           $config
     * @throws \InvalidArgumentException
     * @return \Illuminate\Database\Connection
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);
        }

        switch ($driver) {
            case 'mysql':
                return new MySqlConnection($connection, $database, $prefix, $config);

            case 'pgsql':
                return new PostgresConnection($connection, $database, $prefix, $config);
        }

        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}
