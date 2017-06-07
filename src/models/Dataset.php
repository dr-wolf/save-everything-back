<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 03.06.17
 * Time: 14:21
 */

namespace Models;

use Exception;
use JsonSerializable;
use Utils\{ FileList, PathGenerator };

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
        $path = PathGenerator::makeDatasetMetaPath($this->guid) . '/meta';
        FileList::save($path, $this->posts);
    }

    public function jsonSerialize()
    {
        return array(
            'guid' => $this->guid,
            'posts' => array_map(function ($p) {
                return '/'.$this->guid.'/'.$p;
            }, $this->posts)
        );
    }

    /**
     * @param string $uid
     * @return bool
     */
    public function hasPostUid(string $uid): bool
    {
        return in_array($uid, $this->posts);
    }

    /**
     * @param string $uid
     */
    public function addPostUid(string $uid)
    {
        $this->posts[] = $uid;
    }

    /**
     * @param string $uid
     */
    public function deletePostUid(string $uid)
    {
        $this->posts = array_diff($this->posts, array($uid));
    }

    /**
     * @return int
     */
    public function postCount(): int
    {
        return count($this->posts);
    }

}