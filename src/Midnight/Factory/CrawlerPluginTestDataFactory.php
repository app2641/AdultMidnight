<?php


namespace Midnight\Factory;

use Garnet\Factory\AbstractFactory;

use Midnight\Crawler\Plugin\TestData\AdultAdultTestData,
    Midnight\Crawler\Plugin\TestData\AdultGeekTestData,
    Midnight\Crawler\Plugin\TestData\AvSelectionTestData,
    Midnight\Crawler\Plugin\TestData\BaaaaaaTestData,
    Midnight\Crawler\Plugin\TestData\BikyakuTestData;

class CrawlerPluginTestDataFactory extends AbstractFactory
{

    /**
     * @return AdultAdultTestData
     **/
    public function buildAdultAdult ()
    {
        return new AdultAdultTestData();
    }


    /**
     * @return AdultGeekTestData
     **/
    public function buildAdultGeek ()
    {
        return new AdultGeekTestData();
    }


    /**
     * @return AvSelectionTestData
     **/
    public function buildAvSelection ()
    {
        return new AvSelectionTestData();
    }


    /**
     * @return BaaaaaaTestData
     **/
    public function buildBaaaaaa ()
    {
        return new BaaaaaaTestData();
    }


    /**
     * @return BikyakuTestData
     **/
    public function buildBikyaku ()
    {
        return new BikyakuTestData();
    }
}


