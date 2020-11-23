<?php

/**
 * Class runasShortcodeTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\runas_shortcode class - main processor
 */
class runasShortcodeTest extends WP_UnitTestCase
{
    public function setUp()
    {
        // before
        parent::setUp();
        $this->class_instance = new \genimage\runas_shortcode;
    }

    /**
     * @test
     *
     * @testdox Testing class exists and returns an object.
     *
     */
    public function test_generator_class_exists()
    {
        $got = new \genimage\runas_shortcode;

        $this->assertIsObject($got);
    }
}
