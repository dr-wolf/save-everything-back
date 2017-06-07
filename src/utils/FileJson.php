<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 04.06.17
 * Time: 12:50
 */

namespace Utils;

class FileJson
{
    /**
     * @param string $path
     * @return mixed
     */
    static public function load(string $path)
    {
        return json_decode(file_get_contents($path), true);
    }

    /**
     * @param string $path
     * @param $data
     */
    static public function save(string $path, $data)
    {
        file_put_contents($path, json_encode($data));
    }
}