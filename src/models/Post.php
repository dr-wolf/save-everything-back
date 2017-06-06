<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 03.06.17
 * Time: 18:23
 */

namespace Models;

use Exception;
use JsonSerializable;
use Utils\FileJson;
use Utils\FileList;
use Utils\PathGenerator;

class Post implements JsonSerializable
{
    private $guid;
    private $metadata;
    private $files;

    /**
     * Post constructor.
     * @param string $guid
     * @param string $datasetGuid
     * @throws Exception
     */
    public function __construct(string $guid, string $datasetGuid)
    {
        $this->guid = $guid;
        $files_path = PathGenerator::makePostMetaPath($guid, $datasetGuid);
        $meta_path = PathGenerator::makePostPublicPath($guid, $datasetGuid) . '/metadata.json';
        if (file_exists($files_path) && file_exists($meta_path)) {
            $this->metadata = FileJson::load($meta_path);
            $this->files = FileList::load($files_path);
        } else {
            throw new Exception("Post $guid does not exists", 404);
        }
    }

    public function save(string $datasetGuid)
    {
        FileJson::save(PathGenerator::makePostPublicPath($this->guid, $datasetGuid) . '/metadata.json', $this->metadata);
        FileList::save(PathGenerator::makePostMetaPath($this->guid, $datasetGuid), $this->files);
    }

    public function jsonSerialize()
    {
        return array(
            'guid' => $this->guid,
            'metadata' => $this->metadata,
            'files' => $this->files
        );
    }

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param mixed $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    public function getFileGuids() {
        return array_values($this->files);
    }

}