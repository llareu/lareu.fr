<?php

namespace Core;

use Core\Validator\ValidationError;
use Core\Database\Table;

class Validator
{

    /**
     *
     * @var array
     *
     */
    private $params;

    /**
     *
     * @var string[]
     *
     */
    private $errors = [];



    public function __construct(array $params)
    {
        $this->params = $params;
    }


    /***
     *
     * Verifie que les champs sont présents dans le tableau $keys
     * @param string[] ...$keys
     * @k string clé du tableau
     * @return Validator
     *
     */
    public function required(string ...$keys): self
    {
        foreach ($keys as $index) {
            if (is_null($this->getValue($index))) {
                $this->addError($index, 'required');
            }
        }
        return $this;
    }

    /**
     *
     * Verifie que le champ n'est pas vide
     * @param string $key
     * @return Validator
     *
     */
    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }
        return $this;
    }

    public function length(string $key, ?int $min, ?int $max = null):self
    {
        $value = $this->getValue($key);
        $length = mb_strlen($value);
        if (!is_null($min) &&
            !is_null($max) &&
            ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        }

        if (!is_null($min) && $length < $min) {
            $this->addError($key, 'minLength', [$min]);
            return $this;
        }

        if (!is_null($max) && $length > $max) {
            $this->addError($key, 'maxLength', [$max]);
            return $this;
        }
        return $this;
    }

    /**
     * Verifie que l'élément est un slug
     *
     * @param string $key
     * @return Validator
     */
    public function slug(string $key): self
    {
        $pattern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';
        if (!is_null($this->getValue($key)) && !preg_match($pattern, $this->params[$key])) {
            $this->addError($key, 'slug');
        }

        return $this;
    }


    public function dateTime(string $key, string $format = 'Y-m-d H:i:s'): self
    {
        $value = $this->getValue($key);
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        //$errors['error_count'] > 0 || // $errors['warning_count'] > 0 || $date !== false
        if ($errors !== false || $date === false) {
            $this->addError($key, 'datetime', [$format]);
        };
        return $this;
    }

    
    public function exists(string $key, string $table, \PDO $pdo): self
    {
        $id = $this->getValue($key);
        $stmt = $pdo->prepare("SELECT id FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->fetchColumn() === false) {
            $this->addError($key, 'exists', [$table]);
        }
        return $this;
    }

    public function unique(string $key, string $table, \PDO $pdo, ?int $exclude = null): self
    {
        $value = $this->getValue($key);
        $query = "SELECT id FROM $table WHERE $key = ?";
        $params = [$value];

        if ($exclude !== null) {
            $query .= " AND id != ?";
            $params[] = $exclude;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        if ($stmt->fetchColumn() !== false) {
            $this->addError($key, 'unique', [$value]);
        }
        return $this;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }


    /**
     *
     * Récupère les erreurs
     * @return validationError[]
     *
     */
    public function getErrors(): array
    {
        return $this->errors;
    }



    /**
     * Ajoute une erreur
     *
     * @param string $key
     * @string rule
     */
    private function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }


    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return null;
    }
}
