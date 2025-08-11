<?php

namespace Tests\Core\Database;

use Core\Database\Query;
use Tests\DatabaseTestCase;

class QueryTest extends DatabaseTestCase
{
    public function testSimpleQuery() {
        $query = (new Query())
            ->from('bg_news')
            ->select('title');

        $this->assertEquals('SELECT title FROM bg_news', (string)$query);
    }

    public function testWithWhereQuery() {
        $query = (new Query())
            ->from('bg_news', 'n')
            ->where('a = :a OR b = :b', 'c = :c');

        $query2 = (new Query())
            ->from('bg_news', 'n')
            ->where('a = :a OR b = :b')
            ->where('c = :c');


        $this->assertEquals('SELECT * FROM bg_news AS n WHERE (a = :a OR b = :b) AND (c = :c)', (string)$query);
        $this->assertEquals('SELECT * FROM bg_news AS n WHERE (a = :a OR b = :b) AND (c = :c)', (string)$query2);
    }

    public function testFetchAll() {
        $pdo = $this->getPDO();
        $this->migrateDatabase($pdo);
        $this->seedDatabase($pdo);
        $news = (new Query($pdo))
            ->from('bg_news', 'n')
            ->count();
        $this->assertEquals(100, $news);
        
        $news = (new Query($pdo))
            ->from('bg_news', 'n')
            ->where('n.id < :number')
            ->params([
                'number' => 30
            ])
            ->count();
        $this->assertEquals(29, $news);
    }

    public function testHydrateEntity() {
        $pdo = $this->getPDO();
        $this->migrateDatabase($pdo);
        $this->seedDatabase($pdo);
        $news = (new Query($pdo))->from('bg_news', 'n')
        ->into(Demo::class)
        ->all();
        

        $this->assertEquals('demo', substr($news[0]->getSlug(), -4));
    }

    public function testLazyHydrate() {
        $pdo = $this->getPDO();
        $this->migrateDatabase($pdo);
        $this->seedDatabase($pdo);
        $news = (new Query($pdo))->from('bg_news', 'n')
        ->into(Demo::class)
        ->all();
        $news1 = $news[0];
        $news2 = $news[1];
        

        $this->assertSame($news1, $news2);
    }

}
