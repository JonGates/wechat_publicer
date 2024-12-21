<?php

require_once __DIR__ . '/vendor/autoload.php';

use WechatPublicer\Publisher\Publisher;

// 配置信息
function cs_cs(): void
{
	$config = [
		'app_id' => 'xxxxxxxxxxxxxxxx',
		'app_secret' => 'xxxxxxxxxxxxxxxxxxxxxxx',
		'nickname' => 'app名称',
		'cache_path' => '../../temp/gzh',
		'templates_path' => '../../templates_gzh',

		'encoding_aes_key' => '55555555555555555555555555555555',
	];
	$wechatPublicer = new Publisher($config);

	$templates = [
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
		],
		[
			'type' => 'miniprogram',
			'thumb_media_id' => '4uyfP5XSWLkLfwA93nlMsfAWFXcuixT1bnLry3da6kyMSPL9S4C-m9i4sekp1zcb',
			'title' => '测试2',
			'nickname' => 'app2名称',
			'author' => 'app2名称',
			'description' => '描述2',
			'digest' => '简介2',
			'app_id' => 'wxxxxxxxxxxxxxxxx',
			'url' => 'pages/Book/index?book_id=2',
			'click_str' => '点击免费阅读',
			'pic_1' => 'http://mmbiz.qpic.cn/mmbiz_jpg/pYlKxHMxWnQC8WhibshcibvgUMG7xic2WDnlTybQSApEVFAON3UTwt8Bribnp7mBpebxeTvIEadyibavhUzKToy6LpA/0?from=appmsg',
		]
	];

	try {
		$wechatPublicer->publishArticleWithImage($templates);
	} catch (\Exception $e) {
		echo $e->getMessage();
	}
}
