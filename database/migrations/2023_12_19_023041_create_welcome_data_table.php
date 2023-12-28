<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('welcome_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sale_area_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('install_process_id')->nullable();
            $table->unsignedBigInteger('remark_field_id')->nullable();
            $table->unsignedBigInteger('applicant_response_id')->nullable();
            $table->string('application_id')->nullable();
            $table->string('version')->nullable();
            $table->string('contract_no')->nullable();
            $table->string('phone_no')->nullable();
            $table->longText('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welcome_data');
    }
};
