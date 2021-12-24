<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('voivodeship')->nullable();
            $table->string('address')->nullable();
            $table->string('community')->nullable();
            $table->string('township')->nullable();
            $table->string('district')->nullable();
            $table->string('postcode')->nullable();
            $table->bigInteger('regon_id')->nullable();
            $table->bigInteger('silos_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
