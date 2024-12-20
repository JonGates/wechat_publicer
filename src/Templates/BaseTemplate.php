<?php
namespace WechatPublicer\Templates;

abstract class BaseTemplate {
    public $author;
    
    abstract public function render($data);
} 