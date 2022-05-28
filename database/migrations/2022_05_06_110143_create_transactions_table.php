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
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactable');
            $table->foreignid('customer_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('wallet_id')->nullable()->constrained();
            $table->string('description');
            $table->decimal('amount')->index();
            $table->string('status')->index();
            $table->string('type')->index();
            $table->string('category')->nullable()->index();
            $table->string('payment_method')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
