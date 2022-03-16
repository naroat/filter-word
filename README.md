## 非法词/敏感词过滤

非法词/敏感词过滤是网站必不可少的功能，一般通过正则和字符串函数来计算和判断，效率比较慢；所以我们必须减少计算量来提高效率，而DFA算法中几乎没有计算量，有的只是状态转移； 

## 功能
- 词库和节点树管理功能
- 判断是否有敏感词(暂时只有判断是否有敏感词，没有替换功能，有需求再加)

## 使用方式

判断敏感词：
```
$filter = new Filter();
$filter->dict->setDictFile(BASE_PATH . '/public/dict/', 'dict.php');
$result = $filter->filter($text);    //return bool: true 表示有敏感词
```

词库管理：
```
$dict = new Dict();
//设置词库文件两个参数： 路径和文件
$dict->setDictFile(BASE_PATH . '/public/dict/', 'dict.php');
// 添加词汇
$addResult = $dict->add(['词汇一', '词汇二', '词汇五']);
// 删除词汇
$delResult = $dict->destroy(['词汇一']);
// 获取词库内容
$dictContent = $dict->getDictContent();
// 获取词库节点树
$dictDFAContent = $dict->getDictDFAContent();
```

