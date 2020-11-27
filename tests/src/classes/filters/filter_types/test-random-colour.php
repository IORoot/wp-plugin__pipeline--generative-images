<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\filters\random_colour class
 */
class filterRandomColourTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\filters\random_colour;
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
    public function test_run()
    {
        $recieved = $this->class_instance->run();
        $this->assertIsObject($recieved);
    }

    
    /**
     * @test
     *
     * @testdox Testing filter can output() an <rect> with noise image in it.
     *
     */
    public function test_output()
    {
        /**
         * Expected, Recieved, Asserted
         */
        $expected = null;
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }



    /**
     * @test
     *
     * @testdox Testing filter can return output <linearGradient with defs().
     *
     */
    public function test_defs()
    {
        /**
         * SETUP
         */
        $defs = $this->class_instance->defs();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<linearGradient';
        $recieved = substr($defs, 0, 15);
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can insert an ID with params().
     *
     */
    public function test_defs_with_an_id()
    {
        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('phpunitID'));
        $defs = $this->class_instance->defs();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 20;
        $recieved = strpos($defs, 'phpunitID');
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter returns with a hex code colour.
     *
     */
    public function test_defs_contain_hex_colour()
    {
        /**
         * SETUP
         */
        $defs = $this->class_instance->defs();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 1;
        $recieved = preg_match('/#[0-9a-f]{6}/i', $defs);
        $this->assertEquals($expected, $recieved);
    }


}
