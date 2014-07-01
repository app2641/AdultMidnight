<?php


namespace Midnight\Factory;

use Garnet\Factory\AbstractFactory;

use Midnight\Crawler\Plugin\AdultAdult,
    Midnight\Crawler\Plugin\AdultGeek,
    Midnight\Crawler\Plugin\AvSelection,
    Midnight\Crawler\Plugin\Baaaaaa,
    Midnight\Crawler\Plugin\Bikyaku,
    Midnight\Crawler\Plugin\Minna,
    Midnight\Crawler\Plugin\Youskbe;

class CrawlerPluginFactory extends AbstractFactory
{

    /**
     * @var AdultAdult
     **/
    public function buildAdultAdult ()
    {
        return new AdultAdult();
    }


    /**
     * @return AdultGeek
     **/
    public function buildAdultGeek ()
    {
        return new AdultGeek();
    }


    /**
     * @var AvSelection
     **/
    public function buildAvSelection ()
    {
        return new AvSelection();
    }


    /**
     * @var Baaaaaa
     **/
    public function buildBaaaaaa ()
    {
        return new Baaaaaa();
    }


    /**
     * @var Bikyaku
     **/
    public function buildBikyaku ()
    {
        return new Bikyaku();
    }


    /**
     * @var Minna
     **/
    public function buildMinna ()
    {
        return new Minna();
    }


    /**
     * @var Youskbe
     **/
    public function buildYouskbe ()
    {
        return new Youskbe();
    }
}

