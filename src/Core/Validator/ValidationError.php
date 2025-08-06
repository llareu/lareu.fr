<?php

namespace Core\Validator;

class ValidationError
{

    private $key;
    private $rule;
    private $msg = [
        'required' => 'Le champs %s est requis.',
        'empty' => 'Le champs %s ne doit pas être vide.',
        'slug' => 'Le champs %s n\'est pas un slug valide',
        'minLength' => 'Le champs %s doit contenir plus de %d caractères.',
        'maxLength' => 'Le champs %s doit contenir moins de %d caractères.',
        'betweenLength' => 'Le champs %s doit être entre %d et %d caractères.',
        'datetime' => 'Le champs %s doit être un format ou une date valide',
        'exists' => 'Le champs %s n\'existe pas dans la table %s',
        'unique' => 'Le champs doit être unique dans la table %s'
    ];

    private $attributes;

    public function __construct(string $key, string $rule, array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $params = array_merge([$this->msg[$this->rule], $this->key], $this->attributes);
        return (string) call_user_func_array('sprintf', $params);
    }
}
