<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\filters\generate_shape class
 */
class filterGenerateShapeTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\filters\generate_shape;
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
     * @testdox Testing filter can return output SVG Definitions with defs().
     *
     */
    public function test_defs()
    {
        $expected = null;

        $recieved = $this->class_instance->defs();

        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can run() without any supplied parameters.
     *
     */
    public function test_run_filter_without_any_parameters()
    {
        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('no supplied values'));
        $this->class_instance->set_image([
                DIR_DATA . '/test_image.jpg',
            ]);
        $this->class_instance->set_source_object('source_object');

        /**
         * Expected, Recieved, Asserted
         */
        $recieved = $this->class_instance->run();
        $this->assertIsObject($recieved);

        
        /**
         * Expected, Recieved, Asserted
         * 
         * check result parameter is set.
         */
        $expected = true;
        $recieved = isset($recieved->result);
        $this->assertEquals($expected, $recieved);


    }

    /**
     * @test
     *
     * @testdox Testing filter can run() with all parameters.
     *
     */
    public function test_run_filter_with_all_parameters()
    {
        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize("[
                'palette' => ['#123456', '#FAFAFA'],
                'additional_palette' => ['#000000','#242424','#FFFFFF'],
                'additional_colours' => 1,
                'opacity' => 0.8,
                'corners' => ['tl','tr','bl','br'],
                'corner_size' => 4,
                'shapes' => ['rect', 'cross', 'square_cross', 'square_plus', 'triangle', 'right_angled_triangle',
                            'leaf', 'dots', 'lines', 'wiggles', 'diamond', 'flower', 'stripes', 'bump'],
                'cell_size' => 40,
                'width' => 1280,
                'height' => 1280,
        ]"));

        $this->class_instance->set_image([
                DIR_DATA . '/test_image.jpg',
            ]);
        $this->class_instance->set_source_object('source_object');

        /**
         * Expected, Recieved, Asserted
         */
        $recieved = $this->class_instance->run();
        $this->assertIsObject($recieved);

        
        /**
         * Expected, Recieved, Asserted
         * 
         * check result parameter is set.
         */
        $expected = true;
        $recieved = isset($recieved->result);
        $this->assertEquals($expected, $recieved);

    }

    /**
     * @test
     *
     * @testdox Testing filter can run() without addition palette.
     *
     */
    public function test_run_filter_without_additional_palette()
    {
        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize("[
                'palette' => ['#123456', '#FAFAFA'],
                'additional_colours' => 1,
                'opacity' => 0.8,
                'corners' => ['tl','tr','bl','br'],
                'corner_size' => 4,
                'shapes' => ['rect', 'cross', 'square_cross', 'square_plus', 'triangle', 'right_angled_triangle',
                            'leaf', 'dots', 'lines', 'wiggles', 'diamond', 'flower', 'stripes', 'bump'],
                'cell_size' => 40,
                'width' => 1280,
                'height' => 1280,
        ]"));

        $this->class_instance->set_image([
                DIR_DATA . '/test_image.jpg',
            ]);
        $this->class_instance->set_source_object('source_object');

        /**
         * Expected, Recieved, Asserted
         */
        $recieved = $this->class_instance->run();
        $this->assertIsObject($recieved);

        
        /**
         * Expected, Recieved, Asserted
         * 
         * check result parameter is set.
         */
        $expected = true;
        $recieved = isset($recieved->result);
        $this->assertEquals($expected, $recieved);

    }


    /**
     * @test
     *
     * @testdox Testing filter can run() with all parameters and get the output back.
     *
     */
    public function test_run_filter_with_all_parameters_and_get_output()
    {
        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize("[
                'palette' => ['#123456', '#FAFAFA'],
                'additional_palette' => ['#000000','#242424','#FFFFFF'],
                'additional_colours' => 1,
                'opacity' => 0.8,
                'corners' => ['tl','tr','bl','br'],
                'corner_size' => 4,
                'shapes' => ['rect', 'cross', 'square_cross', 'square_plus', 'triangle', 'right_angled_triangle',
                            'leaf', 'dots', 'lines', 'wiggles', 'diamond', 'flower', 'stripes', 'bump'],
                'cell_size' => 40,
                'width' => 1280,
                'height' => 1280,
        ]"));

        $this->class_instance->set_image([
                DIR_DATA . '/test_image.jpg',
            ]);
        $this->class_instance->set_source_object('source_object');

        $this->class_instance->run();

        $result = $this->class_instance->output();

        /**
         * Expected, Recieved, Asserted
         * 
         * check result parameter is set.
         */
        $expected = 'string';
        $recieved = gettype($result);
        $this->assertEquals($expected, $recieved);

        /**
         * Expected, Recieved, Asserted
         * 
         * check result string starts with the word "<svg"
         */
        $expected = 0;
        $recieved = strpos($result, '<svg');
        $this->assertEquals($expected, $recieved);

    }
}
