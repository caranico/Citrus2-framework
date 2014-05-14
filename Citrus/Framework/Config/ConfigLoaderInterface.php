<?php

namespace Citrus\Framework\Config;

interface ConfigLoaderInterface {

    public function load();

    public function getContent();

    public function parseContent($content);
}
