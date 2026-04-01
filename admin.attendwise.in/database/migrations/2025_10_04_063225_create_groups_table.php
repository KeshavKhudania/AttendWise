<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_admin_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., super_admin, admin
            $table->bigInteger('institution_id')->foreign("institution_id")->references("id")->on("institutions");
            $table->text('description')->nullable();
            $table->text('permissions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_admin_groups');
    }
};