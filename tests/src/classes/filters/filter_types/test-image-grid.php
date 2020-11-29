<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\filters\image_grid class
 */
class filterImageGridTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\filters\image_grid;
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
     * @testdox Testing filter can output() a grid of four <image>.
     *
     */
    public function test_output_creates_a_grid_of_four_images()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_all_images([
            [
                DIR_DATA . '/test_image.jpg', 640, 480
            ],
            [
                DIR_DATA . '/test_image.jpg', 640, 480
            ],
            [
                DIR_DATA . '/test_image.jpg', 640, 480
            ],
            [
                DIR_DATA . '/test_image.jpg', 640, 480
            ],
        ]);

        $this->class_instance->set_params(serialize("[
            'rows' => 2,
            'columns' => 2,
            'cell_width' => '50%',
            'cell_height' => '50%',
            'image_parameters' => 'width=\"100%\" height=\"100%\"',
        ]"));

        $this->class_instance->run();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<svg><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" preserveAspectRatio="xMidYMid slice"  viewBox="0 0 100 50"  width="50%"  height="50%"  x="0%"  y="0%"  ></image></svg><svg><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" preserveAspectRatio="xMidYMid slice"  viewBox="0 0 100 50"  width="50%"  height="50%"  x="50%"  y="0%"  ></image></svg><svg><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" preserveAspectRatio="xMidYMid slice"  viewBox="0 0 100 50"  width="50%"  height="50%"  x="0%"  y="50%"  ></image></svg><svg><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" preserveAspectRatio="xMidYMid slice"  viewBox="0 0 100 50"  width="50%"  height="50%"  x="50%"  y="50%"  ></image></svg>';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can gracefully return malformed parameters.
     *
     */
    public function test_output_gracefully_handles_malformed_params()
    {

        /**
         * SETUP
         * 
         * (removed single quotes)
         */
        $this->class_instance->set_params(serialize("[
            rows => 2,
            columns => 2,
            cell_width => 50%,
            cell_height => 50%,
            image_parameters => width=\"100%\" height=\"100%\",
        ]"));

        $this->class_instance->run();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = "syntax error, unexpected ','";
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can default columns / rows to 1x1 params.
     *
     */
    public function test_run_defaults_one_columns_and_one_row_param()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_all_images([
            [
                DIR_DATA . '/test_image.jpg', 640, 480
            ],
            [
                DIR_DATA . '/test_image.jpg', 640, 480
            ],
            [
                DIR_DATA . '/test_image.jpg', 640, 480
            ],
            [
                DIR_DATA . '/test_image.jpg', 640, 480
            ],
        ]);

        $this->class_instance->set_params(serialize("[
            'cell_width' => '50%',
            'cell_height' => '50%',
            'image_parameters' => 'width=\"100%\" height=\"100%\"',
        ]"));

        $this->class_instance->run();

        /**
         * Expected, Recieved, Asserted
         */
        $expected = '<svg><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" preserveAspectRatio="xMidYMid slice"  viewBox="0 0 100 50"  width="50%"  height="50%"  x="0%"  y="0%"  ></image></svg>';
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
