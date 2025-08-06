<?php

namespace Core\Session;

interface SessionInterface
{

    /**
     *
     * Récupère une info en session
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     *
     */
    public function get(string $key, $default = null);

    /**
     * Ajoute une info en session
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     *
     */
    public function set(string $key, $value): void;


    /**
     *
     * Supprime une clef en session
     * @param string $key
     * @return void
     *
     */
    public function delete(string $key): void;
}
