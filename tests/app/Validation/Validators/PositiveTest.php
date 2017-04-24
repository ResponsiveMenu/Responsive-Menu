<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Validation\Validators;

class PositiveTest extends TestCase {

    public function testPositiveList() {
        return [

            // These should fail
            ['-3.5as', false],
            ['-2a', false],
            ['-1', false],
            ['-4', false],
            [-3, false],

            // These should pass
            ['', true],
            ['0', true],
            ['0.2', true],
            ['1', true],
            ['1.2', true],
            ['44', true],
            [0, true],
            [0.3, true],
            [1, true],
            [1.6, true],

        ];
    }

    /**
     * @dataProvider testPositiveList
     */
    public function testNumberIsValidated($number, $expected) {
        $validator = new Validators\Positive($number);
        $this->assertEquals($expected, $validator->validate());
    }

}
