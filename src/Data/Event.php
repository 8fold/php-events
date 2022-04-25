<?php

declare(strict_types=1);

namespace Eightfold\Events\Data;

use SplFileInfo;

use Eightfold\Events\Data\Interfaces\Event as EventInterface;

use Eightfold\Events\Implementations\Year as YearImp;
use Eightfold\Events\Implementations\Month as MonthImp;
use Eightfold\Events\Implementations\Date as DateImp;

class Event
{
    use YearImp;
    use MonthImp;
    use DateImp;

    private string $root;

    /**
     * @var SplFileInfo|false
     */
    private $item = false;

    private int $count;

    private string $content = '';

    /**
     * @var array<int>
     */
    private array $parts = [];

    public function __construct(
        string $root,
        int $year,
        int $month,
        int $date,
        int $count,
    ) {
        $this->root  = $root;
        $this->parts = [$year, $month, $date, $count];
    }

    public function item(): SplFileInfo|false
    {
        if ($this->item === false) {
            $check = new SplFileInfo(
                $this->root . '/' .
                $this->yearString() . '/' .
                $this->monthString() . '/' .
                $this->dateString() . '_' . $this->count() . '.event'
            );

            if ($this->count() === 1 and $check->isFile() === false) {
                $check = new SplFileInfo(
                    $this->root . '/' .
                    $this->yearString() . '/' .
                    $this->monthString() . '/' .
                    $this->dateString() . '.event'
                );
            }

            if ($check->isFile()) {
                $this->item = $check;
            }
        }
        return $this->item;
    }

    public function path(): string
    {
        return $this->item()->thePath();
    }

    public function content(): string
    {
        if (
            strlen($this->content) === 0 and
            $this->hasEvents() and
            $this->item()
        ) {
            $c = file_get_contents($this->item()->getRealPath());
            if (is_string($c)) {
                $this->content = $c;
            }
        }
        return $this->content;
    }

    public function title(): string
    {
        $content = $this->content();
        if (strlen($content) === 0) {
            return '';
        }

        $parts = $this->contentParts();
        $title = array_shift($parts);
        if ($title === null) {
            return '';
        }
        return trim($title);
    }

    public function body(): string
    {
        $content = $this->content();
        if (strlen($content) === 0) {
            return '';
        }

        $parts = $this->contentParts();
        $body  = array_pop($parts);
        if ($body === null) {
            return '';
        }
        return trim($body);
    }

    /**
     * @return array<string> [description]
     */
    private function contentParts(): array
    {
        return explode("\n\n", $this->content(), 2);
    }

    public function count(): int
    {
        return $this->parts[3];
    }

    public function couldHaveEvents(): bool
    {
        return $this->hasEvents();
    }

    public function hasEvents(): bool
    {
        if ($this->item()) {
            return $this->item()->isFile();
        }
        return false;
    }
}
