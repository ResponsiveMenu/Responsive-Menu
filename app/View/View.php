<?php

namespace ResponsiveMenu\View;
use \Twig_Environment;

class View {

    protected $twig;

    public function __construct(Twig_Environment $twig) {
        $this->twig = $twig;
    }

    public function render($location, $options = []) {
        return $this->twig->render($location, $options);
    }

}
