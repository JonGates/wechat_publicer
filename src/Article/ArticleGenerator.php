<?php

namespace Jongates\WechatPublicer\Article;

use Jongates\WechatPublicer\Templates\TemplateManager;

class ArticleGenerator {
	private $config;
	private $templateManager;

	public function __construct(array $config) {
		$this->config = $config;
		$this->templateManager = new TemplateManager($config['templates_path'] ?? null);
	}

	public function generateArticle(array $data): array {
		// 根据类型获取对应的模板
		$content = $this->templateManager->render($data['type'], $data);

		return [
			'title' => $data['title'] ?? '',
			'author' => $data['author'] ?? $this->config['nickname'] ?? '',
			'digest' => $data['digest'] ?? '',
			'content' => $content,
			'content_source_url' => $data['content_source_url'] ?? '',
			'need_open_comment' => $data['need_open_comment'] ?? 0,
			'only_fans_can_comment' => $data['only_fans_can_comment'] ?? 0,
		];
	}
}
