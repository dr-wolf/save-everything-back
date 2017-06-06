<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 01.06.17
 * Time: 17:03
 */

namespace Managers;

use Models\Dataset;
use Models\Post;
use Utils\FileJson;
use Utils\GuidGenerator;
use Utils\PathGenerator;

class PostManager
{

    public function create($metadata, string $datasetGuid): Post
    {
        $guid = GuidGenerator::postGuid($datasetGuid);

        $dataset = new Dataset($datasetGuid);
        $dataset->addPostGuid($guid);
        $dataset->save();

        mkdir(PathGenerator::makePostPublicPath($guid, $datasetGuid));
        touch(PathGenerator::makePostMetaPath($guid, $datasetGuid));
        FileJson::save(PathGenerator::makePostPublicPath($guid, $datasetGuid) . '/metadata.json', $metadata);

        return new Post($guid, $datasetGuid);
    }

    public function update($metadata, string $postGuid, string $datasetGuid): Post
    {
        $post = new Post($postGuid, $datasetGuid);
        $post->setMetadata($metadata);
        $post->save($datasetGuid);
        return $post;
    }

    public function delete(string $postGuid, string $datasetGuid)
    {
        $post = new Post($postGuid, $datasetGuid);
        foreach ($post->getFileGuids() as $file) {
            @unlink(PathGenerator::makeFilePath($file, $postGuid, $datasetGuid));
        }
        @unlink(PathGenerator::makePostPublicPath($postGuid, $datasetGuid) . '/metadata.json');
        @rmdir(PathGenerator::makePostPublicPath($postGuid, $datasetGuid));
        @unlink(PathGenerator::makePostMetaPath($postGuid, $datasetGuid));

        $dataset = new Dataset($datasetGuid);
        $dataset->deletePostGuid($postGuid);
        $dataset->save();
    }

}