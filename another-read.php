<?php
    /**
        * Plugin Name: Another Read activity plugin
        * Description: Add activity from anotherread.com to your wesbsite using this plugin - includes Gutenberg block to add activity content to any of your pages or posts.
        * Version: 1.3
        * Author: Line Industries
        * Author URI: https://line.industries/
    */

    defined('ABSPATH') or die('You can/t access this');
    include_once("admin-page.php");
    include_once("another-read-cpt.php");
    include_once("another-read-post-creation.php");
    include_once("another-read-block.php");


    class AnotherReadActivity{

        function __construct()
        {
            //Adds scripts and styles
            add_action('admin_enqueue_scripts', array($this, 'add_admin_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'add_scripts'));

            //Initialises the custom post type
            add_action('init', array('AnotherReadActivityCPT', 'activityCPT'));
            
            //Initialises the custom post type taxonomy
            add_action('init', array('AnotherReadActivityCPT', 'activityTaxonomy'));
            
            //Adds the meta boxes to the custom post type
            add_action('add_meta_boxes', array('AnotherReadActivityCPT', 'createMetaBoxes'));
            
            //Adds saving to meta boxes
            add_action('save_post', array('AnotherReadActivityCPT','saveMetaBoxes'));
            
            //Adds the admin page
            add_action('admin_menu', array($this, 'adminMenu'));

            //Adds the gutenberg block
            add_action('init', array('AnotherReadActivityGutenbergBlock', 'createActivityBlock'));

            add_action('getActivityPosts', array('AnotherReadPostCreator', 'create'));

            //Set template for CPT
            add_filter('single_template', array('AnotherReadActivityCPT', 'setTemplate'));
            
            if(isset($_POST['update_settings'])){
                $this->insertData();
            }
            elseif(isset($_POST['update_posts'])){
                $this->insertData();
                $this->createPosts();

                //print_r("things are working");
            }

            //Create RSS Feed for activity posts
            add_action('init', array($this, 'another_read_rss_feed'));
            
        }

        // To run on plugin activation
        function activate(){
            AnotherReadActivityCPT::activityCPT();
            AnotherReadActivityCPT::activityTaxonomy();
            flush_rewrite_rules();
        }

        // To run on deactivation
        function deactivate(){

            flush_rewrite_rules();
        }

        //Add menu page to wp-admin
        static function adminMenu(){

            add_menu_page('Another Read activity settings', 'Another Read', 'manage_options', 'AnotherReadAdminMenu', array('AnotherReadActivityAdmin','adminPage'), '');

        }

        //Add css to admin pages
        function add_admin_scripts(){
            wp_enqueue_style('another-read-admin', plugin_dir_url(__FILE__) . 'another-read-admin.css', array(), '1.0.0', 'all');
        }

        //Add css to regular pages
        function add_scripts(){
            wp_enqueue_style('another-read', plugin_dir_url(__FILE__) . 'another-read.css', array(), '1.0.0', 'all');
        }

        //Creats posts
        function createPosts(){
            add_action('init', array('AnotherReadActivityPostCreator', 'create'));
        }

        //Creates RSS feed
        function another_read_rss_feed(){
            //echo '<h2>Another Read activity feed</h2>';
            add_feed('another-read-feed', 'CusRssFeed');
            
            function CusRssFeed(){
                //echo '<h2>Another Read activity feed</h2>';
                load_template(plugin_dir_path(__FILE__) . 'rss-another-read-feed.php');
            }

        }


        function insertData(){

            $another_read_settings = array(
                'keyword' => $_POST['keyword'],
                'contributor' => $_POST['contributor'],
                'publisher' => $_POST['publisher'],
                'results' => $_POST['results'],
                'accesskey' => $_POST['accesskey'],
                'apiCallSuccessful' => null
            );

            if(get_option('another_read_settings') !== false) {
                update_option('another_read_settings', $another_read_settings);
            }
            else{
                add_option('another_read_settings', $another_read_settings);
    
                $this->insertData();
            }
        }
    }

    if( class_exists('AnotherReadActivity')){
        $AnotherReadActivity = new AnotherReadActivity();
    }
    if(! wp_next_scheduled('getActivityPosts')){
        wp_schedule_event(time(), 'daily', 'getActivityPosts');
    }


    //Activate hook
    register_activation_hook(__FILE__, array($AnotherReadActivity, 'activate'));

    //Deactivate hook
    register_deactivation_hook(__FILE__, array($AnotherReadActivity, 'deactivate'));



?>