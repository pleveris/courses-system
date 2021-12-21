<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    public function up(): void
    {
        Schema::create('files', static function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('folder_id')->unsigned();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('files', static function (Blueprint $table): void {
            $table->foreign('folder_id')->references('id')->on('folders');
        });
    }

    public function down(): void
    {
        Schema::drop('files');
    }
}
