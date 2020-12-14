<?php

/**
 * Class generatorTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\generator class
 */
class generatorTest extends WP_UnitTestCase
{

    public $input = [];


    public function setUp()
    {
        // before
        parent::setUp();
        $this->class_instance = new \genimage\generator;
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
        $got = new \genimage\generator;

        $this->assertIsObject($got);
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
     * @testdox Testing the generator can be run()
     *
     */
    public function test_run()
    {
        /**
         * Setup - Instances
         */
        $instance = [
            'genimage_instance_slug'     => 'phpunit_test_slug',
            'genimage_instance_source'   => 'phpunit_test_source',
            'genimage_instance_filter'   => 'phpunit_test_filter',
            'genimage_instance_enabled'  => true,
        ];
        add_row('genimage_instance', $instance, 'option');


        /**
         * Setup - Insert post with image
         */
        $this->util_make_post_with_thumbnail();


        /**
         *  Setup - Source 'phpunit_test_source'
         */
        $source = [
            'genimage_source_slug' => 'phpunit_test_source',
            'genimage_source_type' => 'get_query',
            'genimage_post'        => null,
            'genimage_taxonomy'    => null,
            'genimage_query'       => "[
                'post_type' => 'post',
                'post_status' => 'publish',
                'numberposts' => 1,
            ]",
        ];
        add_row('genimage_source', $source, 'option');



        /**
         * Setup - Filter group
         */
        $filter_group = [
            'genimage_filter_group' => [
                'genimage_filter_preview' => '',
                'genimage_filter_slug' => 'phpunit_test_filter',
            ],
            'genimage_filter_description' => 'testing end-to-end',
            'genimage_filter_resize_width' => null,
            'genimage_filter_resize_height' => null,
            'genimage_filter' => [
                [
                    'filter_name' => "image",
                    'filter_parameters' => 'filter="url(#phpunit)"',
                ]
            ]
        ];
        add_row('genimage_filters', $filter_group, 'option');


        /**
         * Setup - SaveAs
         */
        update_field('gi_save_svg', true,  'option');
        update_field('gi_save_jpg', false, 'option'); 
        update_field('gi_save_png', false, 'option'); 

        /**
         * Run
         */
        $this->class_instance->run();
        $result = $this->class_instance->result();
        /**
         * Expected, Recieved, Result.
         */
        $expected = 700;
        $recieved = strlen($result[0]);
        $this->assertGreaterThan($expected, $recieved);
    }

}
