<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new Class extends Migration
{
    public function up()
    {
        Schema::table('return_refund_items', function (Blueprint $table) {
            $table->text('reject_reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('return_refund_items', function (Blueprint $table) {
            $table->dropColumn('reject_reason');
        });
    }
};