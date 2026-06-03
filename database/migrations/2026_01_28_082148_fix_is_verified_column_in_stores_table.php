<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Update data string ke integer
        DB::statement("UPDATE stores SET is_verified = '0' WHERE is_verified = 'false'");
        DB::statement("UPDATE stores SET is_verified = '1' WHERE is_verified = 'true'");
        
        // Step 2: Ubah tipe kolom ke boolean
        Schema::table('stores', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('is_verified')->default('false')->change();
        });
    }
};