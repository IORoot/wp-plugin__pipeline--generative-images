<?php

/**
 * Class generatorTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\generator class
 */
class generatorTest extends WP_UnitTestCase
{
    public function setUp()
    {
        // before
        parent::setUp();
        $this->class_instance = new \genimage\generator;
    }

    /**
     * @test
     *
     * @testdox Testing class exists and returns an object.
     *
     */
    public function test_class_exists()
    {
        $got = new \genimage\generator;

        $this->assertIsObject($got);
    }
}
