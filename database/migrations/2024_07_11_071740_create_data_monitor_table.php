<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataMonitorTable extends Migration
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
            $table->timestamp('created_at')->useCurrent()->index();
            $table->boolean('cbc1')->default(0);
            $table->boolean('cbc2')->default(0);
            $table->boolean('prs1')->default(0);
            $table->boolean('prs2')->default(0);
            $table->boolean('prs3')->default(0);
            $table->boolean('prs4')->default(0);
            $table->boolean('prs5')->default(0);
            $table->boolean('prs6')->default(0);
            $table->boolean('prs7')->default(0);
            $table->boolean('prs8')->default(0);
            $table->boolean('dtr1')->default(0);
            $table->boolean('dtr2')->default(0);
            $table->boolean('dtr3')->default(0);
            $table->boolean('dtr4')->default(0);
            $table->boolean('dtr5')->default(0);
            $table->boolean('dtr6')->default(0);
            $table->boolean('dtr7')->default(0);
            $table->boolean('dtr8')->default(0);
            $table->boolean('spr0')->default(0);
            $table->boolean('spr1')->default(0);
            $table->boolean('spr2')->default(0);
            $table->boolean('spr3')->default(0);
            $table->boolean('spr4')->default(0);
            $table->boolean('spr5')->default(0);
            $table->boolean('spr6')->default(0);
            $table->boolean('spr7')->default(0);
            $table->boolean('spr8')->default(0);
            $table->boolean('spr9')->default(0);
            $table->boolean('spr10')->default(0);
            $table->boolean('spr11')->default(0);
            $table->boolean('spr12')->default(0);
            $table->boolean('spr13')->default(0);
            $table->boolean('spr14')->default(0);
            $table->boolean('spr15')->default(0);
            $table->boolean('spr16')->default(0);
            $table->boolean('spr17')->default(0);
            $table->boolean('spr18')->default(0);
            $table->boolean('spr19')->default(0);
            $table->boolean('spr20')->default(0);
            $table->boolean('spr21')->default(0);
            $table->boolean('spr22')->default(0);
            $table->boolean('spr23')->default(0);
            $table->boolean('spr24')->default(0);
            $table->boolean('spr25')->default(0);
            $table->boolean('spr26')->default(0);
            $table->boolean('spr27')->default(0);
            $table->boolean('spr28')->default(0);
            $table->boolean('spr29')->default(0);
            $table->boolean('spr30')->default(0);
            $table->boolean('spr31')->default(0);
            $table->boolean('spr32')->default(0);
            $table->boolean('spr33')->default(0);
            $table->boolean('spr34')->default(0);
            $table->boolean('spr35')->default(0);
            $table->boolean('spr36')->default(0);
            $table->boolean('spr37')->default(0);
            $table->boolean('spr38')->default(0);
            $table->boolean('spr39')->default(0);
            $table->boolean('spr40')->default(0);
            $table->boolean('spr41')->default(0);
            $table->boolean('spr42')->default(0);
            $table->boolean('spr43')->default(0);
            $table->boolean('spr44')->default(0);
            $table->boolean('spr45')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_monitor');
    }
}
