<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 03.06.17
 * Time: 18:35
 */

namespace Utils;

class FileList
{
    /**
     * @param string $path
     * @return array
     */
    static public function load(string $path)
    {
        $files = explode(PHP_EOL, file_get_contents($path));
        return array_diff($files, array(''));
    }

    /**
     * @param string $path
     * @param $files
     */
    static public function save(string $path, $files)
    {
        file_put_contents($path, implode(PHP_EOL, $files));
    }
}