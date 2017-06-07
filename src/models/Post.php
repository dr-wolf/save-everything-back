<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 03.06.17
 * Time: 18:23
 */

namespace Models;

use Exception;
use JsonSerializable;
use Utils\{ FileJson, FileList, PathGenerator };

class Post implements JsonSerializable
{
    public static $METADATA_FILENAME = 'metadata.json';

    private $uid;
    private $datasetGuid;
    private $metadata = null;
    private $files = array();

    /**
     * Post constructor.
     * @param string $uid
     * @param string $datasetGuid
     * @throws Exception
     */
    public function __construct(string $uid, string $datasetGuid)
    {
        $this->uid = $uid;
        $this->datasetGuid = $datasetGuid;
        $files_path = PathGenerator::makePostMetaPath($uid, $datasetGuid);
        $meta_path = PathGenerator::makePostPublicPath($uid, $datasetGuid).'/'.self::$METADATA_FILENAME;
        if (file_exists($files_path) && file_exists($meta_path)) {
            $this->metadata = FileJson::load($meta_path);
            $this->files = FileList::load($files_path);
        } else {
            throw new Exception("Post $uid does not exists", 404);
        }
    }

    public function save()
    {
        FileJson::save(PathGenerator::makePostPublicPath($this->uid, $this->datasetGuid).'/'.self::$METADATA_FILENAME, $this->metadata);
        FileList::save(PathGenerator::makePostMetaPath($this->uid, $this->datasetGuid), $this->files);
    }

    public function jsonSerialize()
    {
        return array(
            'uid' => $this->uid,
            'metadata' => $this->metadata,
            'files' => array_map(function($f) {
                return '/'.$this->datasetGuid.'/'.$this->uid.'/'.$f;
            }, $this->files)
        );
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
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

    public function getFiles()
    {
        return array_values($this->files);
    }

    public function hasFile(string $filename): bool
    {
        return in_array($filename, $this->files);
    }

    public function addFile(string $filename)
    {
        if (!$this->hasFile($filename)) {
            $this->files[] = $filename;
        } else {
            throw new Exception("File $filename already exists in post $this->uid", 400);
        }
    }

    public function deleteFile(string $filename)
    {
        $this->files = array_diff($this->files, array($filename));
    }

}