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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->unsignedBigInteger('head_level');
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('account_type_id');
            $table->boolean('is_cash_nature')->default(false);
            $table->boolean('is_bank_nature')->default(false);
            $table->boolean('is_budget')->default(false);
            $table->boolean('is_depreciation')->default(false);
            $table->boolean('is_subtype')->default(false);
            $table->boolean('is_stock')->default(false);
            $table->boolean('is_fixed_asset_schedule')->default(false);
            $table->unsignedBigInteger('depreciation_rate')->nullable();
            $table->string('note_no')->nullable();
            $table->string('asset_code')->nullable();
            $table->string('depreciation_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('chart_of_accounts');
    }
};
