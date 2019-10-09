<?php

/**
 * Methods for the local filesystem.
 */

namespace ChrisUllyott\Utility;

class File
{
    /**
     * Build a full path from parts passed as arguments.
     *
     * @return string
     */
    public static function path()
    {
        $s = DIRECTORY_SEPARATOR;
        $path = implode($s, func_get_args());
        $pattern = '/' . preg_quote($s, $s) . '{2,}/';

        return preg_replace($pattern, $s, rtrim($path, $s));
    }

    /**
     * Read a file, return null if unreadable.
     *
     * @param  string $path The path to the file
     * @return mixed
     */
    public static function read($path)
    {
        if (is_readable($path)) {
            return file_get_contents($path);
        }

        return null;
    }

    /**
     * Write a string to a file, return boolean for success.
     *
     * @param  string  $path     The path to the file
     * @param  mixed   $contents The contents for the file
     * @param  integer $flags    Any flags available to file_put_contents()
     * @return boolean           Whether the file was written
     */
    public static function write($path, $contents, $flags = null)
    {
        return file_put_contents($path, $contents, $flags) !== false;
    }

    /**
     * Create a directory if it doesn't exist.
     *
     * @param  integer $permissions The permissions octal
     * @return boolean              Whether the directory exists or was created
     */
    public static function createDir($path, $permissions = 0755)
    {
        if (!is_dir($path)) {
            return mkdir($path, $permissions, true);
        }

        return true;
    }

    /**
     * List all the files in a directory, even hidden ones.
     *
     * @param  string  $dir       The path of a directory
     * @param  boolean $recursive Whether to list recursively
     * @return array              The listed files
     */
    public static function listDir($dir, $recursive = false)
    {
        $files = [];

        $glob = glob(self::path($dir, '/{,.}*'), GLOB_BRACE);

        foreach ($glob as $path) {
            if ($recursive && is_dir($path)) {
                $files = array_merge($files, self::listDir($path, $recursive));
            } elseif (is_file($path)) {
                $files[] = $path;
            }
        }

        return $files;
    }

    /**
     * Delete a directory.
     *
     * @param  string  $dir The path of a directory
     * @return boolean
     */
    public static function deleteDir($dir)
    {
        $files = self::listDir($dir);

        foreach ($files as $file) {
            unlink($file);
        }

        return rmdir($dir);
    }
}
