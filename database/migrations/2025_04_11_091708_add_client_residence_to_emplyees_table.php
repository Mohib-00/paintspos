<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emplyees', function (Blueprint $table) {
            $table->string('client_residence')->nullable()->after('client_father_name');
        });
    }

    public function down(): void
    {
        Schema::table('emplyees', function (Blueprint $table) {
            $table->dropColumn('client_residence');
        });
    }
};
