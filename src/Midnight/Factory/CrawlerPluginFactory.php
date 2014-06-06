<?php


namespace Midnight\Factory;

use Garnet\Factory\AbstractFactory;

use Midnight\Crawler\Plugin\AdultGeek;

class CrawlerPluginFactory extends AbstractFactory
{

    /**
     * @return AdultGeek
     **/
    public function buildAdultGeek ()
    {
        return new AdultGeek;
    }
}

