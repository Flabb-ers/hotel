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
        // Tabel Petugas (Admin/Staff)
        Schema::create('tb_petugas', function (Blueprint $table) {
            $table->id('id_petugas');
            $table->string('nama_lengkap');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('roles', ['admin', 'staff'])->default('staff');
            $table->timestamps();
        });

        // Tabel Layanan
        Schema::create('tb_layanan', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->string('nama_layanan');
            $table->decimal('harga', 10, 2);
            $table->timestamps();
        });

        // Tabel Tipe Kamar
        Schema::create('tb_tipe_kamar', function (Blueprint $table) {
            $table->id('id_tipe');
            $table->string('nama_tipe');
            $table->unsignedBigInteger('id_layanan')->nullable();
            $table->timestamps();

            $table->foreign('id_layanan')->references('id_layanan')->on('tb_layanan');
        });


        // Tabel Kamar
        Schema::create('tb_kamar', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->unsignedBigInteger('id_tipe_kamar');
            $table->string('nomer_kamar')->unique();
            $table->integer('jumlah_bed');
            $table->decimal('harga', 10, 2);
            $table->text('fasilitas')->nullable();
            $table->string('thumbnail_kamar')->nullable();
            $table->enum('status', ['tersedia', 'terisi', 'maintenance'])->default('tersedia');
            $table->timestamps();

            $table->foreign('id_tipe_kamar')->references('id_tipe')->on('tb_tipe_kamar');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kamar');
        Schema::dropIfExists('tb_tipe_kamar');
        Schema::dropIfExists('tb_layanan');
        Schema::dropIfExists('tb_petugas');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
