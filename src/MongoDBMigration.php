<?php
declare(strict_types=1);

namespace PcComponentes\Migration\MongoDB;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use PcComponentes\Migration\Migration;

abstract class MongoDBMigration implements Migration
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function client(): Client
    {
        return $this->client;
    }

    abstract protected function databaseName(): string;

    final protected function getDatabase(): Database
    {
        return $this->client->selectDatabase(
            $this->databaseName()
        );
    }

    final protected function getCollection(string $collectionName): Collection
    {
        $databaseName = $this->databaseName();

        return $this->client->selectDatabase($databaseName)->selectCollection($collectionName);
    }
}
