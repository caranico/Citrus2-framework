<?php

namespace Citrus\Framework\Application;


class Context
{
    public function __construct(Array $options)
    {
        $default_options = Array(
            "dir"              => "",
            "config_dir"       => "/config",
            "routing_file"     => "/routing.json",
            "config_file"      => "/config.json",
            "views_dir"        => "/views",
            "static_dir"       => "/static",
            "cache_dir"        => "/cache",
            "controllers_dir"  => "/controllers",
        );
        $options = array_merge($default_options, $options);

        // echo sprintf("%s%s%s", $options['dir'], $options['config_dir'], $options['routing_file']);
    }
}
