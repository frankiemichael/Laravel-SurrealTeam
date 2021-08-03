<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;

class AlertController extends Controller
{
    public function edit($id)
    {
        $alert = Alert::where('id', $id)->first();
        return view('alerts.edit', compact('alert'));
    }

    public function destroy($alert)
    {
        Alert::where('id', $alert)->delete();
        return redirect()->route('dashboard')->with('success', 'Alert has been deleted.');
    }
}
