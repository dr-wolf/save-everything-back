<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 02.06.17
 * Time: 17:29
 */

namespace Managers;

use Exception;
use Models\Dataset;
use Utils\{ GuidGenerator, PathGenerator };

class DatasetManager
{

    public function create(): Dataset
    {
        $guid = GuidGenerator::datasetGuid();
        mkdir(PathGenerator::makeDatasetMetaPath($guid));
        mkdir(PathGenerator::makeDatasetPublicPath($guid));
        touch(PathGenerator::makeDatasetMetaPath($guid) . '/meta');
        return new Dataset($guid);
    }

    public function delete(string $guid)
    {
        $dataset = new Dataset($guid);
        if ($dataset->postCount() == 0) {
            unlink(PathGenerator::makeDatasetMetaPath($guid). '/meta');
            rmdir(PathGenerator::makeDatasetMetaPath($guid));
            rmdir(PathGenerator::makeDatasetPublicPath($guid));
        } else {
            throw new Exception("Dataset $guid is not empty", 400);
        }
    }

}