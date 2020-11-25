<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\images class
 */
class imagesTest extends WP_UnitTestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\images;
    }


    public function tearDown() {
        $this->remove_added_uploads();
        parent::tearDown();
    }


    /**
     * @test
     *
     * @testdox Testing class exists and returns an object.
     *
     */
    public function test_generator_class_exists()
    {
        $got = new \genimage\images;

        $this->assertIsObject($got);
    }


    /**
     * @test
     *
     * @testdox Testing a WP_Query source that returns a single image result.
     *
     */
    public function test_a_wp_query_source_will_return_results()
    {
        
        /**
         * Create a post, attachment and thumbnail.
         */
        $post = $this->factory->post->create_and_get();
        $attachment_id = $this->factory->attachment->create_upload_object( DIR_DATA . '/test_image.jpg', $post->ID );
        $thumbnail = set_post_thumbnail( $post->ID, $attachment_id );
        $post_attachment = get_attached_media( 'image', $post->ID );

        /**
         * Commit to database.
         */
        $this->commit_transaction();

        /**
         * Create and add Dummy ACF Source data
         */
        $row = array(
            'genimage_source_slug' => 'phpunit_test_1',
            'genimage_source_type' => 'get_query',
            'genimage_post'        => null,
            'genimage_taxonomy'    => null,
            'genimage_query'       => "[
                'post_type' => 'post',
                'post_status' => 'publish',
                'numberposts' => 1,
            ]",
        );
        $row_count = add_row('genimage_source', $row, 'option');
        

        /**
         * Specify the dummy source as the one to run.
         */
        $config['instance_source'] = "phpunit_test_1";

        $this->class_instance->set_instance_source($config['instance_source']);

        $this->class_instance->run();

        $images = $this->class_instance->get_images();


        /**
         * Expected, Recieved, Result.
         * 
         * Expecting an array of 3 elements.
         * [
         *      "path/to/test_image-1.jpg"
         *      "1920"
         *      "1080"
         * ]
         */
        $expected = 3;
        $recieved = count($images[0]);
        $this->assertEquals($expected, $recieved);


        /**
         * Expected, Recieved, Result.
         * 
         * Testing filenames match.
         */
        $expected = basename(reset($post_attachment)->guid);
        $recieved = basename($images[0][0]);
        $this->assertEquals($expected, $recieved);

    }
}
