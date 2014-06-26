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
        foreach ($entry_data as $data) {
            if (is_array($data)) continue;
            
            $format_data[] = $data;
        }

        return $format_data;
    }
}

