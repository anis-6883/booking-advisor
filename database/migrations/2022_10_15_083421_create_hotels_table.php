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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approved_by');
            $table->string('hotel_unique_id', 127)->unique();
            $table->string('hotel_name', 255);
            $table->string('hotel_email', 255)->unique();
            $table->string('hotel_image', 255)->default('public/default/hotel_image.png');
            $table->text('address');
            $table->text('description')->nullable();
            $table->string('division', 127);
            $table->string('district', 127);
            $table->string('upazila', 127);
            $table->string('post_code', 15);
            $table->string('phone_one', 31)->nullable();
            $table->string('phone_two', 31)->nullable();
            $table->string('mobile_one', 31)->nullable();
            $table->string('mobile_two', 31)->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('hotels');
    }
};
