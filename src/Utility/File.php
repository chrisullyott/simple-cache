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
        $parts = func_get_args();

        $s = DIRECTORY_SEPARATOR;

        $path = rtrim(array_shift($parts), $s) . $s;

        foreach ($parts as $p) {
            $path .= trim($p, $s) . $s;
        }

        return rtrim($path, $s);
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
     * Write a string to a file and use locking.
     *
     * @param  string  $path     The path to the file
     * @param  mixed   $contents The contents for the file
     * @param  string  $mode     The write mode to use
     * @return boolean           Whether the file is closed
     */
    public static function writeWithLock($path, $contents, $mode = 'w')
    {
        $handle = fopen($path, $mode);

        if (flock($handle, LOCK_EX)) {
            fwrite($handle, $contents);
            fflush($handle);
            flock($handle, LOCK_UN);
        }

        return fclose($handle);
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

    /**
     * Generate a random filename which isn't already taken in a directory.
     *
     * @param  string $dir The directory path
     * @return string
     */
    public static function availablePath($dir)
    {
        do {
            $name = self::randomString();
            $path = self::path($dir, $name);
        } while (file_exists($path));

        return $path;
    }

    /**
     * Generate a random string using letters and numbers.
     *
     * @param  integer $length The length of the string
     * @return string
     */
    private static function randomString($length = 32)
    {
        $string = '';

        $characters = array_merge(
            range('A', 'Z'),
            range('a', 'z'),
            range(0, 9)
        );

        for ($i = 0; $i < $length; $i++) {
            $key = array_rand($characters);
            $string .= $characters[$key];
        }

        return $string;
    }

}
