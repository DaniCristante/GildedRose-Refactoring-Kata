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
    public function itReturnsItems(): void
    {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals('foo', $items[0]->name);
    }

    /**
     * @test
     */
    public function itDecreasesQuality(): void {
        $items = [new Item('foo', 0, 10)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(8, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itDecresaesSellInDate(): void  {
        $items = [new Item('foo', 10, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(9, $items[0]->sellIn);
    }

    /**
     * @test
     */
    public function itDoesNotDecreaseQualityToNegativeValue(): void {
        $items = [new Item('foo', 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(0, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itDoesNotDecreaseSulfurasQuality(): void {
        $items = [new Item(GildedRose::SULFURAS, 0, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(20, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itIncreasesQualityByTwoIfLessThanElevenDaysToSellLeft(): void {
        $items = [new Item(GildedRose::BACKSTAGE_PASSES, 9, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(22, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itIncreasesQualityByThreeIfLessThanSixDaysToSellLeft(): void {
        $items = [new Item(GildedRose::BACKSTAGE_PASSES, 4, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(23, $items[0]->quality);
    }

    /**
     * @test
     */
    public function itAlwaysIncreasesAgedBrieQuality(): void {
        $items = [new Item(GildedRose::AGED_BRIE, -1, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(21, $items[0]->quality);
    }

    /**
     * @test
    */
    public function itDecreasesQualityTwiceFasterWhenSellInDateExpires(): void {
        $items = [new Item('any', -1, 20)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        static::assertEquals(18, $items[0]->quality);
    }
}
