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
	[
		'type' => 'miniprogram',
		'thumb_media_id' => '4uyfP5XSWLkLfwA93nlMsfAWFXcuixT1bnLry3da6kyMSPL9S4C-m9i4sekp1zcb',
		'title' => '测试1',
		'nickname' => 'app1名称',
		'author' => 'app1名称',
		'description' => '描述1',
		'digest' => '简介1',
		'app_id' => 'wxxxxxxxxxxxxxxxx',
		'url' => 'pages/Book/index?book_id=1',
		'click_str' => '点击免费阅读',
		'pic_1' => 'http://mmbiz.qpic.cn/mmbiz_jpg/pYlKxHMxWnQC8WhibshcibvgUMG7xic2WDnlTybQSApEVFAON3UTwt8Bribnp7mBpebxeTvIEadyibavhUzKToy6LpA/0?from=appmsg',
	]

type: 模板类型（必填），对应模板的文件名
thumb_media_id: 封面图片的media_id，优先使用，如果为空，则使用thumb_media_path，如果thumb_media_path为空，则使用默认封面图片
thumb_media_path: 封面图片路径
title: 标题（必填）
nickname: 公众号名称（必填）
author: 作者（必填）
digest: 简介
description: 描述
app_id: 小程序app_id
url: 小程序页面路径
click_str: 点击按钮文字

