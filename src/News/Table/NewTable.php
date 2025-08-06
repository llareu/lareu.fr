<?php

namespace Root\News\Table;

use Core\Database\Table;
use Core\Database\PaginatedQuery;
use Pagerfanta\Pagerfanta;
use Root\News\Entity\NewsEntity;

class NewTable extends Table
{
    protected $entity = NewsEntity::class;

    protected $table = 'bg_news';

    public function findPaginatedPublic(int $perPage, int $curentPage): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            "SELECT n.*, c.title as category_name, c.slug as category_slug 
            FROM bg_news AS n 
            LEFT JOIN bg_category AS c ON c.id = n.category_id
            ORDER BY n.created_at DESC",
            "SELECT COUNT(id) FROM {$this->table}",
            $this->entity
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($curentPage);
    }

    public function findPaginatedPublicForCategory(int $perPage, int $curentPage, int $categoryId): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            "SELECT n.*, c.title as category_name, c.slug as category_slug 
            FROM bg_news AS n 
            LEFT JOIN bg_category AS c ON c.id = n.category_id
            WHERE n.category_id = :category
            ORDER BY n.created_at DESC",
            "SELECT COUNT(id) FROM {$this->table} WHERE category_id = :category",
            $this->entity,
            ['category' => $categoryId]
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($curentPage);
    }

    public function findWithCategory(int $id)
    {
        return $this->fetchOrFail(
            "SELECT n.*, c.title as category_name, c.slug as category_slug 
            FROM bg_news AS n 
            LEFT JOIN bg_category AS c ON c.id = n.category_id
            WHERE n.id = :id",
            ['id' => $id]
        );
    }


    protected function paginationQuery()
    {
        return "SELECT n.id, n.title, n.slug, n.content, n.created_at, n.updated_at, c.title AS category_name
        FROM {$this->table} AS n
        LEFT JOIN bg_category AS c ON n.category_id = c.id
        ORDER BY created_at DESC";
    }
}
