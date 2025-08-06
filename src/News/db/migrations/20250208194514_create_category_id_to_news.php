<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoryIdToNews extends AbstractMigration
{
    public function change(): void
    {
        $this->table('bg_news')
            ->addForeignKey('category_id', 'bg_category', 'id', array(
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ))
            ->update();
    }
}
