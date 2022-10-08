<?php

use Illuminate\Session\TokenMismatchException;

/**
 * FRONT
 */
Route::get('branch', [
    'as' => 'branch',
    'uses' => 'Foostart\Branch\Controllers\Front\BranchFrontController@index'
]);
Route::get('branch/search/', [
    'as' => 'branch',
    'uses' => 'Foostart\Branch\Controllers\Front\BranchFrontController@search'
]);
Route::get('branch/{slug}', [
    'as' => 'branch',
    'uses' => 'Foostart\Branch\Controllers\Front\BranchFrontController@show'
]);

/**
 * ADMINISTRATOR
 */
Route::group(['middleware' => ['web']], function () {

    Route::group(['middleware' => ['admin_logged', 'can_see', 'in_context'],
                  'namespace' => 'Foostart\Branch\Controllers\Admin',
        ], function () {

        /*
          |-----------------------------------------------------------------------
          | Manage branch
          |-----------------------------------------------------------------------
          | 1. List of companies
          | 2. Edit branch
          | 3. Delete branch
          | 4. Add new branch
          | 5. Manage configurations
          | 6. Manage languages
          |
        */

        /**
         * list
         */
        Route::get('admin/branch', [
            'as' => 'branch.list',
            'uses' => 'BranchAdminController@index'
        ]);

        /**
         * edit-add
         */
        Route::get('admin/branch/edit', [
            'as' => 'branch.edit',
            'uses' => 'BranchAdminController@edit'
        ]);

        /**
         * copy
         */
        Route::get('admin/branch/copy', [
            'as' => 'branch.copy',
            'uses' => 'BranchAdminController@copy'
        ]);

        /**
         * branch
         */
        Route::post('admin/branch/edit', [
            'as' => 'branch.branch',
            'uses' => 'BranchAdminController@branch'
        ]);

        /**
         * delete
         */
        Route::get('admin/branch/delete', [
            'as' => 'branch.delete',
            'uses' => 'BranchAdminController@delete'
        ]);

        /**
         * trash
         */
         Route::get('admin/branch/trash', [
            'as' => 'branch.trash',
            'uses' => 'BranchAdminController@trash'
        ]);

        /**
         * configs
        */
        Route::get('admin/branch/config', [
            'as' => 'branch.config',
            'uses' => 'BranchAdminController@config'
        ]);

        Route::post('admin/branch/config', [
            'as' => 'branch.config',
            'uses' => 'BranchAdminController@config'
        ]);

        /**
         * language
        */
        Route::get('admin/branch/lang', [
            'as' => 'branch.lang',
            'uses' => 'BranchAdminController@lang'
        ]);

        Route::post('admin/branch/lang', [
            'as' => 'branch.lang',
            'uses' => 'BranchAdminController@lang'
        ]);

    });
});
