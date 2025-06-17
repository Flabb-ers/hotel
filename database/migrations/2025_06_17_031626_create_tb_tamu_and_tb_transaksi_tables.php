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
        // Tabel Tamu
        Schema::create('tb_tamu', function (Blueprint $table) {
            $table->id('id_tamu');
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_telp');
            $table->text('alamat');
            $table->date('tanda_pengenal');
            $table->timestamps();
        });

        // Tabel Transaksi
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_tamu');
            $table->unsignedBigInteger('id_kamar');
            $table->decimal('sub_total', 12, 2);
            $table->date('tgl_transaksi');
            $table->date('tgl_checkin');
            $table->date('tgl_checkout');
            $table->enum('is_paid', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->text('midtrans_response')->nullable();
            $table->timestamps();

            $table->foreign('id_tamu')->references('id_tamu')->on('tb_tamu');
            $table->foreign('id_kamar')->references('id_kamar')->on('tb_kamar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
        Schema::dropIfExists('tb_tamu');
    }
};
