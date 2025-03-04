<?php

namespace WeDevs\WeDocs;

/**
 * Frontend Handler Class
 */
class Admin {

    /**
     * Initialize the class
     */
    public function __construct() {
        new Admin\Admin();
        new Admin\Settings();
        new Admin\Docs_List_Table();
    }
}
