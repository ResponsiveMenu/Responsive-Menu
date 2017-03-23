<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenuTest\Validation\Validators;

class ColourTest extends TestCase {

    public function testColourList() {
        return [
            // These should fail
            ['foo', false],
            ['bar', false],
            ['red', false],
            ['#f', false],
            ['#f', false],
            ['#ff', false],

            // These should pass
            ['#fff', true],
            ['#ffffff', true],
            ['rgba(54,54,54,0.5)', true],
            ['rgba(54,54,54)', true],
        ];
    }

    /**
     * @dataProvider testColourList
     */
    public function testHexIsValidated($colour, $expected) {
        $validator = new Validators\Colour($colour);
        $this->assertEquals($expected, $validator->validate());
    }

}
