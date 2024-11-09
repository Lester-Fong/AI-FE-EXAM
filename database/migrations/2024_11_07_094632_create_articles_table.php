<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * id
     * Image (url, url of the saved image file in the server, do not use blob data type) 
     * Title (text)
     * Link (url)
     * Date (Date)
     * Content (text)
     * Status [For Edit, Published]
     * Writer (User FK)
     * Editor (User FK)
     * Company (Company FK)
     * @return void
     */
    public function up()
    {
        Schema::create('tblArticles', function (Blueprint $table) {
            $table->bigIncrements('fldArticleID');
            $table->string('fldArticleImage', 500)->nullable();
            $table->text('fldArticleTitle')->nullable();
            $table->text('fldArticleLink')->nullable();
            $table->date('fldArticleDate')->nullable()->default(date('Y-m-d'));
            $table->text('fldArticleContent')->nullable();
            $table->integer('fldArticleStatus')->nullable()->default(0)->comment('0 - For Edit, 1 - Published');
            $table->bigInteger('fldArticleWriterID')->unsigned()->nullable();
            $table->bigInteger('fldArticleEditorID')->unsigned()->nullable();
            $table->unsignedBigInteger('fldArticleCompanyID')->index('fldArticleCompanyID'); // Foreign Key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblArticles');
    }
}
