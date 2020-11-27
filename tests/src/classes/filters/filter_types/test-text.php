<?php

/**
 * Class imagesTest
 *
 * @package Andyp_pipeline_generative_images
 */

/**
 * @testdox Testing the \genimage\filters\text class
 */
class filterTextTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->class_instance = new \genimage\filters\text;
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

        $this->class_instance->set_image($image);

        $expected = 'image.jpg';

        $recieved = $this->class_instance->image;

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

        $this->class_instance->set_source_object($source_object);

        $expected = 'source_object';

        $recieved = $this->class_instance->source_object;

        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can run().
     *
     */
    public function test_run_filter()
    {
        $recieved = $this->class_instance->run();

        $this->assertIsObject($recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can return plain text with output().
     *
     */
    public function test_output_plain_text()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('text'));
        $this->class_instance->set_image('image.jpg');
        $this->class_instance->set_source_object('source_object');

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 'text';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can return check for missing params.
     *
     */
    public function test_check_for_missing_params()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_image('image.jpg');
        $this->class_instance->set_source_object('source_object');

        /**
         * Expected, Recieved, Asserted
         */
        $expected = null;
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can return check for missing image.
     *
     */
    public function test_check_for_missing_image()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('{{post_title}}'));
        $this->class_instance->set_source_object('source_object');

        /**
         * Expected, Recieved, Asserted
         */
        $expected = null;
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can return check for missing source object.
     *
     */
    public function test_check_for_missing_source_object()
    {

        /**
         * SETUP
         */
        $this->class_instance->set_params(serialize('{{post_title}}'));
        $this->class_instance->set_image('image.jpg');

        /**
         * Expected, Recieved, Asserted
         */
        $expected = null;
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can process {{post_title}} to field value.
     *
     */
    public function test_output_moustache_replacement_text()
    {

        /**
         * SETUP
         */
        
        $this->class_instance->set_params(serialize('{{post_title}}'));
        $this->class_instance->set_image('image.jpg');
        $source_object = $this->factory->post->create_and_get(['post_title' => 'This is a test title']);
        $this->class_instance->set_source_object($source_object);

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 'This is a test title';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can process {{uc:field}} to uppercase.
     *
     */
    public function test_output_uppercase_text()
    {

        /**
         * SETUP
         */
        
        $this->class_instance->set_params(serialize('{{uc:post_title}}'));
        $this->class_instance->set_image('image.jpg');
        $source_object = $this->factory->post->create_and_get();
        $this->class_instance->set_source_object($source_object);

        /**
         * Expected, Recieved, Asserted
         */
        $expected = strtoupper($source_object->post_title);
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can process {{hy:field}} to remove everything before a hypen.
     *
     */
    public function test_output_can_remove_everything_before_a_hypen_in_a_text_field()
    {

        /**
         * SETUP
         */
        
        $this->class_instance->set_params(serialize('{{hy:post_title}}'));
        $this->class_instance->set_image('image.jpg');
        $source_object = $this->factory->post->create_and_get(['post_title' => 'Before hypen - After hypen']);
        $this->class_instance->set_source_object($source_object);

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 'After hypen';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can process {{w1:field}} to remove everything but the first word.
     *
     */
    public function test_output_can_match_the_first_word_only_in_a_text_field()
    {

        /**
         * SETUP
         */
        
        $this->class_instance->set_params(serialize('{{w1:post_title}}'));
        $this->class_instance->set_image('image.jpg');
        $source_object = $this->factory->post->create_and_get(['post_title' => 'first second third fourth']);
        $this->class_instance->set_source_object($source_object);

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 'first';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }


    /**
     * @test
     *
     * @testdox Testing filter can process {{w2:field}} to remove everything but the second word.
     *
     */
    public function test_output_can_match_the_second_word_only_in_a_text_field()
    {

        /**
         * SETUP
         */
        
        $this->class_instance->set_params(serialize('{{w2:post_title}}'));
        $this->class_instance->set_image('image.jpg');
        $source_object = $this->factory->post->create_and_get(['post_title' => 'first second third fourth']);
        $this->class_instance->set_source_object($source_object);

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 'second';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can process {{w3:field}} to remove everything but the third word.
     *
     */
    public function test_output_can_match_the_third_word_only_in_a_text_field()
    {

        /**
         * SETUP
         */
        
        $this->class_instance->set_params(serialize('{{w3:post_title}}'));
        $this->class_instance->set_image('image.jpg');
        $source_object = $this->factory->post->create_and_get(['post_title' => 'first second third fourth']);
        $this->class_instance->set_source_object($source_object);

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 'third';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can process {{w4:field}} to remove everything but the fourth word.
     *
     */
    public function test_output_can_match_the_fourth_word_only_in_a_text_field()
    {

        /**
         * SETUP
         */
        
        $this->class_instance->set_params(serialize('{{w4:post_title}}'));
        $this->class_instance->set_image('image.jpg');
        $source_object = $this->factory->post->create_and_get(['post_title' => 'first second third fourth']);
        $this->class_instance->set_source_object($source_object);

        /**
         * Expected, Recieved, Asserted
         */
        $expected = 'fourth';
        $recieved = $this->class_instance->output();
        $this->assertEquals($expected, $recieved);
    }

    /**
     * @test
     *
     * @testdox Testing filter can process {{date:Y-m-d}} to substitute the current date now in the Y-m-d format.
     *
     */
    public function test_output_can_match_and_substitute_a_PHP_date_in_a_text_field()
    {

        /**
         * SETUP
         */
        
        $this->class_instance->set_params(serialize('{{date:Y-m-d}} is the current date.'));
        $this->class_instance->set_image('image.jpg');
        $source_object = $this->factory->post->create_and_get(['post_title' => 'title']);
        $this->class_instance->set_source_object($source_object);

        /**
         * Expected, Recieved, Asserted
         */
        
        $expected = date('Y-m-d') .' is the current date.';
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
