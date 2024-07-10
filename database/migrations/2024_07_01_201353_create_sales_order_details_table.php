<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('sales_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('so_mstr_id')->constrained('sales_orders')->onDelete('cascade');
            $table->integer('line');
            $table->foreignId('item_id')->constrained('master_items')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_order_details');
    }
}
