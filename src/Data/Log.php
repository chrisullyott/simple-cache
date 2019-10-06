<?php

/**
 * Persists data in a JSON file.
 */

namespace ChrisUllyott\Data;

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
     * Constructor.
     *
     * @param string $file A path for the JSON file
     */
    public function __construct($file)
    {
        $this->file = $file;
        File::createDir(dirname($this->file));
    }

    /**
     * Get the path for the JSON file.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get a stored value by key.
     *
     * @param  string|integer $key The item key
     * @return mixed
     */
    public function get($key)
    {
        $array = $this->getAll();

        return isset($array[$key]) ? $array[$key] : null;
    }

    /**
     * Get all stored data.
     *
     * @return array
     */
    public function getAll()
    {
        $json = File::read($this->getFile());
        $array = json_decode($json, true);

        return is_array($array) ? $array : [];
    }

    /**
     * Persist a value by key.
     *
     * @param string|integer $key   The key to use
     * @param string|integer $value The value to store
     * @return self
     */
    public function set($key, $value = null)
    {
        $array = $this->getAll();
        $array[$key] = $value;
        $this->save($array);

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
        $this->save($array);

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
       $array = array_merge($this->getAll(), $array);
       $this->save($array);

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
        $array = $this->getAll();

        if (isset($array[$key])) {
            unset($array[$key]);
        }

        $this->save($array);

        return $this;
    }

    /**
     * Persist an array of data.
     *
     * @param  array $array The array of data to store
     * @return bool         Whether the file was written
     */
    private function save(array $array)
    {
        return File::write($this->getFile(), json_encode($array, JSON_PRETTY_PRINT));
    }
}
