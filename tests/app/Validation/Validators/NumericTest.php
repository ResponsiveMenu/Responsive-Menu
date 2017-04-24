<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Validation\Validators;

class NumericTest extends TestCase {

    public function testNumberList() {
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
            ['0.2', true],
            ['1', true],
            ['1.2', true],
            ['-1', true],
            ['-3.4', true],
            ['44', true],
            [0, true],
            [0.3, true],
            [1, true],
            [1.6, true],
            [-1, true],
            [-1.2, true],
            [44, true],
            [44.8, true]

        ];
    }

    /**
     * @dataProvider testNumberList
     */
    public function testIntegerIsValidated($number, $expected) {
        $validator = new Validators\Numeric($number);
        $this->assertEquals($expected, $validator->validate());
    }

}
