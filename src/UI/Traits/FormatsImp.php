<?php

namespace Eightfold\Events\UI\Traits;

trait FormatsImp
{
    protected $yearAbbrFormat = "Y";
    protected $yearTitleFormat = "Y";

    protected $monthAbbrFormat = "M";
    protected $monthTitleFormat = "F Y";

    protected $dayAbbrFormat = "j";
    protected $dayTitleFormat = "jS \\of F Y";

    public function yearFormats(
        string $abbrFormat = "Y",
        string $titleFormat = "Y"
    ) {
        $this->yearAbbrFormat = $abbrFormat;
        $this->yearTitleFormat = $titleFormat;
        return $this;
    }

    public function monthFormats(
        string $abbrFormat = "M",
        string $titleFormat = "F Y"
    ) {
        $this->monthAbbrFormat = $abbrFormat;
        $this->monthTitleFormat = $titleFormat;
        return $this;
    }

    public function dayFormats(
        string $abbrFormat = "j",
        string $titleFormat = "jS F Y"
    ) {
        $this->dayAbbrFormat = $abbrFormat;
        $this->dayTitleFormat = $titleFormat;
        return $this;
    }
}
