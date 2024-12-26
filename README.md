# wechat_publicer
wechat_publicer


## 注意事项

使用说明：
1. 安装
composer require jongates/wechat_publicer

2. 制作模板
模板文件放在templates_gzh目录下，文件名对应type，文件内容为html模板，模板中使用{key}表示变量，key为模板信息中的key，详见模板配置说明
模板文件示例，miniprogram.html

3. 引入
use Jongates\WechatPublicer\Publisher;

4. 初始化
$publisher = new Publisher($config);

5. 发布
$publisher->publishArticle($data);

6. 分开发布
$publisher->generateDraft($data);
$publisher->saveDraft();
$publisher->publishDraft();

模板配置说明：
引用参考：example.php
模板参考：templates_gzh/miniprogram.html



