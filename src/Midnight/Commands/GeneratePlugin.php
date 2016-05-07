<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

class GeneratePlugin extends AbstractCommand implements CommandInterface
{
    /**
     * @var array
     **/
    private $params;

    /**
     * @var string
     **/
    private $name;

    /**
     * @var boolean
     **/
    private $copied_plugin = false;

    /**
     * @var boolean
     **/
    private $copied_plugin_test = false;

    /**
     * @var boolean
     **/
    private $copied_test_data = false;

    /**
     * @var boolean
     **/
    private $copied_test_data_test = false;

    /**
     * コマンドの実行
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function execute (Array $params)
    {
        try {
            $this->params = $params;
            $this->_validateParameters();

            $this->_copyPluginClassSkeleton();
            $this->log('generated plugin!', 'success');

            $this->_copyPluginTestClassSkeleton();
            $this->log('generated test plugin!', 'success');

            $this->_copyTestDataClassSkeleton();
            $this->log('generated plugin test data!', 'success');

            $this->_copyTestDataTestClassSkeleton();
            $this->log('generated plugin test data test!', 'success');

        } catch (\Exception $e) {
            $this->_rollBackCopiedFiles();
            $this->errorLog($e->getMessage());
        }
    }

    /**
     * パラメータのバリデート
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        array_shift($this->params);

        if (! isset($this->params[0])) {
            throw new \Exception('プラグイン名を指定してください');
        }

        $this->name = ucfirst($this->params[0]);
    }

    /**
     * 指定パスのファイルがあるかどうかを判別する
     *
     * @param  string $path
     **/
    private function _validateFileExists ($path)
    {
        if (file_exists($path)) {
            throw new \Exception($path.' は既に存在します');
        }
    }

    /**
     * プラグインクラスのパスを生成する
     *
     * @return string
     **/
    private function _getPluginPath ()
    {
        return sprintf(ROOT.'/src/Midnight/Crawler/Plugin/%s.php', $this->name);
    }

    /**
     * プラグインテストクラスのパスを生成する
     *
     * @return string
     **/
    private function _getPluginTestPath ()
    {
        return sprintf(ROOT.'/tests/Midnight/Crawler/Plugin/%sTest.php', $this->name);
    }

    /**
     * テストデータクラスのパスを生成する
     *
     * @return string
     **/
    private function _getTestDataPath ()
    {
        return sprintf(ROOT.'/src/Midnight/Crawler/Plugin/TestData/%sTestData.php', $this->name);
    }

    /**
     * テストデータテストクラスのパスを生成する
     *
     * @return string
     **/
    private function _getTestDataTestPath ()
    {
        return sprintf(ROOT.'/tests/Midnight/Crawler/Plugin/TestData/%sTestDataTest.php', $this->name);
    }

    /**
     * プラグインクラスのスケルトンファイルをコピーする
     *
     * @return void
     **/
    private function _copyPluginClassSkeleton ()
    {
        // 既にファイルがないかを確認する
        $this->_validateFileExists($this->_getPluginPath());

        $skeleton_path = ROOT.'/data/crawler/skeleton/PluginSkeleton.php';
        $skeleton = file_get_contents($skeleton_path);

        $skeleton = str_replace('${name}', $this->name, $skeleton);
        file_put_contents($this->_getPluginPath(), $skeleton);

        $this->copied_plugin = true;
    }

    /**
     * プラグインテストクラスのスケルトンをコピーする
     *
     * @return void
     **/
    private function _copyPluginTestClassSkeleton ()
    {
        // 既にファイルがないかを確認する
        $this->_validateFileExists($this->_getPluginTestPath());

        $skeleton_path = ROOT.'/data/crawler/skeleton/PluginTestSkeleton.php';
        $skeleton = file_get_contents($skeleton_path);

        $group = strtolower($this->name);
        $skeleton = str_replace('${name}', $this->name, $skeleton);
        $skeleton = str_replace('${group}', $group, $skeleton);
        file_put_contents($this->_getPluginTestPath(), $skeleton);

        $this->copied_plugin_test = true;
    }

    /**
     * テストデータクラスのスケルトンをコピーする
     *
     * @return void
     **/
    private function _copyTestDataClassSkeleton ()
    {
        // 既にファイルがないかを確認する
        $this->_validateFileExists($this->_getTestDataPath());

        $skeleton_path = ROOT.'/data/crawler/skeleton/PluginTestDataSkeleton.php';
        $skeleton = file_get_contents($skeleton_path);

        $group = strtolower($this->name);
        $skeleton = str_replace('${name}', $this->name, $skeleton);
        $skeleton = str_replace('${group}', $group, $skeleton);
        file_put_contents($this->_getTestDataPath(), $skeleton);

        $this->copied_test_data = true;
    }

    /**
     * テストデータテストクラスのスケルトンをコピーする
     *
     * @return void
     **/
    private function _copyTestDataTestClassSkeleton ()
    {
        // 既にファイルがないかを確認する
        $this->_validateFileExists($this->_getTestDataTestPath());

        $skeleton_path = ROOT.'/data/crawler/skeleton/PluginTestDataTestSkeleton.php';
        $skeleton = file_get_contents($skeleton_path);

        $group = strtolower($this->name);
        $skeleton = str_replace('${name}', $this->name, $skeleton);
        $skeleton = str_replace('${group}', $group, $skeleton);
        file_put_contents($this->_getTestDataTestPath(), $skeleton);


        $this->copied_test_data_test = true;
    }

    /**
     * 作成してしまったファイルを削除する
     *
     * @return void
     **/
    private function _rollBackCopiedFiles ()
    {
        // Pluginファイル
        if ($this->copied_plugin) {
            unlink($this->_getPluginPath());
        }

        // PluginTestファイル
        if ($this->copied_plugin_test) {
            unlink($this->_getPluginTestPath());
        }

        // TestDataファイル
        if ($this->copied_test_data) {
            unlink($this->_getTestDataPath());
        }

        // TestDataTestファイル
        if ($this->copied_test_data_test) {
            unlink($this->_getTestDataTestPath());
        }
    }

    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return 'クローラプラグインのスケルトンを生成する';
    }
}
