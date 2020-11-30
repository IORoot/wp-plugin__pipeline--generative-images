<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\filters\image_free class
 */
class filterImageFreeTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\filters\image_free;
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
        $expected = false;
        $recieved = $this->class_instance->run();
        $this->assertEquals($expected, $recieved);
    }
    
    /**
     * @test
     *
     * @testdox Testing filter can output() an <image>.
     *
     */
    public function test_output_creates_an_free_image()
    {
        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('[
            \'' . DIR_DATA . '/test_image.jpg\',
            \'filter="url(#myFilter)"\'
        ]'));

        $this->class_instance->run();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" filter="url(#myFilter)"></image>';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }
    

    /**
     * @test
     *
     * @testdox Testing filter returns false with no params
     *
     */
    public function test_output_returns_gracefully_without_params()
    {

        $this->class_instance->run();

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
     * @testdox Testing filter returns false with one params
     *
     */
    public function test_output_returns_gracefully_with_one_params()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('[
            \'filter="url(#myFilter)"\'
        ]'));

        $this->class_instance->run();

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
     * @testdox Testing filter returns false with malformed params
     *
     */
    public function test_output_returns_gracefully_with_malformed_params()
    {

        /**
         * SETUP 
         * 
         * (removed single quotes around each parameter)
         */
        $this->class_instance->set_params(serialize('[
            ' . DIR_DATA . '/test_image.jpg,
            filter="url(#myFilter)"
        ]'));

        /**
         * Expected, Recieved, Asserted
         */
        $expected = false;
        $recieved = $this->class_instance->run();
        $this->assertEquals($expected, $recieved);

        /**
         * Expected, Recieved, Asserted
         */
        $expected = "syntax error, unexpected '/', expecting ']'";
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
