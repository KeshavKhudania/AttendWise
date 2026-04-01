<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('institution_venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // Ground, Hostel, Other, etc.
            $table->text('latlng')->nullable(); // Polygon geofence JSON
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('radius', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // Update event venues to support new Venue model
        Schema::table('institution_event_venues', function (Blueprint $table) {
            $table->foreignId('venue_id')->nullable()->after('classroom_id')->constrained('institution_venues')->onDelete('cascade');
            $table->unsignedBigInteger('classroom_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('institution_event_venues', function (Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropColumn('venue_id');
            $table->unsignedBigInteger('classroom_id')->nullable(false)->change();
        });
        Schema::dropIfExists('institution_venues');
    }
};