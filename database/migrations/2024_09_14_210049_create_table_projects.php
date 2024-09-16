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
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('description')->nullable()->default(null);
            $table->unsignedBigInteger('user_id');
            $table->dateTime('conclusion_at')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->constrained(
                    table: 'users', indexName: 'users_user_id'
                );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_user_id_foreign');
        });

        Schema::dropIfExists('projects');
    }
};
