<?php
namespace Root\BookmarkManager\Entity;

use DateTime;

class BookmarkManagerEntity extends \stdClass
{
    public $id;

    public $title;

    public $link;

    public $category_title;

    public $picture_title;

    public $click_counter;

    public $user_uuid;

    public $created_at;

    public $updated_at;

    public function __construct()
    {
        if ($this->created_at) {
            $this->created_at = new \DateTime($this->created_at);
        }

        if ($this->updated_at) {
            $this->updated_at = new \DateTime($this->updated_at);
        }
    }
}
