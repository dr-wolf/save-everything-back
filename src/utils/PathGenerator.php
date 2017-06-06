<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 03.06.17
 * Time: 15:35
 */

namespace Utils;


class PathGenerator
{

    private static $meta = '/../../datasets/';
    private static $public = '/../../public/';

    /**
     * @param string $guid
     * @return string
     */
    public static function makeDatasetPublicPath(string $guid): string
    {
        return __DIR__ . self::$public . $guid;
    }

    /**
     * @param string $guid
     * @return string
     */
    public static function makeDatasetMetaPath(string $guid): string
    {
        return __DIR__ . self::$meta . $guid;
    }

    /**
     * @param string $guid
     * @param string $datasetGuid
     * @return string
     */
    public static function makePostPublicPath(string $guid, string $datasetGuid): string
    {
        return __DIR__ . self::$public . $datasetGuid . '/' . $guid;
    }

    /**
     * @param string $guid
     * @param string $datasetGuid
     * @return string
     */
    public static function makePostMetaPath(string $guid, string $datasetGuid): string
    {
        return __DIR__ . self::$meta . $datasetGuid . '/' . $guid;
    }

    /**
     * @param string $guid
     * @param string $postGuid
     * @param string $datasetGuid
     * @return string
     */
    public static function makeFilePath(string $guid, string $postGuid, string $datasetGuid): string
    {
        return __DIR__ . self::$public . $datasetGuid . '/' . $postGuid . '/' . $guid;
    }
}