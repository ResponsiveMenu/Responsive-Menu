<?php

namespace ResponsiveMenuTest\View;
use \Twig_Environment;

class AdminView {

    protected $twig;

    public function __construct(Twig_Environment $twig) {
        $this->twig = $twig;
    }

    public function render($location, $options = []) {
        return $this->twig->render($location, $options);
    }

}
