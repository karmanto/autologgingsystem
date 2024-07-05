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
            $table->timestamps();
            $table->boolean('cbc1');
            $table->boolean('cbc2');
            $table->boolean('prs1');
            $table->boolean('prs2');
            $table->boolean('prs3');
            $table->boolean('prs4');
            $table->boolean('prs5');
            $table->boolean('prs6');
            $table->boolean('prs7');
            $table->boolean('prs8');
            $table->boolean('dtr1');
            $table->boolean('dtr2');
            $table->boolean('dtr3');
            $table->boolean('dtr4');
            $table->boolean('dtr5');
            $table->boolean('dtr6');
            $table->boolean('dtr7');
            $table->boolean('dtr8');
            $table->boolean('spr0');
            $table->boolean('spr1');
            $table->boolean('spr2');
            $table->boolean('spr3');
            $table->boolean('spr4');
            $table->boolean('spr5');
            $table->boolean('spr6');
            $table->boolean('spr7');
            $table->boolean('spr8');
            $table->boolean('spr9');
            $table->boolean('spr10');
            $table->boolean('spr11');
            $table->boolean('spr12');
            $table->boolean('spr13');
            $table->boolean('spr14');
            $table->boolean('spr15');
            $table->boolean('spr16');
            $table->boolean('spr17');
            $table->boolean('spr18');
            $table->boolean('spr19');
            $table->boolean('spr20');
            $table->boolean('spr21');
            $table->boolean('spr22');
            $table->boolean('spr23');
            $table->boolean('spr24');
            $table->boolean('spr25');
            $table->boolean('spr26');
            $table->boolean('spr27');
            $table->boolean('spr28');
            $table->boolean('spr29');
            $table->boolean('spr30');
            $table->boolean('spr31');
            $table->boolean('spr32');
            $table->boolean('spr33');
            $table->boolean('spr34');
            $table->boolean('spr35');
            $table->boolean('spr36');
            $table->boolean('spr37');
            $table->boolean('spr38');
            $table->boolean('spr39');
            $table->boolean('spr40');
            $table->boolean('spr41');
            $table->boolean('spr42');
            $table->boolean('spr43');
            $table->boolean('spr44');
            $table->boolean('spr45');
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
