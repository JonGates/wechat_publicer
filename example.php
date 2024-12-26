<?php

require_once __DIR__ . '/vendor/autoload.php';

use Jongates\WechatPublicer\Publisher\Publisher;

// 配置信息
function cs_cs(): void
{
	$config = [
		'app_id' => 'xxxxxxxxxxxxxxxx', // 公众号appid
		'app_secret' => 'xxxxxxxxxxxxxxxxxxxxxxx', // 公众号appsecret
		'nickname' => 'app名称', // 公众号名称
		'cache_path' => '../../temp/gzh', // 缓存路径
		'templates_path' => '../../templates_gzh', // 模板路径
		'default_thumb_media_id' => '4uyfP5XSWLkLfwA93nlMsfAWFXcuixT1bnLry3da6kyMSPL9S4C-m9i4sekp1zcb', // 全局默认封面图片，最后使用
	];
	$wechatPublicer = new Publisher($config);

	// 多组数据
	$templates = [
		[
			'type' => 'miniprogram',
			'title' => '测试1',
			'description' => '描述1',
			'digest' => '简介1',
			'thumb_media_id' => '4uyfP5XSWLkLfwA93nlMsfAWFXcuixT1bnLry3da6kyMSPL9S4C-m9i4sekp1zcb',// 封面图片id,优先使用
			'thumb_media_path' => 'https://www.baidu.com/img/PCfb_5bf082d29588c07f842ccde3f97243ea.png',// 封面图片路径，可远程可本地,第二使用
			// 非公众号字段：
			'items' => array(
				1 => array(
					'name' => '《1》',
					'price' => '免费',
				),
				2 => array(
					'name' => '《2》',
					'price' => '免费',
				),
				3 => array(
					'name' => '《3》',
					'price' => '免费',
				),
			),
		],
		[
			'type' => 'miniprogram',
			'thumb_media_id' => '4uyfP5XSWLkLfwA93nlMsfAWFXcuixT1bnLry3da6kyMSPL9S4C-m9i4sekp1zcb',
			'thumb_media_path' => 'https://www.baidu.com/img/PCfb_5bf082d29588c07f842ccde3f97243ea.png',
			'title' => '测试2',
			'description' => '描述2',
			'digest' => '简介2',
			// 非公众号字段：
			'items' => array(
				1 => array(
					'name' => '《1》',
					'price' => '免费',
				),
				2 => array(
					'name' => '《2》',
					'price' => '免费',
				),
				3 => array(
					'name' => '《3》',
					'price' => '免费',
				),
			),
		]
	];

	try {
		// 生成草稿
		$wechatPublicer->generateDraft($templates);
		// 保存草稿
		$wechatPublicer->saveDraft();
		// 发布草稿
		$wechatPublicer->publishDraft();
	} catch (\Exception $e) {
		echo $e->getMessage();
	}
}
