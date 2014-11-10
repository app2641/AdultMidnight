<?php


namespace Midnight\Crawler;

class EntryManager
{

    /**
     * 渡されたエントリデータの配列を正規化する
     * 空のエントリデータは排除する
     *
     * @param  array $entry_data
     * @return array
     **/
    public function format (Array $entry_data)
    {
        $format_data = array();
        $url_stack   = array();

        foreach ($entry_data as $key => $data) {
            if (count($data) == 0) continue;

            // オブジェクトにキャスト
            if (is_array($data)) {
                $entry_data[$key] = $data = (object) $data;
            }

            if (isset($data->disable)) continue;
            if ($this->_validateValue($data->title) === false) continue;
            if ($this->_validateValue($data->eyecatch) === false) continue;
            if (count($data->movies) == 0) continue;
            if (count($data->movies) > 7) continue;
            
            if (! in_array($data->url, $url_stack)) {
                $format_data[] = $data;
                $url_stack[]   = $data->url;
            }
        }

        return $format_data;
    }


    /**
     * 与えられた値が空もしくはnullかどうかを判別する
     *
     * @param  mixed $value
     * @return boolean
     **/
    private function _validateValue ($value)
    {
        if (!is_null($value) &&
            $value !== false &&
            $value !== '') {
            return true;
        }

        return false;
    }
}

