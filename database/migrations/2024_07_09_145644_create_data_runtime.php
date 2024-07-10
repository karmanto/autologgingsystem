<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataRuntime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_runtime', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cbc1')->default(0);
            $table->bigInteger('cbc2')->default(0);
            $table->bigInteger('prs1')->default(0);
            $table->bigInteger('prs2')->default(0);
            $table->bigInteger('prs3')->default(0);
            $table->bigInteger('prs4')->default(0);
            $table->bigInteger('prs5')->default(0);
            $table->bigInteger('prs6')->default(0);
            $table->bigInteger('prs7')->default(0);
            $table->bigInteger('prs8')->default(0);
            $table->bigInteger('dtr1')->default(0);
            $table->bigInteger('dtr2')->default(0);
            $table->bigInteger('dtr3')->default(0);
            $table->bigInteger('dtr4')->default(0);
            $table->bigInteger('dtr5')->default(0);
            $table->bigInteger('dtr6')->default(0);
            $table->bigInteger('dtr7')->default(0);
            $table->bigInteger('dtr8')->default(0);
            $table->bigInteger('spr0')->default(0);
            $table->bigInteger('spr1')->default(0);
            $table->bigInteger('spr2')->default(0);
            $table->bigInteger('spr3')->default(0);
            $table->bigInteger('spr4')->default(0);
            $table->bigInteger('spr5')->default(0);
            $table->bigInteger('spr6')->default(0);
            $table->bigInteger('spr7')->default(0);
            $table->bigInteger('spr8')->default(0);
            $table->bigInteger('spr9')->default(0);
            $table->bigInteger('spr10')->default(0);
            $table->bigInteger('spr11')->default(0);
            $table->bigInteger('spr12')->default(0);
            $table->bigInteger('spr13')->default(0);
            $table->bigInteger('spr14')->default(0);
            $table->bigInteger('spr15')->default(0);
            $table->bigInteger('spr16')->default(0);
            $table->bigInteger('spr17')->default(0);
            $table->bigInteger('spr18')->default(0);
            $table->bigInteger('spr19')->default(0);
            $table->bigInteger('spr20')->default(0);
            $table->bigInteger('spr21')->default(0);
            $table->bigInteger('spr22')->default(0);
            $table->bigInteger('spr23')->default(0);
            $table->bigInteger('spr24')->default(0);
            $table->bigInteger('spr25')->default(0);
            $table->bigInteger('spr26')->default(0);
            $table->bigInteger('spr27')->default(0);
            $table->bigInteger('spr28')->default(0);
            $table->bigInteger('spr29')->default(0);
            $table->bigInteger('spr30')->default(0);
            $table->bigInteger('spr31')->default(0);
            $table->bigInteger('spr32')->default(0);
            $table->bigInteger('spr33')->default(0);
            $table->bigInteger('spr34')->default(0);
            $table->bigInteger('spr35')->default(0);
            $table->bigInteger('spr36')->default(0);
            $table->bigInteger('spr37')->default(0);
            $table->bigInteger('spr38')->default(0);
            $table->bigInteger('spr39')->default(0);
            $table->bigInteger('spr40')->default(0);
            $table->bigInteger('spr41')->default(0);
            $table->bigInteger('spr42')->default(0);
            $table->bigInteger('spr43')->default(0);
            $table->bigInteger('spr44')->default(0);
            $table->bigInteger('spr45')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_data_runtime');
    }
}
