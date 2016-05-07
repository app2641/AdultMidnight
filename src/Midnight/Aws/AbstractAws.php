<?php

namespace Midnight\Aws;

use Aws\Common\Enum\Region;

abstract class AbstractAws
{
    /**
     * クライアントクラス
     *
     * @var mixed
     **/
    protected $client;

    /**
     * aws.iniへのパス
     *
     * @var string
     **/
    protected $aws_ini_path = 'data/config/aws.ini';

    /**
     * aws.iniのパースデータ
     *
     * @var array
     **/
    protected $ini;

    /**
     * コンストラクタ
     *
     * @return void
     */
    public function __construct ()
    {
        $this->_initIniFile();
    }

    /**
     * iniファイルを解析してメンバ変数に保存する
     *
     * @return void
     */
    private function _initIniFile ()
    {
        $this->ini = parse_ini_file($this->aws_ini_path);
    }

    /**
     * クライアントクラス生成に必要なコンフィグ値を配列で返す
     *
     * @param  string $region
     * @return array
     **/
    public function getConfig ($region = Region::AP_NORTHEAST_1)
    {
        return array(
            'key' => $this->ini['key'],
            'secret' => $this->ini['secret'],
            'region' => $region
        );
    }
}
