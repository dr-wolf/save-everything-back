<?php
/**
 * Created by PhpStorm.
 * User: otto
 * Date: 03.06.17
 * Time: 18:35
 */

namespace Utils;


class FileList
{
    static public function load($path)
    {
        $files = explode(PHP_EOL, file_get_contents($path));
        return array_diff($files, array(''));
    }

    static public function save($path, $files)
    {
        file_put_contents($path, implode(PHP_EOL, $files));
    }
}