<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('profilable_id')->nullable()->after('id');
            $table->string('profilable_type')->nullable()->after('profilable_id');
            $table->index(['profilable_id','profilable_type']);
        });
    }
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['profilable_id','profilable_type']);
            $table->dropColumn(['profilable_id','profilable_type']);
        });
    }
};
