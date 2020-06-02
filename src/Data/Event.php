<?php

namespace Eightfold\Events\Data;

use Carbon\Carbon;

use Eightfold\Shoop\{Shoop, ESString};

use Eightfold\Events\Data\Interfaces\Day;
use Eightfold\Events\Data\Traits\DayImp;

class Event implements Day
{
    use DayImp;

	private $content = "";

	static public function init(string $path): Event
	{
		return new Event($path);
	}

	public function __construct(string $path)
	{
        $this->path = Shoop::string($path);
	}

    public function month()
    {
        return $this->path()->divide("/")->toggle()->first(2)->last()->int;
    }

    public function year()
    {
        return $this->path()->divide("/")->toggle()->first(3)->last()->int;
    }

	public function content(): ESString
	{
		return $this->path()->pathContent()->isEmpty(function($result, $content) {
            if ($result) {
                return Shoop::string("");
            }
            return Shoop::string($content);
        });
	}

    public function title(): string
    {
        if ($this->content()->count()->is(0)->unfold()) {
            return "";
        }
        return $this->content()->divide("\n\n")->first;
    }

    public function body(): string
    {
        if (strlen($this->content()) === 0) {
            return "";
        }
        return $this->content()->divide("\n\n", false, 2)->last;
    }

    public function events()
    {
        return Shoop::array([$this]);
    }

    public function dataPath(): ESString
    {
        return $this->path();
    }

    public function uri(): string
    {
        return $this->path()->divide("/")->toggle()->first(3)->toggle()
            ->join("/")->start("/")->divide(".", false, 2)->first;
    }
}
