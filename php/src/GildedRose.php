<?php

declare(strict_types=1);

namespace GildedRose;

use function ECSPrefix202306\Symfony\Component\String\b;

final class GildedRose
{
    private const AGED_BRIE = 'Aged Brie';
    private const BACKSTAGE_PASSES = 'Backstage passes to a TAFKAL80ETC concert';
    private const SULFURAS = 'Sulfuras, Hand of Ragnaros';

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
            switch ($item->name) {
                case self::AGED_BRIE:
                    $item->quality = $this->increaseQuality($item->quality, $item->sellIn);
                    $item->sellIn = $this->decreaseSellInDate($item->sellIn);
                    break;
                case self::BACKSTAGE_PASSES:
                    $item->quality = $this->increaseQuality($item->quality, $item->sellIn);
                    $item->quality = $this->removeQualityWhenSellInExpires($item->quality, $item->sellIn);
                    $item->sellIn = $this->decreaseSellInDate($item->sellIn);
                    break;
                case self::SULFURAS:
                    break;
                default:
                    $item->quality = $this->decreaseQuality($item->quality, $item->sellIn);
                    $item->sellIn = $this->decreaseSellInDate($item->sellIn);
            }
        }
    }

    private function decreaseQuality(int $quality, int $sellIn): int
    {
        if ($quality > 0) {
            $quality = $quality - 1;
        }

        if ($sellIn < 0) {
            $quality = $quality - 1;
        }

        return $quality;
    }

    private function decreaseSellInDate(int $sellIn): int
    {
        return $sellIn > 0 ? $sellIn - 1 : $sellIn;
    }

    private function increaseQuality(int $quality, int $sellIn): int
    {
        if ($quality < 50) {
            $quality = $quality + 1;
            if ($sellIn < 11) {
                if ($quality < 50) {
                    $quality = $quality + 1;
                }
            }
            if ($sellIn < 6) {
                if ($quality < 50) {
                    $quality = $quality + 1;
                }
            }
        }
        return $quality;
    }

    private function removeQualityWhenSellInExpires(int $quality, int $sellIn): int
    {
        if ($sellIn < 0) {
            $quality = $quality - $quality;
        }

        return $quality;
    }
}
