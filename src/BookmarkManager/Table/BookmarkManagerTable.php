<?php

namespace Root\BookmarkManager\Table;

use Core\Database\Table;
use Root\BookmarkManager\Entity\BookmarkManagerEntity;

class BookmarkManagerTable extends Table
{
    protected $entity = BookmarkManagerEntity::class;

    protected $table = 'bm_bookmark';
}
