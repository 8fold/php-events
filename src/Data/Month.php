<?php

declare(strict_types=1);

namespace Eightfold\Events\Data;

use SplFileInfo;

use Symfony\Component\Finder\Finder;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Parts as PartsImp;
use Eightfold\Events\Implementations\Year as YearImp;
use Eightfold\Events\Implementations\Month as MonthImp;

class Month
{
    use RootImp;
    use PartsImp;
    use YearImp;
    use MonthImp;

    private SplFileInfo|false $item = false;

    /**
     * @var [Date]
     */
    private array $content = [];

    /**
     * @todo: should be able to deprecate this constructor
     */
//     public static function fromItem(string $rootPath, SplFileInfo $item): Month
//     {
//         $p = $item->thePath();
//         $parts = explode('/', $p);
//
//         $month = intval(array_pop($parts));
//
//         $year = intval(array_pop($parts));
//
//         return new Month($rootPath, $year, $month);
//     }

    /**
     * @param mixed $args [description]
     */
    // public static function fold(...$args): Month
    // {
    //     return new Month(...$args);
    // }

    public function __construct(string $root, int $year, int $month)
    {
        $this->root  = $root;
        $this->parts = [$year, $month];
        // $this->item  = $item;
    }

    public function item(): SplFileInfo|false
    {
        if ($this->item === false) {
            $check = new SplFileInfo(
                $this->root . '/' .
                $this->yearString() . '/' .
                $this->monthString()
            );

            if ($check->isDir()) {
                $this->item = $check;
            }
        }
        return $this->item;
    }

    public function path(): string
    {
        return $this->item()->getRealPath();
    }

    /**
     * @return [Date]
     */
    public function content(): array
    {
        if (
            count($this->content) === 0 and
            $this->item() !== false and
            $this->item()->isDir()
        ) {
            $c = (new Finder())->files()->name('*.event')
                ->in($this->item()->getRealPath());
            foreach ($c as $item) {
                $path     = $item->getRealPath();
                $p        = explode('/', $path);
                $fileName = array_pop($p);
                $date = substr($fileName, 0, 2);
                $key  = 'i' . $date;
                if (! isset($this->content[$key])) {
                    $d = new Date($this->root, $this->year(), $this->month(), intval($date));
                    $this->content[$key] = $d;
                }
            }

            ksort($this->content);
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
        foreach ($this->content() as $date) {
            if ($date->hasEvents()) {
                return true;
            }
        }
        return false;
    }

    public function isSameAs(int $compare): bool
    {
        return $this->month() === $compare;
    }

    public function isAfter(int $compare): bool
    {
        if ($this->isSameAs($compare)) {
            return false;
        }
        return $this->month() > $compare;
    }

    public function isBefore(int $compare): bool
    {
        if ($this->isSameAs($compare)) {
            return false;
        }
        return $this->month() < $compare;
    }

    public function uri(): string
    {
        return str_replace($this->root, '', $this->path());
    }
}
