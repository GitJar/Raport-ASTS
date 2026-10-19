<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $headmasterName = Setting::get('headmaster_name', 'Rofingul Khusnu Karim, M.Pd.');
        $headmasterNip = Setting::get('headmaster_nip', '198206302005011005');
        $reportDate = Setting::get('report_date', '19 Oktober 2026');

        return view('settings.index', compact('headmasterName', 'headmasterNip', 'reportDate'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'headmaster_name' => 'required|string|max:255',
            'headmaster_nip' => 'required|string|max:255',
            'report_date' => 'required|string|max:255',
        ]);

        Setting::updateOrCreate(['key' => 'headmaster_name'], ['value' => $request->headmaster_name]);
        Setting::updateOrCreate(['key' => 'headmaster_nip'], ['value' => $request->headmaster_nip]);
        Setting::updateOrCreate(['key' => 'report_date'], ['value' => $request->report_date]);

        return back()->with('success', 'Pengaturan Raport Berhasil Diperbarui!');
    }
}