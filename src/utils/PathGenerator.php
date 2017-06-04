<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 03.06.17
 * Time: 15:35
 */

namespace Utils;


class PathGenerator
{

    private static $meta = '/../datasets/';
    private static $public = '/';

    /**
     * @param string $guid
     * @return string
     */
    public static function makeDatasetPublicPath(string $guid): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::$public . $guid;
    }

    /**
     * @param string $guid
     * @return string
     */
    public static function makeDatasetMetaPath(string $guid): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::$meta . $guid;
    }

    /**
     * @param string $guid
     * @param string $datasetGuid
     * @return string
     */
    public static function makePostPublicPath(string $guid, string $datasetGuid): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::$public . $datasetGuid . '/' . $guid;
    }

    /**
     * @param string $guid
     * @param string $datasetGuid
     * @return string
     */
    public static function makePostMetaPath(string $guid, string $datasetGuid): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::$meta . $datasetGuid . '/' . $guid;
    }
}