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
    public function test_class_exists()
    {
        $got = new \genimage\runas_shortcode;

        $this->assertIsObject($got);
    }


    /**
     * @test
     *
     * @testdox Testing the main generator class
     *
     */
    // public function test_generator_class_runs_with_minimal_config()
    // {
    //     $config['instance_slug'] = 'phpunit_test';
    //     $config['instance_source'] = 'get_query';
    //     $config['instance_filter'] = 'corner_dots';
    //     $config['instance_enabled'] = true;
        
    //     $this->class_instance->set_config($config);

    //     $this->class_instance->run();

    //     $expected = true;

    //     $got = $this->class_instance->result();

    //     $this->assertEquals($expected, $got);
    // }


}
