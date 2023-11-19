<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mail_counts', function (Blueprint $table) {
            $table->id();
            $table->integer('total_count')->default(0)->comment("toplam sayı");
            $table->integer('send_success')->default(0)->comment("gönderilen success sayısı");
            $table->string('job', 50)->comment("ilgili jobjun unique idsi");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_counts');
    }
};
