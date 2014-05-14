<?php

namespace Citrus\Framework\Routing;
use Citrus\Framework\Config\ConfigLoaderInterface;

class RoutingConfigLoader implements ConfigLoaderInterface {
    protected $content;
    protected $path;
    protected $parsed = false;

    public function __construct($path, $load = false) {
        $this->path = $path;
        $this->load();
    }

    public function load() {
        $this->content = Array();
        if (!file_exists($this->path)) {
            throw new \InvalidArgumentException(sprintf("Routing file '%s' doesn't exist. Check your configuration", $this->path));
        }
        if (!is_readable($this->path)) {
            throw new \InvalidArgumentException(sprintf("Unable to read routing file '%s'.", $this->path));
        }
        $raw = file_get_contents($this->path);
        $this->content = $this->parseContent( $raw );

    }

    public function parseConfigFile() {
        $routes = $this->content;

        foreach ($routes as $name => $params) {
            if (array_key_exists("url", $params) && array_key_exists("target", $params)) {
                $routes->add($name, new Route($params['url'], Array(
                    "_controller" => $params['target']
                )));
            }
        }
        $this->routes = $routes;
        return $this->routes;
    }

    public function setPath( $path ) {
        $this->path = $path;
    }

    public function getPath() {
        return $this->path;
    }

    public function setContent( $content ) {
        $this->content = $content;
    }

    public function getContent() {
        if (!$this->parsed) $this->content = $this->parseContent( $this->content );
        return $this->content;
    }

    public function parseContent($raw) {
        if ($raw === "") {
            return Array();
        }
        $content = json_decode($raw, true);
        if ($content === null) {
            $error = json_last_error();
            switch ($error) {
                case JSON_ERROR_DEPTH:
                    $error_msg = "The maximum stack depth has been exceeded";
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error_msg = "Invalid or malformed JSON";
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error_msg = "Control character error, possibly incorrectly encoded";
                    break;
                case JSON_ERROR_SYNTAX:
                    $error_msg = "Syntax error";
                    break;
                case JSON_ERROR_UTF8:
                    $error_msg = "Malformed UTF-8 characters, possibly incorrectly encoded";
                    break;
                default:
                    $error_msg = "unknown JSON error.";
                break;
            }
            throw new \Exception(sprintf("Error while parsing JSON file %s : %s", $this->path, $error_msg));
        }
        $this->parsed = true;
        return $content;
    }

}
