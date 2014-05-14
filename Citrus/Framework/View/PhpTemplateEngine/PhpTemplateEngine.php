<?php

namespace Citrus\Framework\View\PhpTemplateEngine;
use Citrus\Framework\View\TemplateEngineInterface;

class PhpTemplateEngine implements TemplateEngineInterface {

    protected $template;
    static protected $extension = ".html.php";

    public function __construct($request) {

    }

    public function renderResponse($view, $args, $response)
    {

    }

    public function render($args) {
        if (!file_exists($this->template)) {
            throw new \InvalidArgumentException(sprintf("Unable to find template %s", $this->template));
        }
        if (is_array($args)) extract($args);
        ob_start();
        include $this->template;
        return ob_get_clean();
    }

    public function loadTemplate($name) {
        $this->template = $name . static::$extension;
    }
}
