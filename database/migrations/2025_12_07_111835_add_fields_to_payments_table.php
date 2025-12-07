<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void{
        Schema::table('payments', function (Blueprint $table) {
            $table->string('transaction_reference')->nullable()->after('reference');
            $table->decimal('fees', 10, 2)->nullable()->after('amount');
            $table->decimal('net_amount', 10, 2)->nullable()->after('fees');
            $table->boolean('is_refunded')->default(false)->after('status');
            $table->timestamp('refunded_at')->nullable()->after('is_refunded');
            $table->text('notes')->nullable()->after('metadata');
            
            // Ajouter des indexes pour les recherches
            $table->index(['status', 'created_at']);
            $table->index(['customer_email', 'created_at']);
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 
                'paid_at', 
                'transaction_reference',
                'fees',
                'net_amount',
                'is_refunded',
                'refunded_at',
                'notes'
            ]);
            
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['customer_email', 'created_at']);
            $table->dropIndex('paid_at');
        });
    }
};