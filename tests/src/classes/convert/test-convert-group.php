<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\convert_group class
 */
class convertGroupTest extends WP_UnitTestCase
{

    public $input;

    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\convert_group;
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
     * @testdox Testing standard run() with correct params.
     *
     */
    public function test_run()
    {
        
        /**
         * Setup - SVG_group
         */
        $svg_group = [ '<svg viewBox="0 0 640 480" class="svgwrapper" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs></defs><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" width="1920" height="1080" filter="url(#aden)"></image></svg>'];
        $this->class_instance->set_svg_group($svg_group);

        /**
         * Setup - images
         */
        $images = [
            [ DIR_DATA . '/test_image.jpg', 1920, 1080 ]
        ];
        $this->class_instance->set_image_group($images);

        /**
         * Setup - SaveAs
         */
        $saves = [ 'svg' => true, 'png' => true, 'jpg' => true ];
        $this->class_instance->set_save_types($saves);

        /**
         * Setup - run
         */
        $this->class_instance->run();


        /**
         * Expected, Recieved, Result.
         */
        $expected = [ 
            [ 
                UPLOAD_DIR . "/test_image_gi.svg", 
                UPLOAD_DIR . "/test_image_gi.png", 
                UPLOAD_DIR . "/test_image_gi.jpg" 
            ]
        ];
        $recieved = $this->class_instance->get_converted();
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing standard run() with bad params.
     *
     */
    public function test_run_with_bad_images()
    {
        
        /**
         * Setup - SVG_group
         */
        $svg_group = [ '<svg viewBox="0 0 640 480" class="svgwrapper" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs></defs><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" width="1920" height="1080" filter="url(#aden)"></image></svg>'];
        $this->class_instance->set_svg_group($svg_group);

        /**
         * Setup - images
         */
        $images = [
            [ '/test_image`', 1920, 1080 ]
        ];
        $this->class_instance->set_image_group($images);

        /**
         * Setup - SaveAs
         */
        $saves = [ 'svg' => true, 'png' => true, 'jpg' => true ];
        $this->class_instance->set_save_types($saves);

        /**
         * Setup - run
         */
        $this->class_instance->run();


        /**
         * Expected, Recieved, Result.
         */
        $expected = [ 
            [ 
                UPLOAD_DIR . "/test_image`_gi.svg", 
                false, 
                false 
            ]
        ];
        $recieved = $this->class_instance->get_converted();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing standard run() with no save types.
     *
     */
    public function test_run_with_no_save_types()
    {
        
        /**
         * Setup - SVG_group
         */
        $svg_group = [ '<svg viewBox="0 0 640 480" class="svgwrapper" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs></defs><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" width="1920" height="1080" filter="url(#aden)"></image></svg>'];
        $this->class_instance->set_svg_group($svg_group);

        /**
         * Setup - images
         */
        $images = [
            [ '/test_image`', 1920, 1080 ]
        ];
        $this->class_instance->set_image_group($images);

        /**
         * Setup - insert ACF options for save types.
         */
        update_field('gi_save_svg', false, 'options');
        update_field('gi_save_jpg', false, 'options');
        update_field('gi_save_png', false, 'options');


        /**
         * Setup - run
         */
        $this->class_instance->run();


        /**
         * Expected, Recieved, Result.
         */
        $expected = null;
        $recieved = $this->class_instance->get_converted();
        $this->assertEquals($expected, $recieved);
    }




}