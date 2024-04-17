<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Master layout
    |--------------------------------------------------------------------------
    | Here you set the master layout file for your project. This should have
    | a `raindrops` section where all the CRUD related stuffs will be attached to
    */
    'layout' => 'layout',

    /*
    |--------------------------------------------------------------------------
    | Show title
    |--------------------------------------------------------------------------
    | Should the title text be displayed on the top of the table and form
    | set it false if you need to display the title in some other places
    | other than the default place
    */
    'show_title' => true,

    /*
    |--------------------------------------------------------------------------
    | Generator related configs
    |--------------------------------------------------------------------------
    |
    */
    'generator' => [

        /*
        |--------------------------------------------------------------------------
        | Path for the stub files
        |--------------------------------------------------------------------------
        |
        */
        'stubs' => base_path('resources/crud-generator/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Labels
    |--------------------------------------------------------------------------
    |
    */
    'labels' => [

        'CONNECTED' => 'span.label label-primary',
        'REQUESTED' => 'span.label label-success',
        'DECLINED' => 'span.label label-info',
        'CANCELLED' => 'span.label label-warning',
        'DONE' => 'span.label label-danger',

        'icu' => 'span.label label-icu',
        'ccu' => 'span.label label-ccu',
        'nicu' => 'span.label label-nicu',
        'urine' => 'span.label label-icu',
    ],

    /*
    |--------------------------------------------------------------------------
    | File System disk to be used for uploading files
    |--------------------------------------------------------------------------
    |
    */
    'disk' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Root path to be used for showing files in table
    |--------------------------------------------------------------------------
    |
    */
    'filesystem_root' => 'storage',

    /*
    |--------------------------------------------------------------------------
    | Currency formats
    |--------------------------------------------------------------------------
    |
    */
    'currency_formats' => [

        // BDT
        'bdt' => [
            'symbol' => 'BDT',
            'place' => 'left'
        ]

    ],

    /*
    |-------------------------------------------------------------------------
    | Default formats for date, datetime & date types.
    | ref: http://php.net/manual/en/function.date.php
    |-------------------------------------------------------------------------
    |
    */
    'datetime_formats' => [

        'time' => 'g:i A',

        'date' => 'F j, Y',

        'datetime' => 'F j, Y, g:i A',

        'timestamp' => 'F j, Y, g:i A',

    ],

    /*
    |--------------------------------------------------------------------------
    | Classes that will be added to various fields on forms & tables
    |--------------------------------------------------------------------------
    |
    */
    'classes' => [

        /*
        |--------------------------------------------------------------------------
        | Image on details table
        |--------------------------------------------------------------------------
        |
        */
        'image_details' => 'image-details',

        /*
        |--------------------------------------------------------------------------
        | Image on index/list table
        |--------------------------------------------------------------------------
        |
        */
        'image_index' => 'image-list',
    ],



];
