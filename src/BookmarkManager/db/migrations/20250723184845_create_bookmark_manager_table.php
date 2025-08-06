<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateBookmarkManagerTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('bm_bookmark', ['signed' => true])
        ->addColumn('title', 'string', ['limit'=> 60, 'null' => false ],)
        ->addColumn('link', 'text', ['limit'=> MysqlAdapter::TEXT_LONG, 'null' => false ])
        ->addColumn('category_id', 'integer', ['signed' => true, 'null' => true])
        ->addColumn('picture_title', 'string', ['limit'=> 60, 'null' => true ])
        ->addColumn('click_counter', 'integer', ['limit'=> 6, 'null' => true ])
        ->addColumn('user_uuid', 'string', ['limit'=> 255, 'null' => false ])
        ->addColumn('updated_at', 'datetime')
        ->addColumn('created_at', 'datetime')
        ->create();
    }
}
