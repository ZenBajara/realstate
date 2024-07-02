<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('pincode');
            $table->unsignedBigInteger('property_type_id');
            $table->integer('num_bathrooms');
            $table->integer('num_bedrooms');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            // Assuming you have a `property_types` table with an `id` column
            $table->foreign('property_type_id')->references('id')->on('property_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
