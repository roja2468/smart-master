<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainCategoryToPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->integer('main_category_id')->nullable();
            $table->float('package_mrp', 11, 2)->nullable();
            $table->string('package_offer')->nullable();
            $table->longText('about_course_description')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_designation')->nullable();
            $table->string('author_qualification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->dropColumn('main_category_id');
            $table->dropColumn('package_mrp');
            $table->dropColumn('package_offer');
            $table->dropColumn('about_course_description');
            $table->dropColumn('author_name');
            $table->dropColumn('author_designation');
            $table->dropColumn('author_qualification');
        });
    }
}
