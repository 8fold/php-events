<?php

namespace Eightfold\Events\Data;

use SplFileInfo;

use Symfony\Component\Finder\Finder;

use Eightfold\Events\Data\Year;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Item as ItemImp;

class Years
{
    use RootImp;
    use ItemImp;

    /**
     * @var Year[]
     */
    private array $content = [];

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
     * @return Year[]
     */
    public function content(): array
    {
        if (count($this->content) === 0 and $this->item() !== false) {
            $c = (new Finder())->directories()->depth('== 0')
                ->in($this->item()->getRealPath());
            foreach ($c as $year) {
                $path  = $year->getRealPath();
                $parts = explode('/', $path);
                $year  = array_pop($parts);
                $key   = 'i' . $year;

                $this->content[$key] = new Year($this->root, intval($year));
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
        foreach ($this->content() as $year) {
            if ($year->hasEvents()) {
                return true;
            }
        }
        return false;
    }

    public function year(int $year): Year|false
    {
        $year = 'i' . $year;
        if ($c = $this->content() and array_key_exists($year, $c)) {
            return $c[$year];
        }
        return false;
    }
}
