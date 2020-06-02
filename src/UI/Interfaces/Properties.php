<?php

namespace Eightfold\Events\UI\Interfaces;

interface Properties
{
    public function render();

    public function path();

    // public function year();

    // public function month();

    public function events();

    public function carbon();

    public function isYear();

    public function isMonth();

    public function uriPrefix(string $prefix = "/events");

    public function prefix(): string;
}
