<?php
namespace Jongates\WechatPublicer\Templates;

class TemplateManager
{
    private $templatesPath;
    private $defaultPath;
    private $context = [];

    public function __construct(string $templatesPath = null)
    {
        $this->templatesPath = $templatesPath;
        $this->defaultPath = dirname(__DIR__) . '/templates_gzh';
    }

    public function getTemplate(string $name): string
    {
        if ($this->templatesPath) {
            $customPath = rtrim($this->templatesPath, '/') . '/' . $name . '.html';
            if (file_exists($customPath)) {
                $content = @file_get_contents($customPath);
                if ($content === false) {
                    throw new \Exception("无法读取模板文件: {$customPath}");
                }
                return $content;
            }
        }

        $defaultPath = $this->defaultPath . '/' . $name . '.html';
        if (file_exists($defaultPath)) {
            $content = @file_get_contents($defaultPath);
            if ($content === false) {
                throw new \Exception("无法读取模板文件: {$defaultPath}");
            }
            return $content;
        }

        throw new \Exception("模板文件不存在: {$name}");
    }

    public function render(string $name, array $data = []): string
    {
        $this->context = $data;
        $template = $this->getTemplate($name);

        // 递归处理嵌套的条件和循环
        $template = $this->parseTemplate($template);

        return $template;
    }

    private function parseTemplate(string $template): string
    {
        // 先处理条件
        $template = $this->handleConditions($template);

        // 再处理循环
        $template = $this->handleLoops($template);

        // 最后处理变量
        return $this->handleVariables($template);
    }

    private function handleConditions(string $template): string
    {
        return preg_replace_callback(
            '/\{\%\s*if\s+(.+?)\s*\%\}(.*?)\{\%\s*endif\s*\%\}/s',
            function($matches) {
                $condition = $this->evaluateCondition($matches[1]);
                $content = $matches[2];

                $parts = explode('{% else %}', $content);

                if ($condition) {
                    return $this->parseTemplate($parts[0]);
                } else {
                    return $this->parseTemplate($parts[1] ?? '');
                }
            },
            $template
        );
    }

    private function evaluateCondition(string $condition): bool
    {
        // 支持基本的比较操作
        if (strpos($condition, ' == ') !== false) {
            list($left, $right) = explode(' == ', $condition);
            return $this->getValue(trim($left)) == $this->getValue(trim($right));
        }

        if (strpos($condition, ' > ') !== false) {
            list($left, $right) = explode(' > ', $condition);
            return $this->getValue(trim($left)) > $this->getValue(trim($right));
        }

        // 简单变量判断
        return (bool)$this->getValue($condition);
    }

    private function getValue(string $key)
    {
        if ($key === 'true') return true;
        if ($key === 'false') return false;
        if (is_numeric($key)) return $key;

        return $this->context[$key] ?? null;
    }

    private function handleLoops(string $template): string
    {
        return preg_replace_callback(
            '/\{\%\s*for\s+(\w+)\s+in\s+(\w+)\s*\%\}(.*?)\{\%\s*endfor\s*\%\}/s',
            function($matches) {
                $itemName = $matches[1];
                $arrayName = $matches[2];
                $content = $matches[3];

                if (!isset($this->context[$arrayName]) || !is_array($this->context[$arrayName])) {
                    return '';
                }

                $result = '';
                $originalContext = $this->context;

                foreach ($this->context[$arrayName] as $item) {
                    $this->context[$itemName] = $item;
                    $result .= $this->parseTemplate($content);
                }

                $this->context = $originalContext;
                return $result;
            },
            $template
        );
    }

    private function handleVariables(string $template): string
    {
        return preg_replace_callback(
            '/\{\{\s*(.+?)\s*\}\}/',
            function($matches) {
                $path = trim($matches[1]);
                return $this->getNestedValue($path);
            },
            $template
        );
    }

    private function getNestedValue(string $path)
    {
        $keys = explode('.', $path);
        $value = $this->context;

        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return '';
            }
            $value = $value[$key];
        }

        return htmlspecialchars((string)$value, ENT_QUOTES);
    }
}
