<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('image')->nullable()->after('content');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->string('image')->nullable()->after('content');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->string('profile_bg_color')->default('#667eea')->after('avatar');
            $table->string('profile_bg_color2')->default('#764ba2')->after('profile_bg_color');
            $table->string('profile_card_color')->default('#ffffff')->after('profile_bg_color2');
            $table->string('profile_text_color')->default('#2c3e50')->after('profile_card_color');
            $table->string('profile_accent_color')->default('#3498db')->after('profile_text_color');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'profile_bg_color', 'profile_bg_color2', 'profile_card_color', 'profile_text_color', 'profile_accent_color']);
        });
    }
};
