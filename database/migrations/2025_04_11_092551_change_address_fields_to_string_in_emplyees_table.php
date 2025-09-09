<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emplyees', function (Blueprint $table) {
            $table->string('client_permanent_address')->nullable()->change();
            $table->string('client_residential_address')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('emplyees', function (Blueprint $table) {
            $table->decimal('client_permanent_address', 8, 2)->nullable()->change();
            $table->decimal('client_residential_address', 8, 2)->nullable()->change();
        });
    }
};
