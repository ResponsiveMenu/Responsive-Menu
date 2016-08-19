<?php

namespace ResponsiveMenu\Translation;

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Collections\OptionsCollection;
use ResponsiveMenu\Models\Option;

class TranslatorTest extends TestCase {

  public static function setUpBeforeClass() {
    function apply_filters($a, $b, $c, $d) {
      return $b;
    }
    function get_home_url() {
      return 'home';
    }
    function do_action($a, $b, $c, $d) {
      return $d;
    }
    function pll__($a) {
      return $a;
    }
  }
  public function setUp() {

    $this->translator = new Translator;
  }

  public function testSearchUrl() {
    $this->assertEquals('home', $this->translator->searchUrl());
  }

  public function testSaveTranslations() {
    $collection = new OptionsCollection;
    $collection->add(new Option('menu_additional_content', 'a'));
    $this->assertNull($this->translator->saveTranslations($collection));
  }

  public function testTranslate() {
    $this->assertEquals('a', $this->translator->translate(new Option('menu_additional_content', 'a')));
  }

}
