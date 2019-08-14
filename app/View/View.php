<?php

namespace ResponsiveMenu\View;
use \Twig\Environment;

class View {

    protected $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    public function render($location, $options = []) {
        return $this->twig->render($location, $options);
    }

}
