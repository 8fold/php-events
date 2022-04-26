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
use Eightfold\Events\Implementations\Item as ItemImp;

class Date
{
    use RootImp;
    use PartsImp;
    use YearImp;
    use MonthImp;
    use DateImp;
    use ItemImp;

    /**
     * @var Event[]
     */
    private array $content = [];

    public function __construct(string $root, int $year, int $month, int $date)
    {
        $this->root = $root;
        $this->parts = [$year, $month, $date];
    }

    public function item(): SplFileInfo|false
    {
        if ($this->item === false) {
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
        if ($this->item() === false) {
            return '';
        }
        return $this->item()->getRealPath();
    }

    /**
     * @return Event[]
     */
    public function content(): array
    {
        if (count($this->content) === 0 and $this->item() !== false) {
            $c = (new Finder())->name('*.event')
                ->in($this->item()->getRealPath());
            foreach ($c as $item) {
                $path     = $item->getRealPath();
                $p        = explode('/', $path);
                $fileName = array_pop($p);
                if (str_starts_with($fileName, $this->dateString())) {
                    $fParts = explode('_', $fileName);
                    $count = 1;
                    if (count($fParts) > 1) {
                        $count = array_pop($fParts);
                        $count = str_replace('.events', '', $count);
                        $count = intval($count);
                    }

                    $this->content[$path] =
                        (new Event(
                            $this->root,
                            $this->year(),
                            $this->month(),
                            $this->date(),
                            $count
                        ));
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
        return $this->hasEvents();
    }

    public function hasEvents(): bool
    {
        return $this->count() > 0;
    }
}
