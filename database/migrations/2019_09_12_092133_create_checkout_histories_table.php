<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckoutHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_histories', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("customer_id");
            $table->string("title");
            $table->integer("qty");
            $table->integer("unit_price");
            $table->string("total");
            $table->string("payment_ref");
            $table->string('payment_type');
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
        Schema::dropIfExists('checkout_histories');
    }
}
