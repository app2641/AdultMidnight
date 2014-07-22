<?php


use Midnight\Crawler\EntryManager;

class EntryManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EntryManager
     **/
    private $manager;


    /**
     * @var array
     **/
    private $entry_data;


    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->manager = new EntryManager();

        $data = file_get_contents(ROOT.'/data/fixtures/entry_data.json');
        $this->entry_data = json_decode($data);
    }


    /**
     * @test
     * @group entry-empty-array
     * @group entry
     */
    public function 空の配列を処理しようとした場合 ()
    {
        $result = $this->manager->format(array());
        $this->assertTrue(is_array($result));
        $this->assertEquals(0, count($result));
    }


    /**
     * @test
     * @group entry-format
     * @group entry
     */
    public function エントリデータを正規化する ()
    {
        $result = $this->manager->format($this->entry_data);
        $this->assertTrue(is_array($result));
        $this->assertEquals(16, count($result));
    }
}

