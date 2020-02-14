<?php
declare(strict_types=1);

namespace PcComponentes\Migration\MongoDB;

use MongoDB\Driver\Exception\CommandException;

abstract class MongoDBCollectionMigration extends MongoDBMigration
{
    abstract protected function collections(): array;

    final public function upOperation(): void
    {
        foreach ($this->collections() as $collectionName) {
            $this->createCollectionIfNotExist($collectionName);
        }
    }

    final public function downOperation(): void
    {
        foreach ($this->collections() as $collectionName) {
            $this->dropCollection($collectionName);
        }
    }

    private function createCollectionIfNotExist(string $collectionName): void
    {
        try {
            $this->getDatabase()->createCollection($collectionName);
        } catch (CommandException $exception) {
            if (false === \strpos($exception->getMessage(), 'already exists')) {
                throw $exception;
            }
        }
    }

    private function dropCollection(string $collectionName): void
    {
        $this->getDatabase()->dropCollection($collectionName);
    }
}
