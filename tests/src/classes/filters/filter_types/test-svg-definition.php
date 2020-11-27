<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\filters\svg_definition class
 */
class filterSvgDefinitionTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\filters\svg_definition;
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
     * @testdox Testing filter defs().
     *
     */
    public function test_defs()
    {

        /**
         * Expected, Recieved, Asserted
         */
        $expected = null;
        $recieved = $this->class_instance->defs();
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing defs() with params().
     *
     */
    public function test_defs_with_a_params()
    {
        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('<image></image>'));

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<image></image>';
        $recieved = $this->class_instance->defs();
        $this->assertEquals($expected, $recieved);
    }


}
