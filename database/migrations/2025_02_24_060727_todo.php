<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('title'); // Title of the todo
            $table->text('description')->nullable(); // Optional description
            $table->boolean('completed')->default(false); // Completion status
            $table->unsignedBigInteger('user_id'); // Foreign key for the user
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
    
            // Define the foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('todos'); // Drop the table if the migration is rolled back
    }
};
