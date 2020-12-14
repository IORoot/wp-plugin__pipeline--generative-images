<?php

/**
 * Class generatorTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the genimage_get_instance wordpress filter
 */
class getGiInstanceTest extends WP_UnitTestCase
{

    public $input = [];


    public function setUp()
    {
        // before
        parent::setUp();
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
     * @testdox Testing the filter can be run()
     *
     */
    public function test_run()
    {

        /**
         * Setup - Insert post with image
         */
        $this->util_make_post_with_thumbnail(2);


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
                ],
                [
                    'filter_name' => "text",
                    'filter_parameters' => '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" style="font-size: 42px; fill:#fafafa;" >PHPUNIT</text>',
                ]
            ]
        ];
        add_row('genimage_filters', $filter_group, 'option');


        /**
         * apply filter
         */
        $filter_args = [
            'phpunit_test_filter',
            [
                $this->input[0]['post'],
                $this->input[1]['post'],
            ],
            [
                'svg' => false,
                'jpg' => false,
                'png' => true,
            ],
            [ 300,300 ]
        ];
        
        $result = apply_filters_ref_array('genimage_get_instance', $filter_args);
        /**
         * Expected, Recieved, Result.
         */
        $expected = 2;
        $recieved = count($result);
        $this->assertEquals($expected, $recieved);
    }

}
