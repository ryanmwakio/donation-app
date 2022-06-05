<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->integer('amount');
            $table->string('period_of_donation');
            $table->dateTime('last_donation_date')->nullable();
            $table->dateTime('next_payment_date')->nullable();
            $table->string('merchant_reference')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('status')->default('pending');
            $table->string('order_tracking_id')->nullable();
            $table->integer('reminders_sent')->default(0);
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
        Schema::dropIfExists('donors');
    }
};
