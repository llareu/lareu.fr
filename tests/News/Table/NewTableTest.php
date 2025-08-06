<?php

namespace Tests\Core\News\Table;

use Core\Database\NoRecordException;
use Root\News\Entity\NewsEntity;
use Root\News\Table\NewTable;
use Tests\DatabaseTestCase;

class NewTableTest extends DatabaseTestCase
{

    /**
     * @var NewTable
     */
    private $newTable;

    public function setUp(): void
    {
        $pdo = $this->getPDO();
        $this->migrateDatabase($pdo);
        $this->newTable = new NewTable($pdo);
    }

    public function testFind()
    {
        $this->seedDatabase($this->newTable->getPDO());
        $news = $this->newTable->find(1);
        $this->assertInstanceOf(NewsEntity::class, $news);
    }

    public function testFindNotFoundRecord()
    {
        $this->expectException(NoRecordException::class);
        $this->newTable->find(100);
    }

    public function testUpdate()
    {
        $this->seedDatabase($this->newTable->getPDO());
        $this->newTable->update(1, [
            'title' => 'Salut les gens !',
            'slug' => 'demo'
        ]);
        $news = $this->newTable->find(1);
        $this->assertEquals('Salut les gens !', $news->title);
        $this->assertEquals('demo', $news->slug);
    }

    public function testInsert()
    {
        $this->newTable->insert([
            'title' => 'Salut',
            'slug' => 'demo',
            'category_id' => rand(1, 5),
            'content' => 'contenu'
        ]);
        $news = $this->newTable->find(1);
        $this->assertEquals('Salut', $news->title);
        $this->assertEquals('demo', $news->slug);
        $this->assertEquals('contenu', $news->content);
    }

    public function testdelete()
    {
        $this->newTable->insert([
            'title' => 'Salut',
            'slug' => 'demo',
            'category_id' => rand(1, 5),
            'content' => 'contenu'
        ]);
        $this->newTable->insert([
            'title' => 'Salut',
            'slug' => 'demo',
            'category_id' => rand(1, 5),
            'content' => 'contenu'
        ]);
        $count = $this->newTable->getPDO()->query('SELECT count(id) from bg_news')->fetchColumn();
        $this->assertEquals(2, (int) $count);
        $this->newTable->delete($this->newTable->getPDO()->lastInsertId());
        $count = $this->newTable->getPDO()->query('SELECT count(id) from bg_news')->fetchColumn();
        $this->assertEquals(1, (int) $count);
    }
}
