<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateNewsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('bg_news', ['signed' => true])
        ->addColumn('title', 'string', ['limit'=> 255, 'null' => false ],)
        ->addColumn('slug', 'string', ['limit'=> 255, 'null' => false ])
        ->addColumn('content', 'text', ['limit'=> MysqlAdapter::TEXT_LONG, 'null' => false ])
        ->addColumn('category_id', 'integer', ['signed' => true, 'null' => false])
        ->addColumn('updated_at', 'datetime')
        ->addColumn('created_at', 'datetime')
        ->create();
    }
}
