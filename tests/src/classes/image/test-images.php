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

    /**
     * Contains generated test posts
     */
    public $input = [];
    


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
        $got = new \genimage\images;

        $this->assertIsObject($got);
    }



    /**
     * @test
     *
     * @testdox Testing image source - WP_Query - single image.
     *
     */
    public function test_a_wp_query_source_will_return_results()
    {

        $this->util_make_post_with_thumbnail();

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
        add_row('genimage_source', $row, 'option');
        

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
        $expected = basename(reset($this->input[0]['post_attachment'])->guid);
        $recieved = basename($images[0][0]);
        $this->assertEquals($expected, $recieved);

    }



    /**
     * @test
     *
     * @testdox Testing image source - WP_Query - multiple images.
     *
     */
    public function test_a_wp_query_source_will_return_results_with_multiple_images()
    {

        $this->util_make_post_with_thumbnail(2);

        /**
         * Create and add Dummy ACF Source data
         */
        $row = array(
            'genimage_source_slug' => 'phpunit_test_2',
            'genimage_source_type' => 'get_query',
            'genimage_post'        => null,
            'genimage_taxonomy'    => null,
            'genimage_query'       => "[
                'post_type' => 'post',
                'post_status' => 'publish',
                'numberposts' => 2,
            ]",
        );
        add_row('genimage_source', $row, 'option');
        

        /**
         * Specify the dummy source as the one to run.
         */
        $config['instance_source'] = "phpunit_test_2";
        $this->class_instance->set_instance_source($config['instance_source']);
        $this->class_instance->run();
        $images = $this->class_instance->get_images();


        /**
         * Expected, Recieved, Result.
         * 
         * Expecting an array of 2 images.
         * [
         *      [
         *          "path/to/test_image-1.jpg"
         *          "1920"
         *          "1080"
         *      ]
         *      [
         *          "path/to/test_image-2.jpg"
         *          "1920"
         *          "1080"
         *      ]
         * ]
         */
        $expected = 2;
        $recieved = count($images);
        $this->assertEquals($expected, $recieved);

    }

    /**
     * @test
     *
     * @testdox Testing image source - POST - single image.
     *
     */
    public function test_a_post_source_will_return_a_post()
    {

        $this->util_make_post_with_thumbnail();

        /**
         * Create and add Dummy ACF Source data
         */
        $row = array(
            'genimage_source_slug' => 'phpunit_test_3',
            'genimage_source_type' => 'get_post',
            'genimage_post'        => [ $this->input[0]['post']->ID ],
            'genimage_taxonomy'    => null,
            'genimage_query'       => null,
        );
        add_row('genimage_source', $row, 'option');
        

        /**
         * Specify the dummy source as the one to run.
         */
        $config['instance_source'] = "phpunit_test_3";
        $this->class_instance->set_instance_source($config['instance_source']);
        $this->class_instance->run();
        $images = $this->class_instance->get_images();


        /**
         * Expected, Recieved, Result.
         * 
         * Expecting an array of 1 images.
         * [
         *      [
         *          "path/to/test_image-1.jpg"
         *          "1920"
         *          "1080"
         *      ]
         * ]
         */
        $expected = 1;
        $recieved = count($images);
        $this->assertEquals($expected, $recieved);

    }

    /**
     * @test
     *
     * @testdox Testing image source - POST - multiple images.
     *
     */
    public function test_a_post_source_will_return_multiple_posts()
    {

        $this->util_make_post_with_thumbnail(2);

        /**
         * Create and add Dummy ACF Source data
         */
        $row = array(
            'genimage_source_slug' => 'phpunit_test_4',
            'genimage_source_type' => 'get_post',
            'genimage_post'        => [ $this->input[0]['post']->ID, $this->input[1]['post']->ID ],
            'genimage_taxonomy'    => null,
            'genimage_query'       => null,
        );
        add_row('genimage_source', $row, 'option');
        

        /**
         * Specify the dummy source as the one to run.
         */
        $config['instance_source'] = "phpunit_test_4";
        $this->class_instance->set_instance_source($config['instance_source']);
        $this->class_instance->run();
        $images = $this->class_instance->get_images();


        /**
         * Expected, Recieved, Result.
         * 
         * Expecting an array of 2 images.
         * [
         *      [
         *          "path/to/test_image-1.jpg"
         *          "1920"
         *          "1080"
         *      ]
         *      [
         *          "path/to/test_image-2.jpg"
         *          "1920"
         *          "1080"
         *      ]
         * ]
         */
        $expected = 2;
        $recieved = count($images);
        $this->assertEquals($expected, $recieved);

    }

    /**
     * @test
     *
     * @testdox Testing image source - override source with set_source().
     * 
     * This is used with thee runas_filter class to manually set the required
     * source posts.
     *
     */
    public function test_a_post_source_manaul_set()
    {

        $this->util_make_post_with_thumbnail(2);

        $posts = [
            $this->input[0]['post'],
            $this->input[1]['post']
        ];

        /**
         * Specify the dummy source as the one to run.
         */
        $this->class_instance->set_source_objects($posts);
        $this->class_instance->run();
        $images = $this->class_instance->get_images();


        /**
         * Expected, Recieved, Result.
         * 
         * Expecting an array of 2 images.
         * [
         *      [
         *          "path/to/test_image-1.jpg"
         *          "1920"
         *          "1080"
         *      ]
         *      [
         *          "path/to/test_image-2.jpg"
         *          "1920"
         *          "1080"
         *      ]
         * ]
         */
        $expected = 2;
        $recieved = count($images);
        $this->assertEquals($expected, $recieved);

    }


    /**
     * @test
     *
     * @testdox Testing image source - use get_source() to get source image.
     *
     */
    public function test_a_post_source_manaul_set_can_be_retrieved()
    {

        $this->util_make_post_with_thumbnail(2);

        $posts = [
            $this->input[0]['post'],
            $this->input[1]['post']
        ];

        /**
         * Specify the dummy source as the one to run.
         */
        $this->class_instance->set_source_objects($posts);
        $this->class_instance->run();
        $images = $this->class_instance->get_images();


        $expected = $posts;
        $recieved = $this->class_instance->get_source_objects();
        $this->assertEquals($expected, $recieved);

    }

}
