<?php
    /**
        * Plugin Name: Another read activity plugin
        * Description: Add activity from anotherread.com to your wesbite using this plugin - includes gutenberg block to add activity content to any of your pages or posts.
        * Version: 1.0
        * Author: Line Industries
        * Author URI: https://line.industries/
    */

    defined('ABSPATH') or die('You can/t access this');
    include_once("admin-page.php");
    include_once("another-read-cpt.php");
    include_once("another-read-post-creation.php");
    include_once("another-read-block.php");


    class AnotherRead{

        function __construct()
        {
            //Initialises the custom post type
            add_action('init', array('AnotherReadCPT', 'activityCPT'));
            
            //Initialises the custom post type taxonomy
            add_action('init', array('AnotherReadCPT', 'activityTaxonomy'));
            
            //Adds the meta boxes to the custom post type
            add_action('add_meta_boxes', array('AnotherReadCPT', 'createMetaBoxes'));
            
            //Adds saving to meta boxes
            add_action('save_post', array('AnotherReadCPT','saveMetaBoxes'));
            
            //Adds the admin page
            add_action('admin_menu', array($this, 'adminMenu'));

            //Adds the gutenberg block
            add_action('init', array('AnotherReadGutenbergBlock', 'createActivityBlock'));

            add_action('getActivityPosts', array('AnotherReadPostCreator', 'create'));

            
            if(isset($_POST['update_settings'])){
                $this->insertData();
            }
            elseif(isset($_POST['update_posts'])){
                $this->createPosts();

                //print_r("things are working");
            }
            
        }

        // To run on plugin activation
        function activate(){
            AnotherReadCPT::activityCPT();
            AnotherReadCPT::activityTaxonomy();
            flush_rewrite_rules();
        }

        // To run on deactivation
        function deactivate(){

            flush_rewrite_rules();
        }

        //add menu page to wp-admin
        static function adminMenu(){

            add_menu_page('Another Read activity settings', 'Another Read', 'manage_options', 'AnotherReadAdminMenu', array('AnotherReadAdmin','adminPage'), '');

        }

        function createPosts(){
            
            add_action('init', array('AnotherReadPostCreator', 'create'));
        }


        function insertData(){

            if(isset($_POST['update_settings'])){

                $another_read_settings = array(
                    'keyword' => $_POST['keyword'],
                    'contributor' => $_POST['contributor'],
                    'publisher' => $_POST['publisher'],
                    'results' => $_POST['results'],
                    'accesskey' => $_POST['accesskey']
                );

                if(get_option('another_read_settings') !== false) {
                    update_option('another_read_settings', $another_read_settings);
                }
                else{
                    add_option('another_read_settings', $another_read_settings);
        
                    $this->insertData();
                }
            }
            else{
                echo "this didnt work";
            }

        }
    }

    if( class_exists('AnotherRead')){
        $AnotherRead = new AnotherRead();
    }
    if(! wp_next_scheduled('getActivityPosts')){
        wp_schedule_event(time(), 'daily', 'getActivityPosts');
    }


    //Activate hook
    register_activation_hook(__FILE__, array($AnotherRead, 'activate'));

    //Deactivate hook
    register_deactivation_hook(__FILE__, array($AnotherRead, 'deactivate'));



?>