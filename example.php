<?php

require_once __DIR__ . '/vendor/autoload.php';

use Jongates\WechatPublicer\Publisher\Publisher;

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

	// 多组数据
	$templates = [
		[
			'type' => 'miniprogram',
			'thumb_media_id' => '4uyfP5XSWLkLfwA93nlMsfAWFXcuixT1bnLry3da6kyMSPL9S4C-m9i4sekp1zcb',
			'title' => '测试1',
			'content' => '测试1内容',
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
			'title' => '测试2',
			'content' => '测试2内容',
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
