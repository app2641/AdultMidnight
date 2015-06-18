<?php


namespace Midnight\Factory;

use Garnet\Factory\AbstractFactory;

use Midnight\Crawler\Plugin\AdultAdult,
    Midnight\Crawler\Plugin\AdultGeek,
    Midnight\Crawler\Plugin\AdultVideo,
    Midnight\Crawler\Plugin\AvSelection,
    Midnight\Crawler\Plugin\Baaaaaa,
    Midnight\Crawler\Plugin\Bikyaku,
    Midnight\Crawler\Plugin\Doesu,
    Midnight\Crawler\Plugin\Download,
    Midnight\Crawler\Plugin\Epusta,
    Midnight\Crawler\Plugin\EroEro,
    Midnight\Crawler\Plugin\Eromon,
    Midnight\Crawler\Plugin\EroOna,
    Midnight\Crawler\Plugin\HentaiAnime,
    Midnight\Crawler\Plugin\Ichizen,
    Midnight\Crawler\Plugin\Matome,
    Midnight\Crawler\Plugin\Minna,
    Midnight\Crawler\Plugin\Muryo,
    Midnight\Crawler\Plugin\MuryoEro,
    Midnight\Crawler\Plugin\Rakuen,
    Midnight\Crawler\Plugin\Shikosen,
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
     * @return AdultVideo
     **/
    public function buildAdultVideo ()
    {
        return new AdultVideo();
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
     * @var Epusta
     **/
    public function buildEpusta ()
    {
        return new Epusta();
    }


    /**
     * @var EroEro
     **/
    public function buildEroEro ()
    {
        return new EroEro();
    }


    /**
     * @var Eromon
     **/
    public function buildEromon ()
    {
        return new Eromon();
    }


    /**
     * @var EroOna
     **/
    public function buildEroOna ()
    {
        return new EroOna();
    }


    /**
     * @var HentaiAnime
     */
    public function buildHentaiAnime ()
    {
        return new HentaiAnime();
    }


    /**
     * @var Ichizen
     */
    public function buildIchizen ()
    {
        return new Ichizen();
    }


    /**
     * @var Matome
     **/
    public function buildMatome ()
    {
        return new Matome();
    }


    /**
     * @return Minna
     **/
    public function buildMinna ()
    {
        return new Minna();
    }


    /**
     * @var Muryo
     **/
    public function buildMuryo ()
    {
        return new Muryo();
    }


    /**
     * @var MuryoEro
     **/
    public function buildMuryoEro ()
    {
        return new MuryoEro();
    }


    /**
     * @var Rakuen
     **/
    public function buildRakuen ()
    {
        return new Rakuen();
    }


    /**
     * @return Shikosen
     **/
    public function buildShikosen ()
    {
        return new Shikosen();
    }


    /**
     * @return Youskbe
     **/
    public function buildYouskbe ()
    {
        return new Youskbe();
    }
}

