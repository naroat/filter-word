<?php

namespace Taoran\FilterWord;

class DFA
{
    private $hashMap = [];

    private $encoding = 'UTF-8';

    /**
     * 获取hashMap
     *
     * @return array
     */
    public function getHashMap()
    {
        return $this->hashMap;
    }

    /**
     * 设置hashMap
     */
    public function setHashMap($hashMap)
    {
        $this->hashMap = $hashMap;
    }

    /**
     * 添加词汇到hashMap
     *
     * @param $word
     * @return bool
     */
    public function add($word)
    {
        $len = mb_strlen($word, $this->encoding);
        $tmpHashMap = &$this->hashMap;
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($word, $i, 1, $this->encoding);
            $is_end = ($i == $len - 1) ? 1 : 0;
            $tmpHashMap[$char]['is_end'] = $is_end;
            $tmpHashMap = &$tmpHashMap[$char];
        }
        return true;
    }

    /**
     * 查询是否有敏感词
     *
     * @param $text
     * @return bool
     */
    public function checkBadWord($text)
    {
        $len = mb_strlen($text, $this->encoding);
        $tmpHashMap = $this->hashMap;
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1, $this->encoding);
            if (empty($tmpHashMap[$char])) {
                $tmpHashMap = $this->hashMap;
                continue;
            }
            if ($tmpHashMap[$char]['is_end'] == 1) {
                return true;
            }
            $tmpHashMap = $tmpHashMap[$char];
        }
        return false;
    }
}