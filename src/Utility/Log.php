<?php

/**
 * Persists data in a JSON file.
 */

namespace ChrisUllyott\Utility;

use ChrisUllyott\Utility\File;

class Log
{
    /**
     * A path for the JSON file.
     *
     * @var string
     */
    private $file;

    /**
     * A copy of the data fetched from storage.
     *
     * @var array
     */
    private $copy = [];

    /**
     * The data in memory.
     *
     * @var array
     */
    private $data = [];

    /**
     * Constructor.
     *
     * @param string $file A path for the JSON file
     */
    public function __construct($file)
    {
        $this->file = $file;

        if (file_exists($this->file)) {
            $this->data = json_decode(File::read($this->file), true);
            $this->copy = $this->data;
        }
    }

    /**
     * Get a stored value by key.
     *
     * @param  string|integer $key The item key
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Get all stored data.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Persist a value by key.
     *
     * @param string|integer $key   The key to use
     * @param string|integer $value The value to store
     * @return self
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Define the entire dataset.
     *
     * @param  array $array The array of data to store
     * @return self
     */
    public function setAll(array $array)
    {
        $this->data = $array;

        return $this;
    }

    /**
     * Merge an array into the existing dataset.
     *
     * @param  array $array The array of data to store
     * @return self
     */
    public function merge(array $array)
    {
       $this->data = array_merge($this->data, $array);

       return $this;
    }

    /**
     * Delete an item by key.
     *
     * @param string|integer $key The key to use
     * @return self
     */
    public function delete($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }

        return $this;
    }

    /**
     * Whether the data has been modified.
     *
     * @return bool
     */
    public function hasChanged()
    {
        return serialize($this->data) !== serialize($this->copy);
    }

    /**
     * Save the data to disk.
     */
    public function __destruct()
    {
        if ($this->hasChanged()) {
            File::createDir(dirname($this->file));
            File::write($this->file, json_encode($this->data));
        }
    }
}
