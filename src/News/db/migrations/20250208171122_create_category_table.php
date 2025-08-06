<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoryTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('bg_category', ['signed' => true])
            ->addColumn('title', 'string')
            ->addColumn('slug', 'string')
            ->addIndex('slug', ['unique' => true])
            ->create();
    }
}
