<?php

namespace Citrus\Framework\View;

class TemplateResolver {

    public function getTemplate($request) {
        if (!$controller = $request->attributes->get('_controller')) {
            return false;
        }

        list($app, $class, $method) = explode('/', $controller, 3);
        return $method;

    }
}
