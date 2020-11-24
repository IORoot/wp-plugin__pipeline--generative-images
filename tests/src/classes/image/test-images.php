<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\images class
 */
class imagesTest extends WP_UnitTestCase
{
    public function setUp()
    {
        // before
        parent::setUp();

        $this->class_instance = new \genimage\images;
    }

    /**
     * @test
     *
     * @testdox Testing class exists and returns an object.
     *
     */
    public function test_generator_class_exists()
    {
        $got = new \genimage\images;

        $this->assertIsObject($got);
    }


    /**
     * @test
     *
     * @testdox Test a WP_Query will return results
     *
     */
    public function test_a_wp_query_will_return_results()
    {
        $config['instance_source'] = "get_query";

        $this->class_instance->set_instance_source($config['instance_source']);

        $this->class_instance->run();

        $got = new \genimage\images;

        $this->assertIsObject($got);
    }
}
