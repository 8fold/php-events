<?php

namespace Eightfold\Events\UI\Interfaces;

interface Formats
{
    public function yearFormats(string $abbrFormat = "Y", string $titleFormat = "Y");

    public function monthFormats(string $abbrFormat = "M", string $titleFormat = "F Y");

    public function dayFormats(string $abbrFormat = "j", string $titleFormat = "jS F Y");
}
