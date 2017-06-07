<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 01.06.17
 * Time: 17:03
 */

namespace Managers;

use Exception;
use Models\{ Dataset, Post };
use Utils\{ FileJson, GuidGenerator, PathGenerator };

class PostManager
{
    public function create($metadata, string $datasetGuid): Post
    {
        $guid = GuidGenerator::postGuid($datasetGuid);

        $dataset = new Dataset($datasetGuid);
        $dataset->addPostUid($guid);
        $dataset->save();

        mkdir(PathGenerator::makePostPublicPath($guid, $datasetGuid));
        touch(PathGenerator::makePostMetaPath($guid, $datasetGuid));
        FileJson::save(PathGenerator::makePostPublicPath($guid, $datasetGuid).'/'.Post::$METADATA_FILENAME, $metadata);

        return new Post($guid, $datasetGuid);
    }

    public function update($metadata, string $postUid, string $datasetGuid): Post
    {
        $post = new Post($postUid, $datasetGuid);
        $post->setMetadata($metadata);
        $post->save();
        return $post;
    }

    public function delete(string $postUid, string $datasetGuid)
    {
        $post = new Post($postUid, $datasetGuid);
        foreach ($post->getFiles() as $file) {
            @unlink(PathGenerator::makeFilePath($file, $postUid, $datasetGuid));
        }
        @unlink(PathGenerator::makePostPublicPath($postUid, $datasetGuid).'/'.Post::$METADATA_FILENAME);
        @rmdir(PathGenerator::makePostPublicPath($postUid, $datasetGuid));
        @unlink(PathGenerator::makePostMetaPath($postUid, $datasetGuid));

        $dataset = new Dataset($datasetGuid);
        $dataset->deletePostUid($postUid);
        $dataset->save();
    }

    public function uploadFile()
    {

    }

    public function saveFile(string $filename, $content, string $postUid, string $datasetGuid): Post
    {
        if ($filename == Post::$METADATA_FILENAME) {
            throw new Exception('Filename '.Post::$METADATA_FILENAME.' is reserved', 400);
        }
        $file_path = PathGenerator::makePostPublicPath($postUid, $datasetGuid) . '/' .$filename;
        $post = new Post($postUid, $datasetGuid);
        if ($post->hasFile($filename)) {
            @unlink($file_path);
        } else {
            $post->addFile($filename);
        }
        file_put_contents($file_path, $content);
        $post->save();
        return $post;
    }

    public function deleteFile(string $filename, string $postUid, string $datsetGuid): Post
    {
        $post = new Post($postUid, $datsetGuid);
        if ($post->hasFile($filename)) {
            @unlink(PathGenerator::makePostPublicPath($postUid, $datsetGuid) . '/' .$filename);
            $post->deleteFile($filename);
        }
        return $post;
    }

}