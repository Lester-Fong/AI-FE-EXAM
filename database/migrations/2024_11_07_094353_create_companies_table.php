<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     * id
     * logo (url)
     * name (text)
     * Status [Active,Inactive]
     * @return void
     */
    public function up()
    {
        Schema::create('tblCompany', function (Blueprint $table) {
            $table->bigIncrements('fldCompanyID');
            $table->string('fldCompanyLogo', 250)->nullable();
            $table->text('fldCompanyName')->nullable();
            $table->integer('fldCompanyStatus')->nullable()->default(0)->comment('0 - Active, 1 - Inactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblCompany');
    }
}
