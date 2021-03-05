<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->unsignedDecimal('total_charges_amount')->after('description');
            $table->string('total_charges_currency', 3)->after('total_charges_amount');
            $table->unsignedDecimal('budget_amount')->after('total_charges_currency');
            $table->string('budget_currency', 3)->after('budget_amount');
            $table->text('reply')->after('budget_currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['total_charges_amount', 'total_charges_currency', 'budget_amount', 'budget_currency', 'reply']);
        });
    }
}
