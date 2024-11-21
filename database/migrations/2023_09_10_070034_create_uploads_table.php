<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("alternative_name")->nullable();
            $table->text("hash")->nullable();
            $table->string("extension")->nullable();
            $table->string("path")->nullable();
            $table->string("mime")->nullable();
            $table->string("url")->nullable();
            $table->double("size")->nullable();
            $table->integer("width")->nullable();
            $table->integer("height")->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('uploads');
    }
};
