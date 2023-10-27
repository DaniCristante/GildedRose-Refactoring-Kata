<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    /**
     * @test
     */
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals('foo', $items[0]->name);
    }

    /**
     * @test
     */
    public function itShouldDecreaseQualityOfAnItem(): void
    {
        $items = [new Item('foo', 12, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(9, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itShouldDecreaseSellInOfAnyNonUniqueItem()
    {
        $items = [new Item('foo', 12, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(11, $items[0]->sellIn);
    }

    /**
     * @test
     */
    public function itShouldDecreaseQualityTwiceFasterIfSellInDateIsNegative()
    {
        $items = [new Item('foo', -1, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(8, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itShouldNotDecreaseQualityIfQualityIsZero(): void
    {
        $items = [new Item('foo', 12, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(0, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itShouldNotIncreaseQualityIfQualityIsFifty(): void
    {
        $items = [new Item('Aged Brie', 12, 50)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(50, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itShouldIncreaseAgedBrieQuality(): void
    {
        $items = [new Item('Aged Brie', 12, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(11, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itShouldIncreaseQualityByTwoIfSellInDateIsTenDaysOrLess(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 7, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(12, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itShouldIncreaseQualityByThreeIfSellInDateIsFiveDaysOrLess(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', 4, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(13, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itShouldRemoveQualityFromPassesAfterTheConcert(): void
    {
        $items = [new Item('Backstage passes to a TAFKAL80ETC concert', -1, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(0, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itShouldNotModifyUniqueItems(): void
    {
        $items = [new Item('Sulfuras, Hand of Ragnaros', 12, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(10, $items[0]->quality);
        static::assertEquals(12, $items[0]->sellIn);
    }
}
