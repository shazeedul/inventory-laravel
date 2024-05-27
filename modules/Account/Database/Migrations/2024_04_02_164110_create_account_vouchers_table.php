<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chart_of_account_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->unsignedBigInteger('account_sub_type_id')->nullable();
            $table->unsignedBigInteger('account_sub_code_id')->nullable();
            $table->unsignedBigInteger('account_voucher_type_id');
            $table->string('voucher_no');
            $table->date('voucher_date');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('narration')->nullable();
            $table->text('cheque_no')->nullable();
            $table->string('cheque_date')->nullable();
            $table->boolean('is_honour')->default(false);
            $table->text('ledger_comment')->nullable();
            $table->decimal('debit', 20, 2)->nullable();
            $table->decimal('credit', 20, 2)->nullable();
            $table->unsignedBigInteger('reverse_code')->nullable();
            $table->unsignedBigInteger('reverse_sub_type_id')->nullable();
            $table->unsignedBigInteger('reverse_sub_code_id')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_auto')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_vouchers');
    }
};
