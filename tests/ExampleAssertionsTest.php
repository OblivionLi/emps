<?php

class ExampleAssertionsTest extends \PHPUnit\Framework\TestCase {

    public function testThatStringsMatch() {
        $string1 = 'testing';

        $string2 = 'testing';

        $this->assertSame($string1, $string2);
    }
}