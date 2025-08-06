<?php

namespace Core\Database;

use Pagerfanta\Pagerfanta;
use Core\Database\NoRecordException;

class Table
{
    /**
     *
     * @var \PDO
    */
    protected $pdo;

    /**
     *
     * Nom de la table BDD
     * @var string
     */
    protected $table;

    /**
     *
     * Entité à utiliser
     * @var string|null
     */
    protected $entity;


    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Paginer des elements
     * @param int $perPage
     * @return Pagerfanta
     */

    public function findPaginated(int $perPage, int $curentPage): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            $this->paginationQuery(),
            "SELECT COUNT(id) FROM {$this->table}",
            $this->entity
        );

        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($curentPage);
    }

    protected function paginationQuery()
    {
        return "SELECT * FROM {$this->table}";
    }

    /**
     * Recup une liste clef valeur de nos reccord
     */
    public function findList(): array
    {
        $results = $this->pdo
            ->query("SELECT * FROM {$this->table}")
            ->fetchAll(\PDO::FETCH_NUM);
            $list = [];

        foreach ($results as $result) {
            $list[$result[0]] = $result[1];
        }
        return $list;
    }

    /**
     * Recupere tous les enregistrements
     * @return array
     */
    public function findAll(): array
    {
        $query = $this->pdo
            ->query("SELECT * FROM {$this->table}");
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        } else {
            $query->setFetchMode(\PDO::FETCH_OBJ);
        }
        return $query->fetchAll();
    }

    /**
     * Récupère un enregistrement en fonction de son champ
     * @param string $field
     * @param string $value
     * @return array
     * @throws NoRecordException
     */
    public function findBy(string $field, string $value)
    {
        return $this->fetchOrFail("SELECT * FROM {$this->table} WHERE $field = ?", [$value]);
    }

    /**
     *
     * Récupère un element a partir de son ID
     * @param int $id
     * @return mixed
     * @throws NoRecordException
     *
     */
    public function find(int $id)
    {
        return $this->fetchOrFail("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }

    /**
     * Compte le nombre d'enregistrements dans la table
     * @return integer
     */
    public function count(): int
    {
        return (int) $this->fetchColumn("SELECT COUNT(id) FROM {$this->table}");
    }

    /**
     * Met à jour un enregistrement au niveau de la BDD
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $fieldQuery = $this->buildFieldQuery($params);
        $params["id"] = $id;
        $query = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id");
        return $query->execute($params);
    }

        /**
     * ajoute un enregistrement au niveau de la BDD
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function insert(array $params):bool
    {

        $fields = array_keys($params);
        $values = join(',', array_map(function ($field) {
            return ':'.$field;
        }, $fields));
        $fields = join(',', $fields);
        $query = "INSERT INTO {$this->table} ($fields) VALUES ($values)";

        $query = $this->pdo->prepare($query);
        return $query->execute($params);
    }

        /**
     * Supprime un enregistrement au niveau de la BDD
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $query->execute([$id]);
    }



    private function buildFieldQuery(array $params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }

        /**
     * Verifie qu'un enregistrement existe
     * @param $id
     * return bool
     */

    public function exists($id): bool
    {
        $query = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE id = ?");
        $query->execute([$id]);
        return $query->fetchColumn() !== false;
    }

    /**
     * retourne l'entité
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * retourne la table
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return \PDO
     */
    public function getPDO(): \PDO
    {
        return $this->pdo;
    }

    /**
     * Permet d'execuer une requete et de retourner un enregistrement
     *
     * @param string $query
     * @param array $params
     * @return mixed
     * @throws NoRecordException
     */
    protected function fetchOrFail(string $query, array $params = [])
    {
        $query = $this->pdo->prepare($query);
        $query->execute($params);
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        $reccord =  $query->fetch();
        if ($reccord === false) {
            throw new NoRecordException();
        }
        return $reccord;
    }

    /***
     * Permet d'exécuter une requête et de retourner une colonne
     * @param string $query
     * @param array $params
     * @return mixed
     */
    protected function fetchColumn(string $query, array $params = [])
    {
        $query = $this->pdo->prepare($query);
        $query->execute($params);
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        return $query->fetchColumn();
    }
}
