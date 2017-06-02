<?php
/**
 * Created by PhpStorm.
 * User: otto
 * Date: 01.06.17
 * Time: 17:03
 */

namespace DB;

class PostDB
{

    public function getAll()
    {
        return array(
            array(
                'id' => 1,
                'title' => 'post'
            ),
            array(
                'id' => 2,
                'title' => 'post2'
            )
        );
    }

}