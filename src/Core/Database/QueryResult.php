<?php

namespace Core\Database;

class QueryResult implements \ArrayAccess, \Iterator
{
    /**
     * @var Array
     */
    private $records;

    private $index = 0;

    private $hydratedReccords = [];

    private $entity;

    public function __construct(array $records, ?string $entity = null)
    {
        $this->records = $records;
        $this->entity = $entity;
    }

    public function get(int $index)
    {
        if ($this->entity) {
            if (!isset($this->hydratedReccords[$index])) {
                $this->hydratedReccords[$index] = Hydrator::hydrate($this->records[$index], $this->entity);
            }
            return $this->hydratedReccords[$index];
        }
        return $this->entity;
    }

    public function current():mixed
    {
        return $this->get($this->index);
    }

    public function next(): void
    {
        $this->index++;
    }

    public function key():mixed
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return isset($this->records[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->records[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($this->index);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \Exception();
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \Exception();
    }
}
