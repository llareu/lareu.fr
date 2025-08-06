<?php

namespace Core\Database;

use Pagerfanta\Adapter\AdapterInterface;

class PaginatedQuery implements AdapterInterface
{

    private $pdo;
    private $query;
    private $countQuery;
    private $entity;
    private $params;



    /**
     * PaginatedQuery constructor
     * @param \PDO $pdo
     * @param $query requete permettant de recup les results
     * @param $countQuery requete permetant de compter le nombre de results
     * @param $entity entité à utiliser
     * @param $params paramètres à passer à la requete
     */

    public function __construct(\PDO $pdo, string $query, string $countQuery, ?string $entity, array $params = [])
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
        $this->entity = $entity;
        $this->params = $params;
    }

    /**
     *
     * Returns the number of results
     * @return integer the number of results
     *
     */

    public function getNbResults(): int
    {
        if (!empty($this->params)) {
            $query = $this->pdo->prepare($this->countQuery);
            $query->execute($this->params);
            return $query->fetchColumn();
        }
        return $this->pdo->query($this->countQuery)->fetchColumn();
    }


    public function getSlice($offset, $length): array
    {
        $stmt = $this->pdo->prepare($this->query.' LIMIT :offset, :length');
        foreach ($this->params as $key => $param) {
            $stmt->bindParam($key, $param);
        }
        $stmt->bindParam('offset', $offset, \PDO::PARAM_INT);
        $stmt->bindParam('length', $length, \PDO::PARAM_INT);
        if ($this->entity) {
            $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
