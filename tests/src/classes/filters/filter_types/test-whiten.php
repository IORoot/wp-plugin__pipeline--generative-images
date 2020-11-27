<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\filters\whiten class
 */
class filterWhitenTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\filters\whiten;
    }


    public function tearDown()
    {
        $this->remove_added_uploads();
        parent::tearDown();
    }


    /**
     * @test
     *
     * @testdox Testing class exists and returns an object.
     *
     */
    public function test_class_exists()
    {
        $recieved = $this->class_instance;

        $this->assertIsObject($recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can set_params() with serialised data.
     *
     */
    public function test_set_params()
    {
        $params = serialize('filter_parameters');

        $expected = null;

        $recieved = $this->class_instance->set_params($params);

        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can set_image().
     *
     */
    public function test_set_image()
    {
        $image = 'image.jpg';

        $expected = null;

        $recieved = $this->class_instance->set_image($image);

        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can set_all_images().
     *
     */
    public function test_set_all_images()
    {
        $image = 'images.jpg';

        $expected = null;

        $recieved = $this->class_instance->set_all_images($image);

        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can set_source_object().
     *
     */
    public function test_set_source_object()
    {
        $source_object = 'source_object';

        $expected = null;

        $recieved = $this->class_instance->set_source_object($source_object);

        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can run().
     *
     */
    public function test_run_filter()
    {
        $recieved = $this->class_instance->run();

        $this->assertIsObject($recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can return a <rect> with no params.
     *
     */
    public function test_output_with_no_params()
    {
        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<rect height="100%" width="100%" x="0" y="0" fill-opacity="1" fill="#ffffff"></rect>';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can return a <rect> with a float params.
     *
     */
    public function test_output_with_a_float_param()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize(0.5));

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.5" fill="#ffffff"></rect>';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can return a <rect> with an int params.
     *
     */
    public function test_output_with_an_int_param()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize(1));

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<rect height="100%" width="100%" x="0" y="0" fill-opacity="1" fill="#ffffff"></rect>';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can return a <rect> with a wrong param.
     *
     */
    public function test_output_with_a_wrong_param()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize(['one', 2, 'three']));

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<rect height="100%" width="100%" x="0" y="0" fill-opacity="1" fill="#ffffff"></rect>';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }



    /**
     * @test
     *
     * @testdox Testing filter can return output SVG Definitions with defs().
     *
     */
    public function test_defs()
    {
        $expected = null;

        $recieved = $this->class_instance->defs();

        $this->assertEquals($expected, $recieved);
    }
}
