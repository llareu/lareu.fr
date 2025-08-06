<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBmCategoryTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('bm_category', ['signed' => true])
        ->addColumn('title', 'string', ['limit'=> 60, 'null' => false ],)
        ->addColumn('user_uuid', 'string', ['limit'=> 255, 'null' => false ])
        ->addColumn('updated_at', 'datetime')
        ->addColumn('created_at', 'datetime')
        ->create();
    }
}
