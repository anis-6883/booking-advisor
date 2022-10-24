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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('bed_type_id');
            $table->string('room_title', 255);
            $table->string('master_image', 255);
            $table->double('regular_price', 8, 2);
            $table->integer('total_room');
            $table->integer('capacity');
            $table->integer('square_meter')->nullable();
            $table->double('tax_price', 8, 2)->nullable();
            $table->integer('discounted_pct')->nullable();
            $table->dateTimeTz('discount_start_date')->nullable();
            $table->dateTimeTz('discount_end_date')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('rooms');
    }
};
