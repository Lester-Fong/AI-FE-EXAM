<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     */
    public function up(): void
    {
        Schema::create('tblUser', function (Blueprint $table) {
            $table->bigIncrements('fldUserID');
            $table->string('fldUserFirstname', 250)->nullable();
            $table->string('fldUserLastname', 250)->nullable();
            $table->string('fldUserEmail', 250)->nullable();
            $table->string('fldUserPassword', 250)->nullable();
            $table->integer('fldUserType')->nullable()->default(0)->comment('0 - Writer, 1 - Editor');
            $table->boolean('fldUserStatus')->nullable()->default(1)->comment('0 - Inactive, 1 - Active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblUser');
    }
};