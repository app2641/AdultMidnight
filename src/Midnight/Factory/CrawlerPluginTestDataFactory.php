<?php


namespace Midnight\Factory;

use Garnet\Factory\AbstractFactory;

use Midnight\Crawler\Plugin\TestData\AdultAdultTestData,
    Midnight\Crawler\Plugin\TestData\AdultGeekTestData;

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
}


