<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWkMallMerchandiseTable extends Migration
{
    public function up()
    {
        Schema::create(config('wk-core.table.mall-merchandise.products'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('host');
            $table->string('serial')->nullable();
            $table->string('identifier')->nullable();
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->index('serial');
            $table->index('identifier');
            $table->index('is_enabled');
            $table->index(['host_type', 'host_id', 'is_enabled']);
        });
        if (!config('wk-mall-merchandise.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.mall-merchandise.products_lang'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('morph');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->longText('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        }

        Schema::create(config('wk-core.table.mall-merchandise.variants'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->string('serial')->nullable();
            $table->string('identifier')->nullable();
            $table->unsignedDecimal('cost', config('wk-mall-merchandise.unsigned_decimal.precision'), config('wk-mall-merchandise.unsigned_decimal.scale'))->nullable();
            $table->unsignedDecimal('price', config('wk-mall-merchandise.unsigned_decimal.precision'), config('wk-mall-merchandise.unsigned_decimal.scale'))->nullable();
            $table->unsignedDecimal('price_sale', config('wk-mall-merchandise.unsigned_decimal.precision'), config('wk-mall-merchandise.unsigned_decimal.scale'))->nullable();
            $table->json('covers')->nullable();
            $table->json('images')->nullable();
            $table->json('videos')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_enabled')->default(0);

            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')
                  ->on(config('wk-core.table.mall-merchandise.products'))
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->index('serial');
            $table->index('is_enabled');
        });
        if (!config('wk-mall-merchandise.onoff.core-lang_core')) {
            Schema::create(config('wk-core.table.mall-merchandise.variants_lang'), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('morph');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('code');
                $table->string('key');
                $table->longText('value')->nullable();
                $table->boolean('is_current')->default(1);

                $table->timestampsTz();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')
                    ->on(config('wk-core.table.user'))
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });
        }
    }

    public function down() {
        Schema::dropIfExists(config('wk-core.table.mall-merchandise.variants_lang'));
        Schema::dropIfExists(config('wk-core.table.mall-merchandise.variants'));
        Schema::dropIfExists(config('wk-core.table.mall-merchandise.products_lang'));
        Schema::dropIfExists(config('wk-core.table.mall-merchandise.products'));
    }
}
