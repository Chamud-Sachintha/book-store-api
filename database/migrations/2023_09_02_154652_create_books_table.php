<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_name');
            $table->string('pdf+path');
            $table->string('book_cover');
            $table->string('book_category_id');
            $table->string('book_description');
            $table->decimal('book_price', 5, 2);
            $table->integer('year');
            $table->string('author_name');
            $table->string('tags');
            $table->integer('rating');
            $table->integer('status');
            $table->integer('create_time');
            
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
        Schema::dropIfExists('books');
    }
}
