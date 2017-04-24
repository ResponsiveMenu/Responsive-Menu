<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Validation\Validators;

class IntegerTest extends TestCase {

    public function testColourList() {
        return [

            // These should fail
            ['a', false],
            ['cd', false],
            ['0cd', false],
            ['cd0', false],
            ['cd0dc', false],
            ['', false],

            // These should pass
            ['0', true],
            ['1', true],
            ['-1', true],
            ['44', true],
            [0, true],
            [1, true],
            [-1, true],
            [44, true]

        ];
    }

    /**
     * @dataProvider testColourList
     */
    public function testIntegerIsValidated($number, $expected) {
        $validator = new Validators\Integer($number);
        $this->assertEquals($expected, $validator->validate());
    }

}
