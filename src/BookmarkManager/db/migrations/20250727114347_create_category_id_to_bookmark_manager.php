<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoryIdToBookmarkManager extends AbstractMigration
{
    public function change(): void
    {
        $this->table('bm_bookmark')
            ->addForeignKey('category_id', 'bm_category', 'id', array(
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ))
            ->update();
    }
}
