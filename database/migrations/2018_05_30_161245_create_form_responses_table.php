<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('rinvex.forms.tables.form_responses'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('unique_identifier')->nullable();
            $table->{$this->jsonable()}('content');
            $table->integer('form_id')->unsigned();
            $table->nullableMorphs('user');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->foreign('form_id')->references('id')->on(config('rinvex.forms.tables.forms'))
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('rinvex.forms.tables.form_responses'));
    }

    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    protected function jsonable(): string
    {
        return DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
               && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '5.7.8', 'ge')
            ? 'json' : 'text';
    }
}
