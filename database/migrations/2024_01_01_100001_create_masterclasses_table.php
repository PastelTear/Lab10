<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('masterclasses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leader_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('craft_categories')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->date('mc_date');
            $table->time('start_time');
            $table->unsignedInteger('max_participants');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->unique(['leader_id', 'mc_date', 'start_time']);
            $table->index(['category_id', 'mc_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('masterclasses');
    }
};
