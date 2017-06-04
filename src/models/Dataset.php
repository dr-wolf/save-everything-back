<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 03.06.17
 * Time: 14:21
 */

namespace Models;

use Exception;
use Iterator;
use JsonSerializable;
use Utils\FileList;
use Utils\PathGenerator;

class Dataset  implements JsonSerializable
{
    private $guid;
    private $posts = array();

    /**
     * Dataset constructor.
     * @param string $guid
     * @throws Exception
     */
    public function __construct(string $guid)
    {
        $this->guid = $guid;
        $path = PathGenerator::makeDatasetMetaPath($guid) . '/meta';
        if (file_exists($path)) {
            $this->posts = FileList::load($path);
        } else {
            throw new Exception("Dataset $guid does not exists", 404);
        }
    }

    public function save()
    {
        $path = PathGenerator::makeDatasetMetaPath($this->guid) . '/meta.json';
        FileList::save($path, $this->posts);
    }

    public function jsonSerialize()
    {
        return array(
            'guid' => $this->guid,
            'posts' => $this->posts
        );
    }

    /**
     * @param string $guid
     * @return bool
     */
    public function hasPostGuid(string $guid): bool
    {
        return in_array($guid, $this->posts);
    }

    /**
     * @param string $guid
     */
    public function addPostGuid(string $guid)
    {
        $this->posts[] = $guid;
    }

    /**
     * @param string $guid
     */
    public function deletePostGuid(string $guid)
    {
        $this->posts = array_diff($this->posts, array($guid));
    }

    /**
     * @return int
     */
    public function postCount(): int
    {
        return count($this->posts);
    }

}