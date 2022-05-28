<?php

use App\Enum\Status;
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
        Schema::create('payment_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('authorization_code');
            $table->string('signature');
            $table->string('card_type');
            $table->string('last4');
            $table->string('email');
            $table->string('exp_month');
            $table->string('exp_year');
            $table->string('bin');
            $table->string('bank');
            $table->string('channel');
            $table->string('reusable');
            $table->string('country_code');
            $table->string('status')->default(Status::ACTIVE->value)->index();
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
        Schema::dropIfExists('payment_cards');
    }
};
