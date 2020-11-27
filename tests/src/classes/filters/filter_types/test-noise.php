<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\filters\noise class
 */
class filterNoiseTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\filters\noise;
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
        $this->class_instance->set_params(serialize('no real data'));
        $recieved = $this->class_instance->run();
        $this->assertIsObject($recieved);
    }
    
    /**
     * @test
     *
     * @testdox Testing filter can run() and catch incorrect params.
     *
     */
    public function test_run_with_incorrect_params()
    {
        $this->class_instance->set_params(serialize("'malformed data"));
        $expected = false;
        $recieved = $this->class_instance->run();
        $this->assertEquals($expected, $recieved);
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
         * SETUP
         */
        $this->class_instance->set_params(serialize('[ 0.3,  "' .DIR_DATA . '/test_image.jpg" ]'));
        $this->class_instance->run();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.3" fill="url(#pattern-noise)"></rect>';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }
    
    /**
     * @test
     *
     * @testdox Testing filter cannot output() with no params
     *
     */
    public function test_output_with_no_params()
    {

        /**
         * Expected, Recieved, Asserted
         */
        $expected = false;
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
        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('[ 0.3,  "' .DIR_DATA . '/test_image.jpg" ]'));
        $this->class_instance->run();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<image id="noise" xlink:href="../../../../wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" height="200px" width="200px"></image><pattern id="pattern-noise" width="200px" height="200px" x="-200" y="-200" patternUnits="userSpaceOnUse"><use xlink:href="#noise"></use></pattern>';
        $recieved = $this->class_instance->defs();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can return without parameters.
     *
     */
    public function test_defs_with_no_params()
    {
        /**
         * SETUP
         */
        $this->class_instance->run();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = false;
        $recieved = $this->class_instance->defs();
        $this->assertEquals($expected, $recieved);
    }

}
