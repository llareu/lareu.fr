<?php

namespace Core\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExt extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('field', [$this, 'field'], [
                'is_safe' => ['html'],
                'needs_context' => true,
            ])
        ];
    }

    /**
    *   Génère le code HTML d'un champs
    *   @param array $context Contexte de la vue Twig
    *   @param string $key clé di champs
    *   @param mixed $value valeur du champs
    *   @param string $label nom du label
    *   @param array $options (textarea,...)
    *   @return string retourne le code html du champs
    **/
    public function field(array $context, string $key, $value, ?string $label = null, array $options = []): string
    {
        $type = $options['type'] ?? 'text';
        $error = $this->getErrorHTML($context, $key);

        $class = ['form-group'];

        $value = $this->convertValue($value);

        $attributes = [
            'class' => trim('form-control '.($options['class'] ?? '')),
            'name' => $key,
            'id' => $key
        ];

        if ($error) {
            $class[] = 'has-danger';
            $attributes['class'] .= ' form-control-danger';
        }

        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } elseif (array_key_exists('options', $options)) {
            $input = $this->select($value, $options['options'], $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }

        return "
            <div class=\"". implode(' ', $class) ."\">
                <label for=\"{$key}\">{$label}</label>
                {$input}
                {$error}
            </div>
        ";
    }

    private function convertValue($value): string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }

    /**
    *   Génère un input
    *   @param null|string $value
    *   @param array $attributes
    *   @return string
    **/
    private function input(?string $value, array $attributes): string
    {
        return "
            <input type=\"text\" ".$this->getHTMLFromArray($attributes)." value=\"{$value}\" />
        ";
    }

    /**
    *   Génère un testarea
    *   @param null|string $value
    *   @param array $attributes
    *   @return string
    **/
    private function textarea(?string $value, array $attributes): string
    {
        return "
            <textarea ".$this->getHTMLFromArray($attributes).">{$value}</textarea>
        ";
    }

        /**
    *   Génère un select
    *   @param null|string $value
    *   @param array $options
    *   @param array $attributes
    *   @return string
    **/
    private function select(?string $value, array $options, array $attributes)
    {
        $htmlOptions = array_reduce(array_keys($options), function (string $html, string $key) use ($options, $value) {
            $params = [
                'value' => $key,
                'selected' => $key === $value
            ];
            return $html.'<option '.$this->getHTMLFromArray($params).'>'.$options[$key].'</option>';
        }, "");
            return "<select ".$this->getHTMLFromArray($attributes).">$htmlOptions</select>";
    }

    /**
    *   Génère un text erreur sur le formulaire
    *   @param null|string $key
    *   @param array $context
    *   @return string
    **/
    private function getErrorHTML($context, $key): string
    {
        $error = $context['errors'][$key] ?? false;
        if ($error) {
            return "<small class=\"form-text text-muted\">{$error}</small>";
        }
        return "";
    }

    /**
    *   Génère les attributes dans le HTML
    *   @param array $attributes
    *   @return string
    **/
    private function getHTMLFromArray(array $attributes): string
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $htmlParts[] = (string) $key;
            } elseif ($value !== false) {
                $htmlParts[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $htmlParts);
    }
}
