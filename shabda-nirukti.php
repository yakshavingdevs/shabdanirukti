<?php
/**
* Plugin Name: shabda-nirukti
* Plugin URI: https://shabdanirukti.in
* Description: Shabda Nirukti
* Version: 0.1
* Author: Chiranjeevi Karthik Kuruganti, Sri Pravan Paturi
* Author URI: https://github.com/srimdev
**/



function sn_init() {
    global $wpdb;
    $db_table_prefix = $wpdb->prefix;
    //create persons table if not exists
    create_database_table("sn_persons","(
            person_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            person_email VARCHAR(200) NOT NULL,
            person_name VARCHAR(200)
     )");
     
    //create words table if not exists
    create_database_table("sn_words","(
            word_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            word VARCHAR(500) NOT NULL,
            language VARCHAR(5) NOT NULL
     )");
    //create definitions table if not exists
    create_database_table("sn_definitions","(
            definition_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            word_id INT NOT NULL,
            FOREIGN KEY (word_id) REFERENCES {$db_table_prefix}sn_words(word_id),
            definition MEDIUMTEXT NOT NULL,
            submitter_id INT NOT NULL,
            FOREIGN KEY (submitter_id) REFERENCES {$db_table_prefix}sn_persons(person_id),
            source TEXT,
            language VARCHAR(5),
            approval_status VARCHAR(12)
     )");
    //create tags table if not exists
    create_database_table("sn_tags","(
            tag_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            tag_name VARCHAR(200) NOT NULL
     )");
    //create definition_tags table if not exists
    create_database_table("sn_definition_tags","(
    	    def_tag_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    	    tag_id INT NOT NULL,
    	    FOREIGN KEY (tag_Id) REFERENCES {$db_table_prefix}sn_tags(tag_id),
    	    definition_id INT NOT NULL,
    	    FOREIGN KEY (definition_id) REFERENCES {$db_table_prefix}sn_definitions(definition_id)
     )");
    //create votes table if not exists
    create_database_table("sn_votes","(
    	    vote_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    	    voter_id INT NOT NULL,
    	    FOREIGN KEY (voter_id) REFERENCES {$db_table_prefix}sn_persons(person_id),
    	    definition_id INT NOT NULL,
    	    FOREIGN KEY (definition_id) REFERENCES {$db_table_prefix}sn_definitions(definition_id),
    	    vote VARCHAR(10) NOT NULL
     )");
}

function create_database_table($table_name,$sql)
{
    global $wpdb;
    $full_table_name = $wpdb->prefix . $table_name;
    // Get the encoding of the DB
    $charset_collate = $wpdb->get_charset_collate();

    if ($wpdb->get_var("SHOW TABLES LIKE '$full_table_name'") != $full_table_name) {
        // If table does not exists
        $sql_query = "CREATE TABLE $full_table_name $sql $charset_collate";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_query);
    }
}

// When plugin is activated
register_activation_hook(__FILE__, 'sn_init');
