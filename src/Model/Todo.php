<?php

declare(strict_types = 1);

namespace App\Model;


class Todo implements \JsonSerializable
{
    /**
     * @var string|null
     */
    protected $title;
    /**
     * @param string|null $title
     */
    public function setTitle(?string $title) : void
    {
        $this->title = $title;
    }
    /**
     * @return string|null
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }
    public function jsonSerialize()
    {
        return (object) array_filter(array('title' => $this->title), static function ($value) : bool {
            return $value !== null;
        });
    }
}
