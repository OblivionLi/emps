<?php

namespace core\traits;

trait CategoryTrait {
    protected $title;
    protected $slug;
    protected $description;

    public function get_title() { return $this->title; }
    public function get_slug() { return $this->slug; }
    public function get_description() { return $this->description; }

    public function set_title($title) { $this->title = $title; }
    public function set_slug($slug) { $this->slug = $slug; }
    public function set_description($description) { $this->description = $description; }
}