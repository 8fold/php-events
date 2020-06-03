<?php

namespace Eightfold\Events\UI\Traits;

trait FormatsImp
{
    private $yearAbbrFormat = "Y";
    private $yearTitleFormat = "Y";

    private $monthAbbrFormat = "M";
    private $monthTitleFormat = "F Y";

    private $dayAbbrFormat = "j";
    private $dayTitleFormat = "jS \\of F Y";

    public function yearFormats(
        string $abbrFormat = "Y",
        string $titleFormat = "Y"
    )
    {
        $this->yearAbbrFormat = $abbrFormat;
        $this->yearTitleFormat = $titleFormat;
        return $this;
    }

    public function monthFormats(
        string $abbrFormat = "M",
        string $titleFormat = "F Y"
    )
    {
        $this->monthAbbrFormat = $abbrFormat;
        $this->monthTitleFormat = $titleFormat;
        return $this;
    }

    public function dayFormats(
        string $abbrFormat = "j",
        string $titleFormat = "jS F Y"
    )
    {
        $this->dayAbbrFormat = $abbrFormat;
        $this->dayTitleFormat = $titleFormat;
        return $this;
    }
}
