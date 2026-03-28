<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_name')) {
                $table->string('product_name')->nullable()->after('farmer_id');
            }

            if (!Schema::hasColumn('products', 'variety_size')) {
                $table->string('variety_size')->nullable()->after('product_name');
            }

            if (!Schema::hasColumn('products', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0)->after('price');
            }

            if (Schema::hasColumn('products', 'egg_type')) {
                $table->dropColumn('egg_type');
            }

            if (Schema::hasColumn('products', 'egg_category')) {
                $table->dropColumn('egg_category');
            }

            if (Schema::hasColumn('products', 'jumbo')) {
                $table->dropColumn('jumbo');
            }
        });

        if (Schema::hasTable('size_transactions')) {
            Schema::drop('size_transactions');
        }

        if (Schema::hasTable('sizes')) {
            Schema::drop('sizes');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'egg_type')) {
                $table->string('egg_type')->nullable()->after('description');
            }

            if (!Schema::hasColumn('products', 'egg_category')) {
                $table->string('egg_category')->nullable()->after('egg_type');
            }

            if (!Schema::hasColumn('products', 'jumbo')) {
                $table->boolean('jumbo')->default(false)->after('egg_category');
            }

            if (Schema::hasColumn('products', 'variety_size')) {
                $table->dropColumn('variety_size');
            }

            if (Schema::hasColumn('products', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
        });

        if (!Schema::hasTable('sizes')) {
            Schema::create('sizes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('farmer_id');
                $table->unsignedBigInteger('product_id');
                $table->string('egg_type');
                $table->string('size_name');
                $table->integer('tray_count');
                $table->decimal('price_per_tray', 8, 2);
                $table->decimal('total_price', 10, 2);
                $table->timestamps();

                $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('size_transactions')) {
            Schema::create('size_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('transaction_id');
                $table->unsignedBigInteger('size_id');
                $table->string('size_name');
                $table->integer('tray_count');
                $table->decimal('price_per_tray', 10, 2);
                $table->decimal('total_price', 10, 2);
                $table->timestamps();

                $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
                $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            });
        }
    }
};
