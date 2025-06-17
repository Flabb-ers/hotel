<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tamu;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestTamuLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tamu:test-login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test tamu login system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Tamu Authentication System...');

        // Create test tamu if not exists
        $tamu = Tamu::where('email', 'test@tamu.com')->first();

        if (!$tamu) {
            $tamu = Tamu::create([
                'nama_lengkap' => 'Test Tamu',
                'email' => 'test@tamu.com',
                'password' => Hash::make('password123'),
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
                'tanda_pengenal' => '1990-01-01',
            ]);
            $this->info('✅ Test tamu created');
        } else {
            $this->info('✅ Test tamu already exists');
        }

        // Test authentication
        $this->info('Testing authentication...');

        // Test login
        if (Auth::guard('tamu')->attempt(['email' => 'test@tamu.com', 'password' => 'password123'])) {
            $this->info('✅ Login test successful');
            $this->info('   Authenticated user: ' . Auth::guard('tamu')->user()->nama_lengkap);

            // Test logout
            Auth::guard('tamu')->logout();
            $this->info('✅ Logout test successful');
        } else {
            $this->error('❌ Login test failed');
        }

        $this->info('');
        $this->info('Test credentials:');
        $this->info('Email: test@tamu.com');
        $this->info('Password: password123');

        return 0;
    }
}
