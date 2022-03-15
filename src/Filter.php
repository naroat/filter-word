<?php


namespace App\Packages\Fuckword\src;

class Filter
{

    private $dict;

    private $dfa;

    const FILTER_TYPE_IS = 0;
    const FILTER_TYPE_REPLACE = 1;

    public function __construct()
    {
        $this->dfa = new DFA();
        $this->dict = new Dict();
    }

    /**
     * 过滤
     *
     * @param $text
     * @param int $type
     * @return bool
     */
    public function filter($text, $type = self::FILTER_TYPE_IS)
    {
        $hashMap = $this->dict->getDictDFAContent();
        $this->dfa->setHashMap($hashMap);
        return $this->dfa->checkBadWord($text);
    }

}