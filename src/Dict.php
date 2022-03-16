<?php


namespace Taoran\FilterWord;

/**
 * 词库管理
 *
 * Class Dict
 * @package App\Packages\Fuckword\src
 */
class Dict
{
    /**
     * 词库文件
     *
     * @var
     */
    public $dictFile;

    /**
     * 词库的DFA节点树文件
     *
     * @var string
     */
    public $dictDFAFile;

    /**
     * DFA obj
     *
     * @var DFA
     */
    protected $dfa;

    /**
     * 词库内容
     *
     * @var
     */
    public $dictContent;

    /**
     * 词库节点树内容 - hashMap
     *
     * @var
     */
    public $dictDFAContent;

    public function __construct()
    {
        $this->dfa = new DFA();
    }

    /**
     * 获取词库内容
     *
     * @return mixed
     */
    public function getDictContent()
    {
        if (empty($this->dictContent)) {
            $this->dictContent = require_once $this->dictFile;
        }
        if (!is_array($this->dictContent)) {
            $this->dictContent = [];
        }
        return $this->dictContent;
    }

    /**
     * 获取词库hashMap
     *
     * @return array|mixed
     */
    public function getDictDFAContent()
    {
        if (empty($this->dictDFAContent)) {
            $this->dictDFAContent = require_once $this->dictDFAFile;
        }
        if (!is_array($this->dictDFAContent)) {
            $this->dictDFAContent = [];
        }
        return $this->dictDFAContent;
    }

    /**
     * 添加词汇
     *
     * @param array $words
     * @return bool
     */
    public function add(array $words)
    {
        $dict = $this->getDictContent();
        foreach ($words as $word) {
            if (empty($dict[$word])) {
                array_push($dict, $word);
            }
        }
        $this->dictContent = array_values(array_filter(array_unique($dict)));
        $dict = '<?php return ' . var_export($this->dictContent, true) . ';';
        if (!file_put_contents($this->dictFile, $dict)) {
            return false;
        }
        $this->reset($this->dictContent);
        return true;
    }

    /**
     * 删除词汇
     *
     * @param array $words
     * @return bool
     */
    public function destroy(array $words)
    {
        $dict = $this->getDictContent();
        if (empty($dict)) {
            //词库无内容
            return true;
        }
        foreach ($words as $key => $word) {
            $search = array_search($word, $dict);
            if ($search !== false) {
                unset($dict[$search]);
            }
        }
        $this->dictContent = array_values(array_filter(array_unique($dict)));
        $dict = '<?php return ' . var_export($this->dictContent, true) . ';';
        if (!file_put_contents($this->dictFile, $dict)) {
            return false;
        }
        $this->reset($this->dictContent);
        return true;
    }

    /**
     * 重置词库
     *
     * @return bool
     */
    public function reset($dictContent = null)
    {
        $dict = $dictContent;
        if (empty($dict)) {
            $dict = $this->getDictContent();
        }
        $this->dfa->setHashMap([]);
        foreach ($dict as $word) {
            $this->dfa->add($word);
        }
        $hashMap = '<?php return ' . var_export($this->dfa->getHashMap(), true) . ';';
        if (file_put_contents($this->dictDFAFile, $hashMap)) {
            return false;
        }
        return true;
    }

    /**
     * 获取词库文件
     *
     * @return string
     */
    public function getDictFile()
    {
        return $this->dictFile;
    }

    /**
     * 设置词库文件
     *
     * @param $dictFile
     * @return $this
     */
    public function setDictFile($path, $file)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $this->dictFile = $path . $file;
        $this->dictDFAFile = $path . md5($file) . '.php';
        if (!file_exists($this->dictFile)) {
            file_put_contents($this->dictFile, '');
        }
        if (!file_exists($this->dictDFAFile)) {
            file_put_contents($this->dictDFAFile, '');
        }
    }
}