<?php

use PHPUnit\Framework\TestCase;
use ResponsiveMenu\Validation\Validator;

class ValidatorTest extends TestCase {

    public function testCorrectErrorsReturned() {
        $options = [
            'button_background_colour' => 'will fail',
            'menu_link_colour' => '#ffffff'
        ];
        $validator = new Validator();
        $this->assertFalse($validator->validate($options));
        $errors = $validator->getErrors();

        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('button_background_colour', $errors);

        // Tests the creation of URL inside error message
        $this->assertContains('#responsive-menu-button-background-colour', $errors['button_background_colour'][0]);

        // Test the conversion of ID to name inside error message
        $this->assertContains('Button background colour', $errors['button_background_colour'][0]);
    }

    public function testNoErrorsThrown() {
        $options = [
            'button_background_colour' => '#DADADA',
            'menu_link_colour' => '#ffffff'
        ];
        $validator = new Validator();
        $this->assertTrue($validator->validate($options));
        $this->assertEmpty($validator->getErrors());
    }

    public function testCombinedValidators() {
        $options = [
            'breakpoint' => '-3.5abc',
            'menu_link_colour' => '#ffffff'
        ];
        $validator = new Validator();
        $this->assertFalse($validator->validate($options));
        $this->assertEquals(count($validator->getErrors()['breakpoint']), 2);
    }

}
