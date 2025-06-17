<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;

class TestLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:test-login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin login credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Admin Login Credentials...');

        // Test admin user
        $admin = Petugas::where('username', 'admin')->first();
        if ($admin) {
            $this->info("✅ Admin user found:");
            $this->info("   Username: {$admin->username}");
            $this->info("   Email: {$admin->email}");
            $this->info("   Role: {$admin->roles}");

            // Test password
            if (Hash::check('admin123', $admin->password)) {
                $this->info("✅ Password 'admin123' is correct");
            } else {
                $this->error("❌ Password 'admin123' is incorrect");
            }
        } else {
            $this->error("❌ Admin user not found");
        }

        $this->info('');

        // Test staff user
        $staff = Petugas::where('username', 'staff')->first();
        if ($staff) {
            $this->info("✅ Staff user found:");
            $this->info("   Username: {$staff->username}");
            $this->info("   Email: {$staff->email}");
            $this->info("   Role: {$staff->roles}");

            // Test password
            if (Hash::check('staff123', $staff->password)) {
                $this->info("✅ Password 'staff123' is correct");
            } else {
                $this->error("❌ Password 'staff123' is incorrect");
            }
        } else {
            $this->error("❌ Staff user not found");
        }

        $this->info('');
        $this->info('Login credentials:');
        $this->info('Admin - Username: admin, Password: admin123');
        $this->info('Staff - Username: staff, Password: staff123');

        return 0;
    }
}
