<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert default values for tax and discount if not already present
        Setting::create([
            'tax' => 10.00,  // Default tax rate
            'discount' => 5.00,  // Default discount rate
        ]);
    }
}
