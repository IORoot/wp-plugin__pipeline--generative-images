<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\svg_group class
 */
class svgGroupTest extends WP_UnitTestCase
{

    public $input;

    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\svg_group;
    }


    public function tearDown()
    {
        $this->remove_added_uploads();
        parent::tearDown();
    }



    /**
     * Create a post, attachment and thumbnail.
     * 
     * int $number is the number of posts you want.
     */
    public function util_make_post_with_thumbnail(int $number = 1)
    {

        for ($i = 0; $i < $number; $i++) {

            $in['post']            = $this->factory->post->create_and_get();
            $in['attachment_id']   = $this->factory->attachment->create_upload_object( DIR_DATA . '/test_image.jpg', $in['post']->ID );
            $in['thumbnail']       = set_post_thumbnail( $in['post']->ID, $in['attachment_id'] );
            $in['post_attachment'] = get_attached_media( 'image', $in['post']->ID );

            $this->input[$i] = $in;
        }
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
         * Setup - filters
         */
        $filters = [
            [ 
                'filter_name' => 'image',
                'filter_parameters' => 's:19:"filter="url(#aden)"";'
            ]
        ];
        $this->class_instance->set_filters($filters);


        /**
         * Setup - images
         */
        $images = [
            [ DIR_DATA . '/test_image.jpg', 1920, 1080 ]
        ];
        $this->class_instance->set_images($images);


        /**
         * Setup - dimensions
         */
        $dimensions = [
            'width' => '640', 
            'height' => '480'
        ];
        $this->class_instance->set_dimensions($dimensions);
        

        /**
         * Setup - Source Object
         */
        $this->util_make_post_with_thumbnail();
        $this->class_instance->set_source_objects([$this->input[0]['post']]);


        /**
         * Setup - run
         */
        $this->class_instance->run();


        /**
         * Expected, Recieved, Result.
         */
        $expected = [ '<svg viewBox="0 0 640 480" class="svgwrapper" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs></defs><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" width="1920" height="1080" filter="url(#aden)"></image></svg>'];
        $recieved = $this->class_instance->get_svg_group();
        $this->assertEquals($expected, $recieved);
    }



    /**
     * @test
     *
     * @testdox Testing run() fails with no image.
     *
     */
    public function test_run_without_image()
    {
        
        /**
         * Setup - images
         */
        $images = [ '' ];
        $this->class_instance->set_images($images);

        /**
         * Setup - run
         */
        $this->class_instance->run();

        /**
         * Expected, Recieved, Result.
         */
        $expected = null;
        $recieved = $this->class_instance->get_svg_group();
        $this->assertEquals($expected, $recieved);
    }




    /**
     * @test
     *
     * @testdox Testing run() without optional width and heights.
     *
     */
    public function test_run_without_optional_dimensions()
    {
        
        /**
         * Setup - filters
         */
        $filters = [
            [ 
                'filter_name' => 'image',
                'filter_parameters' => 's:19:"filter="url(#aden)"";'
            ]
        ];
        $this->class_instance->set_filters($filters);


        /**
         * Setup - images
         */
        $images = [
            [ DIR_DATA . '/test_image.jpg', 1920, 1080 ]
        ];
        $this->class_instance->set_images($images);        

        /**
         * Setup - Source Object
         */
        $this->util_make_post_with_thumbnail();
        $this->class_instance->set_source_objects([$this->input[0]['post']]);


        /**
         * Setup - run
         */
        $this->class_instance->run();


        /**
         * Expected, Recieved, Result.
         */
        $expected = [ '<svg viewBox="0 0 1920 1080" class="svgwrapper" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs></defs><image  xlink:href="/var/www/vhosts/dev.londonparkour.com/wp-content/plugins/andyp_pipeline_generative_images/tests/data/test_image.jpg" width="1920" height="1080" filter="url(#aden)"></image></svg>'];
        $recieved = $this->class_instance->get_svg_group();
        $this->assertEquals($expected, $recieved);
    }



}