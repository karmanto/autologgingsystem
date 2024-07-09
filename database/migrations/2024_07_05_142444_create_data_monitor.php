<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataMonitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_monitor', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->boolean('cbc1')->nullable();
            $table->boolean('cbc2')->nullable();
            $table->boolean('prs1')->nullable();
            $table->boolean('prs2')->nullable();
            $table->boolean('prs3')->nullable();
            $table->boolean('prs4')->nullable();
            $table->boolean('prs5')->nullable();
            $table->boolean('prs6')->nullable();
            $table->boolean('prs7')->nullable();
            $table->boolean('prs8')->nullable();
            $table->boolean('dtr1')->nullable();
            $table->boolean('dtr2')->nullable();
            $table->boolean('dtr3')->nullable();
            $table->boolean('dtr4')->nullable();
            $table->boolean('dtr5')->nullable();
            $table->boolean('dtr6')->nullable();
            $table->boolean('dtr7')->nullable();
            $table->boolean('dtr8')->nullable();
            $table->boolean('spr0')->nullable();
            $table->boolean('spr1')->nullable();
            $table->boolean('spr2')->nullable();
            $table->boolean('spr3')->nullable();
            $table->boolean('spr4')->nullable();
            $table->boolean('spr5')->nullable();
            $table->boolean('spr6')->nullable();
            $table->boolean('spr7')->nullable();
            $table->boolean('spr8')->nullable();
            $table->boolean('spr9')->nullable();
            $table->boolean('spr10')->nullable();
            $table->boolean('spr11')->nullable();
            $table->boolean('spr12')->nullable();
            $table->boolean('spr13')->nullable();
            $table->boolean('spr14')->nullable();
            $table->boolean('spr15')->nullable();
            $table->boolean('spr16')->nullable();
            $table->boolean('spr17')->nullable();
            $table->boolean('spr18')->nullable();
            $table->boolean('spr19')->nullable();
            $table->boolean('spr20')->nullable();
            $table->boolean('spr21')->nullable();
            $table->boolean('spr22')->nullable();
            $table->boolean('spr23')->nullable();
            $table->boolean('spr24')->nullable();
            $table->boolean('spr25')->nullable();
            $table->boolean('spr26')->nullable();
            $table->boolean('spr27')->nullable();
            $table->boolean('spr28')->nullable();
            $table->boolean('spr29')->nullable();
            $table->boolean('spr30')->nullable();
            $table->boolean('spr31')->nullable();
            $table->boolean('spr32')->nullable();
            $table->boolean('spr33')->nullable();
            $table->boolean('spr34')->nullable();
            $table->boolean('spr35')->nullable();
            $table->boolean('spr36')->nullable();
            $table->boolean('spr37')->nullable();
            $table->boolean('spr38')->nullable();
            $table->boolean('spr39')->nullable();
            $table->boolean('spr40')->nullable();
            $table->boolean('spr41')->nullable();
            $table->boolean('spr42')->nullable();
            $table->boolean('spr43')->nullable();
            $table->boolean('spr44')->nullable();
            $table->boolean('spr45')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_data_monitor');
    }
}
