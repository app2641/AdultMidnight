<?php


namespace Midnight\Factory;

use Garnet\Factory\AbstractFactory;

use Midnight\Crawler\Plugin\TestData\AdultAdultTestData,
    Midnight\Crawler\Plugin\TestData\AdultGeekTestData,
    Midnight\Crawler\Plugin\TestData\AvSelectionTestData,
    Midnight\Crawler\Plugin\TestData\BaaaaaaTestData,
    Midnight\Crawler\Plugin\TestData\BikyakuTestData,
    Midnight\Crawler\Plugin\TestData\DoesuTestData,
    Midnight\Crawler\Plugin\TestData\DownloadTestData,
    Midnight\Crawler\Plugin\TestData\EpustaTestData,
    Midnight\Crawler\Plugin\TestData\MatomeTestData,
    Midnight\Crawler\Plugin\TestData\MinnaTestData,
    Midnight\Crawler\Plugin\TestData\MuryoTestData,
    Midnight\Crawler\Plugin\TestData\RakuenTestData,
    Midnight\Crawler\Plugin\TestData\ShikosenTestData,
    Midnight\Crawler\Plugin\TestData\YouskbeTestData;

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


    /**
     * @return DoesuTestData
     */
    public function buildDoesu ()
    {
        return new DoesuTestData();
    }


    /**
     * @return DownloadTestData
     */
    public function buildDownload ()
    {
        return new DownloadTestData();
    }


    /**
     * @return EpustaTestData
     */
    public function buildEpusta ()
    {
        return new EpustaTestData();
    }


    /**
     * @return MatomeTestData
     */
    public function buildMatome ()
    {
        return new MatomeTestData();
    }


    /**
     * @return MinnaTestData
     */
    public function buildMinna ()
    {
        return new MinnaTestData();
    }


    /**
     * @return MuryoTestData
     */
    public function buildMuryo ()
    {
        return new MuryoTestData();
    }


    /**
     * @return RakuenTestData
     */
    public function buildRakuen ()
    {
        return new RakuenTestData();
    }


    /**
     * @return ShikosenTestData
     */
    public function buildShikosen ()
    {
        return new ShikosenTestData();
    }


    /**
     * @return YouskbeTestData
     */
    public function buildYouskbe ()
    {
        return new YouskbeTestData();
    }
}


