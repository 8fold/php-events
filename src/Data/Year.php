<?php

declare(strict_types=1);

namespace Eightfold\Events\Data;

use SplFileInfo;

use Symfony\Component\Finder\Finder;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Parts as PartsImp;
use Eightfold\Events\Implementations\Year as YearImp;
use Eightfold\Events\Implementations\Item as ItemImp;

class Year
{
    use RootImp;
    use PartsImp;
    use YearImp;
    use ItemImp;

    /**
     * @var [Month]
     */
    private array $content = [];

    public static function totalMonthsInYear(): int
    {
        return 12;
    }

    public function __construct(string $root, int $year)
    {
        $this->root  = $root;
        $this->parts = [$year];
    }

    private function item(): SplFileInfo|false
    {
        if ($this->item === false) {
            $check = new SplFileInfo(
                $this->root . '/' .
                $this->yearString()
            );

            if ($check->isDir()) {
                $this->item = $check;
            }
        }
        return $this->item;
    }

    private function path(): string
    {
        if ($this->item() === false) {
            return '';
        }
        return $this->item()->getPath();
    }

    /**
     * @return [Month]
     */
    public function content(): array
    {
        if (
            count($this->content) === 0 and
            $path = $this->path() . '/' . $this->yearString() and
            file_exists($path) and
            is_dir($path)
         ) {
            $c = (new Finder())->directories()->depth('== 0')->in($path);
            foreach ($c as $month) {
                $parts = explode('/', $month->getRealPath());
                $month = array_pop($parts);
                $key   = 'i' . $month;
                if (! isset($this->content[$key])) {
                    $this->content[$key] = new Month(
                        $this->root,
                        $this->year(),
                        intval($month)
                    );
                }
            }
        }
        return $this->content;
    }

    public function count(): int
    {
        return count($this->content());
    }

    public function couldHaveEvents(): bool
    {
        return $this->count() > 0;
    }

    public function hasEvents(): bool
    {
        foreach ($this->content() as $month) {
            if ($month->hasEvents()) {
                return true;
            }
        }
        return false;
    }

    public function isSameAs(int $compare): bool
    {
        return $this->year() === $compare;
    }

    public function isAfter(int $compare): bool
    {
        if ($this->isSameAs($compare)) {
            return false;
        }
        return $this->year() > $compare;
    }

    public function isBefore(int $compare): bool
    {
        if ($this->isSameAs($compare)) {
            return false;
        }
        return ! $this->isAfter($compare);
    }

    public function uri(): string
    {
        return '/' . $this->yearString();
    }
}
