<?php 
namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Get the existing settings from the database (assume only one row exists)
        $settings = Setting::first();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tax' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
        ]);

        // Update the settings values
        $settings = Setting::first();  // Assuming there is only one record
        $settings->tax = $request->tax;
        $settings->discount = $request->discount;
        $settings->save();

        // Redirect or return with success message
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
