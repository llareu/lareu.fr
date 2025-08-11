<?php

namespace Tests\Core\Database;

class Demo
{

    public $slug;

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug . '-demo';
    }
}
