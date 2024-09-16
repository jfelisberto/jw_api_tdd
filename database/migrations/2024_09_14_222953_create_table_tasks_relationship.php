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
        Schema::create('tasks_relationship', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::table('tasks_relationship', function (Blueprint $table) {
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->constrained(
                    table: 'tasks', indexName: 'tasks_task_id'
                );

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
        Schema::table('tasks_relationship', function (Blueprint $table) {
            $table->dropForeign('tasks_relationship_task_id_foreign');
            $table->dropForeign('tasks_relationship_user_id_foreign');
        });

        Schema::dropIfExists('tasks_relationship');
    }
};
