<?php

namespace Core\Router;

/**
 * Class Route
 * represent a route fund
 */
class Route
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var $callback
     */
    private $callback;

    /**
     * @var array $params
     */
    private $params;

    /**
     * Route constructor
     * @param string|callable $callback
     * @param array $params
     * @param string $name
     */

    public function __construct(string|callable $callback, array $params, string $name = null)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->params = $params;
    }


    /**
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @return callable|string
     */
    public function getCallback(): callable|string
    {
        return $this->callback;
    }

    /**
     * Get the URL params
     * @return string[]
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
