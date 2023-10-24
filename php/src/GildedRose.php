<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    public const MAX_QUALITY = 50;

    public const AGED_BRIE = 'Aged Brie';
    public const BACKSTAGE_PASSES = 'Backstage passes to a TAFKAL80ETC concert';
    public const SULFURAS = 'Sulfuras, Hand of Ragnaros';
    
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($item->name != self::AGED_BRIE and $item->name != self::BACKSTAGE_PASSES) {
                if ($item->quality > 0) {
                    if ($item->name != self::SULFURAS) {
                        $item->quality = $this->decreaseQuality($item->quality);
                    }
                }
            } else {
                $item->quality = $this->increaseQuality($item->quality);
                if ($item->name == self::BACKSTAGE_PASSES) {
                    if ($item->sellIn < 11) {
                        $item->quality = $this->increaseQuality($item->quality);
                    }
                    if ($item->sellIn < 6) {
                        $item->quality = $this->increaseQuality($item->quality);
                    }
                }
                
            }

            if ($item->name != self::SULFURAS) {
                $item->sellIn = $this->decreaseSellIn($item->sellIn);
            }

            if ($item->sellIn < 0) {
                if ($item->name != self::AGED_BRIE) {
                    if ($item->name != self::BACKSTAGE_PASSES) {
                        if ($item->quality > 0) {
                            if ($item->name != self::SULFURAS) {
                                $item->quality = $this->decreaseQuality($item->quality);
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    $this->increaseQuality($item->quality);
                }
            }
        }
    }

    private function decreaseQuality(int $quality): int {
        return $quality - 1;
    }

    private function increaseQuality(int $quality): int {
        if ($quality < self::MAX_QUALITY) {
            return $quality + 1;
        }
    }

    private function decreaseSellIn(int $sellIn): int {
        return $sellIn - 1;
    }
}
