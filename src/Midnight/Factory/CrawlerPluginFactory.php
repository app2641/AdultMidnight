<?php


namespace Midnight\Factory;

use Garnet\Factory\AbstractFactory;

use Midnight\Crawler\Plugin\AdultAdult,
    Midnight\Crawler\Plugin\AdultGeek,
    Midnight\Crawler\Plugin\AvSelection,
    Midnight\Crawler\Plugin\Baaaaaa,
    Midnight\Crawler\Plugin\Bikyaku,
    Midnight\Crawler\Plugin\Doesu,
    Midnight\Crawler\Plugin\Download,
    Midnight\Crawler\Plugin\Minna,
    Midnight\Crawler\Plugin\Youskbe;

class CrawlerPluginFactory extends AbstractFactory
{

    /**
     * @return AdultAdult
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
     * @return AvSelection
     **/
    public function buildAvSelection ()
    {
        return new AvSelection();
    }


    /**
     * @return Baaaaaa
     **/
    public function buildBaaaaaa ()
    {
        return new Baaaaaa();
    }


    /**
     * @return Bikyaku
     **/
    public function buildBikyaku ()
    {
        return new Bikyaku();
    }


    /**
     * @return Doesu
     **/
    public function buildDoesu ()
    {
        return new Doesu();
    }


    /**
     * @return Download
     **/
    public function buildDownload ()
    {
        return new Download();
    }


    /**
     * @return Minna
     **/
    public function buildMinna ()
    {
        return new Minna();
    }


    /**
     * @return Youskbe
     **/
    public function buildYouskbe ()
    {
        return new Youskbe();
    }
}

