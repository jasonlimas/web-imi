<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateSalesOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('so_nbr')->unique();
            $table->foreignId('so_cust')->constrained('master_customers')->onDelete('cascade');
            $table->date('so_ord_date');
            $table->date('so_due_date');
            $table->string('so_status');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();  // Soft delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_orders');
    }
}
