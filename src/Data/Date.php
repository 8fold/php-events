<?php

declare(strict_types=1);

namespace Eightfold\Events\Data;

use SplFileInfo;

use Symfony\Component\Finder\Finder;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Parts as PartsImp;
use Eightfold\Events\Implementations\Year as YearImp;
use Eightfold\Events\Implementations\Month as MonthImp;
use Eightfold\Events\Implementations\Date as DateImp;

class Date
{
    use RootImp;
    use PartsImp;
    use YearImp;
    use MonthImp;
    use DateImp;

    /**
     * @var Item|null
     */
    private $item;

    /**
     * @var array<Event>
     */
    private array $content = [];

    public static function fromItem(string $rootPath, SplFileInfo $item): Date
    {
        $p = $item->thePath();
        $parts = explode('/', $p);

        $fileName = array_pop($parts);
        $fileName = str_replace('.event', '', $fileName);
        $fParts   = explode('_', $fileName);
        $date     = intval(array_shift($fParts));

        $month = intval(array_pop($parts));

        $year = intval(array_pop($parts));

        // Item doesn't need date; go up one
        return new Date($rootPath, $year, $month, $date, $item->up());
    }

    /**
     * @param mixed $args [description]
     */
    public static function fold(...$args): Date
    {
        return new Date(...$args);
    }

    public function __construct(
        string $root,
        int $year,
        int $month,
        int $date,
        SplFileInfo $item = null
    ) {
        $this->root = $root;
        $this->parts = [$year, $month, $date];
        $this->item  = $item;
    }

    public function item(): SplFileInfo
    {
        if ($this->item === null) {
            $this->item = new SplFileInfo(
                $this->root . '/' .
                $this->yearString() . '/' .
                $this->monthString()
            );
        }
        return $this->item;
    }

    public function path(): string
    {
        return $this->item()->thePath();
    }

    /**
     * @return array<Event>
     */
    public function content()
    {
        if (count($this->content) === 0) {
            $c = (new Finder())->in($this->item()->getRealPath());
            foreach ($c as $item) {
                $path     = $item->getRealPath();
                $p        = explode('/', $path);
                $fileName = array_pop($p);
                if (
                    str_ends_with($fileName, '.event') and
                    str_starts_with($fileName, $this->dateString())
                ) {
                    $this->content[$path] =
                        Event::fromItem(
                            $this->root,
                            new SplFileInfo($item->getRealPath())
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
        return $this->hasEvents();
    }

    public function hasEvents(): bool
    {
        return $this->count() > 0;
    }
}
