<?php

namespace Framework\Base\Model;

use Framework\Base\Database\DatabaseAdapterInterface;

/**
 * Interface BrunoInterface
 * @package Framework\Base\Model
 */
interface BrunoInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return bool
     */
    public function isNew();

    /**
     * @param bool $flag
     * @return BrunoInterface
     */
    public function setIsNew(bool $flag = true);

    /**
     * @return BrunoInterface
     */
    public function save();

    /**
     * @param DatabaseAdapterInterface $adapter
     * @return BrunoInterface
     */
    public function setDatabaseAdapter(DatabaseAdapterInterface $adapter);

    /**
     * @return DatabaseAdapterInterface|null
     */
    public function getDatabaseAdapter();

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param array $attributes
     * @return BrunoInterface
     */
    public function setAttributes(array $attributes = []);

    /**
     * @param string $attribute
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute(string $attribute, $value);

    /**
     * @return array
     */
    public function getDirtyAttributes();

    /**
     * @param array $attributes
     * @return BrunoInterface
     */
    public function setDatabaseAttributes(array $attributes = []);

    /**
     * @return array
     */
    public function getDatabaseAttributes();

    /**
     * @return string
     */
    public function getDatabase();

    /**
     * @param string $collectionName
     * @return mixed
     */
    public function setCollection(string $collectionName);

    /**
     * @return string
     */
    public function getCollection();
}