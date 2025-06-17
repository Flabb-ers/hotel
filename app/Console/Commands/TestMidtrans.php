<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MidtransService;

class TestMidtrans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Midtrans configuration and connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Midtrans Configuration...');

        // Check configuration
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');

        $this->info("Server Key: " . ($serverKey ? 'Set' : 'Not Set'));
        $this->info("Client Key: " . ($clientKey ? 'Set' : 'Not Set'));
        $this->info("Environment: " . ($isProduction ? 'Production' : 'Sandbox'));

        if (!$serverKey || !$clientKey) {
            $this->error('Midtrans keys are not configured properly!');
            $this->info('Please set MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in your .env file');
            return 1;
        }

        // Test creating a simple transaction
        try {
            $midtransService = new MidtransService();

            $testData = [
                'transaction_details' => [
                    'order_id' => 'TEST-' . time(),
                    'gross_amount' => 100000,
                ],
                'customer_details' => [
                    'first_name' => 'Test User',
                    'email' => 'test@example.com',
                    'phone' => '08123456789',
                ],
                'item_details' => [
                    [
                        'id' => 'TEST-ITEM',
                        'price' => 100000,
                        'quantity' => 1,
                        'name' => 'Test Item',
                    ]
                ],
            ];

            $result = $midtransService->createTransaction($testData);

            if ($result['success']) {
                $this->info('✅ Midtrans connection successful!');
                $this->info('Snap Token generated: ' . substr($result['snap_token'], 0, 20) . '...');
            } else {
                $this->error('❌ Midtrans connection failed: ' . $result['message']);
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Error testing Midtrans: ' . $e->getMessage());
            return 1;
        }

        $this->info('🎉 All tests passed!');
        return 0;
    }
}
