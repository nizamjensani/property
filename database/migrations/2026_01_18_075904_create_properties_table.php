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
        // Schema::table('users', function (Blueprint $table){
        //     $table->foreign('city')->references('id')->on('cities')->nullOnDelete();
        //     $table->foreign('state')->references('id')->on('states')->nullOnDelete();
        // });
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // Ownership / agent
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference_no')->nullable()->unique();
        
            // Core
            $table->string('title');
            $table->longText('description')->nullable();
        
            $table->enum('listing_type', ['sale', 'rent'])->index();
            $table->enum('status', ['draft', 'published', 'archived', 'sold', 'rented'])->default('draft')->index();
        
            $table->foreignId('property_type_id')->nullable()->constrained('property_types')->nullOnDelete();
            $table->enum('tenure', ['freehold', 'leasehold', 'unknown'])->default('unknown');
            $table->enum('completion_status', ['under_construction', 'completed'])->default('completed');
            $table->year('build_year')->nullable();
        
            // Address
            $table->string('address');
            $table->string('address_2')->nullable();
            $table->string('postcode');
            $table->foreignId('city')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('state')->nullable()->constrained('states')->nullOnDelete();
        
            // Geo
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
        
            // Pricing
            $table->unsignedBigInteger('price')->nullable();        // for sale
            $table->unsignedBigInteger('monthly_rent')->nullable(); // for rent
            $table->string('currency', 3)->default('MYR');
            $table->unsignedTinyInteger('deposit_months')->nullable();
            $table->unsignedInteger('maintenance_fee')->nullable(); // monthly
            $table->boolean('negotiable')->default(false);
        
            // Specs
            $table->unsignedInteger('built_up_sqft')->nullable();
            $table->unsignedInteger('land_area_sqft')->nullable();
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->unsignedTinyInteger('carparks')->nullable();
            $table->unsignedSmallInteger('floor_level')->nullable();
            $table->enum('furnishing', ['unfurnished', 'partial', 'fully'])->nullable();
        
            // Publish / availability
            $table->date('available_from')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('expires_at')->nullable()->index();
        
            // Analytics / internal
            $table->unsignedBigInteger('views_count')->default(0);
            $table->text('notes_internal')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropConstrainedForeignId('city');
        //     $table->dropConstrainedForeignId('state');
        // });
        Schema::dropIfExists('properties');
    }
};
