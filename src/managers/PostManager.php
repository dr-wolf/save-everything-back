<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 01.06.17
 * Time: 17:03
 */

namespace Managers;

use Models\Post;

class PostManager
{

    public function create($metadata, $datasetGuid): Post
    {
        $dataset = new Dataset($datasetGuid);

        $post = $postManager->create();
        $dataset->addPostGuid($post->getGuid());
        $dataset->save();

    }

}