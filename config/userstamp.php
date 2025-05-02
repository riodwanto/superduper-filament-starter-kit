<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Stamp Columns
    |--------------------------------------------------------------------------
    |
    | These are the default column names used for storing user IDs
    | when stamping database records. You can override these in
    | your models by defining constants.
    |
    */
    'columns' => [
        // User stamping
        'created_by' => 'created_by',
        'updated_by' => 'updated_by',
        'deleted_by' => 'deleted_by',
        // Team stamping
        'created_by_team' => 'created_by_team',
        'updated_by_team' => 'updated_by_team',
        'deleted_by_team' => 'deleted_by_team',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This is the model used for user relationships
    |
    */
    'user_model' => config('auth.providers.users.model', 'App\\Models\\User'),

    /*
    |--------------------------------------------------------------------------
    | Team Model
    |--------------------------------------------------------------------------
    |
    | This is the model used for team relationships if using team stamping
    |
    */
    'team_model' => 'App\\Models\\Team',

    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    |
    | Default values to use when no user is authenticated
    |
    */
    'default_user_id' => null,
    'default_team_id' => null,

    /*
    |--------------------------------------------------------------------------
    | Behavior Settings
    |--------------------------------------------------------------------------
    |
    | Configure how user stamping behaves in various scenarios
    |
    */
    'skip_when_no_user' => true,
    'enable_team_stamping' => false,
    'log_stamping_errors' => true,
];
