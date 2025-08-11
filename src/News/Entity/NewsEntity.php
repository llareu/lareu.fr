<?php
namespace Root\News\Entity;

use DateTime;

class NewsEntity extends \stdClass
{
    public $id;

    public $title;

    public $slug;

    public $content;

    public $createdAt;

    public $updatedAt;

    public $categoryName;

    public function __construct()
    {
        if ($this->created_at) {
            $this->created_at = new \DateTime($this->created_at);
        }

        if ($this->updated_at) {
            $this->updated_at = new \DateTime($this->updated_at);
        }
    }

    public function setcreatedAt($datetime)
    {
        if (is_string($datetime)) {
            $this->createdAt = new DateTime($datetime);
        }
    }

    public function setupdatedAt($datetime)
    {
        if (is_string($datetime)) {
            $this->createdAt = new DateTime($datetime);
        }
    }
}
