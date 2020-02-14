<?php
declare(strict_types=1);

namespace PcComponentes\Migration\MongoDB;

abstract class MongoDBIndexMigration extends MongoDBMigration
{
    private const DEFAULT_OPTIONS = [
        'background' => true,
    ];

    abstract protected function indexes(): array;

    final public function upOperation(): void
    {
        foreach ($this->indexes() as $collectionName => $indexes) {
            $this->createIndexesForCollection($collectionName, $indexes);
        }
    }

    final public function downOperation(): void
    {
        foreach ($this->indexes() as $collectionName => $indexes) {
            $this->dropIndexesForCollection($collectionName, $indexes);
        }
    }

    private function createIndexesForCollection(string $collectionName, array $indexes): void
    {
        $collection = $this->getCollection($collectionName);
        foreach ($indexes as $index) {
            $this->assertCorrectDefinition($collectionName, $index);

            $options = \array_merge(
                self::DEFAULT_OPTIONS,
                \array_key_exists('options', $index)
                    ? $index['options']
                    : []
            );

            $collection->createIndex($index['keys'], $options);
        }
    }

    private function dropIndexesForCollection(string $collectionName, array $indexes): void
    {
        $collection = $this->getCollection($collectionName);
        foreach ($indexes as $index) {
            $this->assertCorrectDefinition($collectionName, $index);
            $collection->dropIndex($index['keys']);
        }
    }

    private function assertCorrectDefinition(string $collectionName, array $index): void
    {
        if (false === \array_key_exists('keys', $index)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'You must specify the [keys] value for the [%s] collection index',
                    $collectionName
                )
            );
        }
    }
}
