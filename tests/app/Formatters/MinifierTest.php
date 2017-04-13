<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenuTest\Formatters\Minifier;

class MinifierTest extends TestCase {
    
    public function testSimpleCSSWhitespaceRemovalTest() {
        $minifier = new Minifier;
        $this->assertEquals('.class{}', $minifier->minify('.class {  }'));
    }

    public function testSimpleCSSCommentRemovalTest() {
        $minifier = new Minifier;
        $this->assertEquals('.class{}', $minifier->minify('.class{} /*comment */'));
    }

    public function testSimpleCSSTabRemovalTest() {
        $minifier = new Minifier;
        $this->assertEquals('.class{}', $minifier->minify('.class{  }'));
    }

    public function testSimpleCSSWhitespaceAfterRemovalTest() {
        $minifier = new Minifier;
        $this->assertEquals('.class{}', $minifier->minify('.class{}                '));
    }

}
