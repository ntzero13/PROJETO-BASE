<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inspection_room_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('inspection_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('foto');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_attachments');
    }
};
