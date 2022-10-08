<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('branch');
        Schema::create('branch', function (Blueprint $table) {
            $table->id('branch_id');
            $table->integer('user_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_full_name')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('slideshow_id')->nullable();
            $table->string('branch_name', 500)->nullable();
            $table->string('branch_slug', 500)->nullable();
            $table->string('branch_overview', 1000)->nullable();
            $table->longtext('branch_description')->nullable();
            $table->string('branch_image')->nullable();
            $table->string('branch_files', 10000)->nullable();
            $table->tinyinteger('branch_status')->nullable();
            $table->text('cache_comments')->nullable();
            $table->text('cache_other_branch')->nullable();
            $table->integer('cache_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
