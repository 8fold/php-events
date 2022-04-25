<?php

namespace Eightfold\Events\Data;

use SplFileInfo;

use Symfony\Component\Finder\Finder;
// use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Year;

use Eightfold\Events\Implementations\Root as RootImp;


class Years
{
    use RootImp;

    private SplFileInfo|false $item = false;

    /**
     * @var array<Year>
     */
    private array $content = [];

    /**
     * @param string $args [description]
     */
    public static function fold(...$args): Years
    {
        return new Years(...$args);
    }

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function path(): string
    {
        return $this->root();
    }

    public function item(): SplFileInfo|false
    {
        if ($this->item === false) {
            $this->item = new SplfileInfo($this->root);
        }
        return $this->item;
    }

    /**
     * @return array<Year> [description]
     */
    public function content(): array
    {
        if (count($this->content) === 0) {
            $c = (new Finder())->directories()->depth('== 0')
                ->in($this->item()->getRealPath());
            // $c = $this->item()->content();
            // if (is_array($c)) {
                foreach ($c as $year) {
                    $path  = $year->getRealPath();
                    $parts = explode('/', $path);
                    $year  = array_pop($parts);
                    $key   = 'i' . $year;

                    $this->content[$key] = new Year($this->root, intval($year));
                }
            // }
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
        foreach ($this->content() as $year) {
            if ($year->hasEvents()) {
                return true;

            }
        }
        return false;
    }

    /**
     * @return Year|bool       [description]
     */
    public function year(int $year)
    {
        $year = 'i' . $year;
        if ($c = $this->content() and array_key_exists($year, $c)) {
            return $c[$year];
        }
        return false;
    }
}
