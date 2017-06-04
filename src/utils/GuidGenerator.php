<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 03.06.17
 * Time: 14:01
 */

namespace Utils;

class GuidGenerator
{
    /**
     * @return string
     */
    private static function makeGuidV4(): string
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @return string
     */
    public static function datasetGuid(): string
    {
        do {
            $guid = self::makeGuidV4();
        } while (file_exists(PathGenerator::makeDatasetMetaPath($guid)));
        return $guid;
    }


    /**
     * @param string $datasetGuid
     * @return string
     */
    public static function postGuid(string $datasetGuid): string
    {
        do {
            $guid = self::makeGuidV4();
        } while (file_exists(PathGenerator::makePostMetaPath($guid, $datasetGuid)));
        return $guid;
    }

}