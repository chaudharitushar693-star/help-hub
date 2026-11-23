<?php

namespace App\Http\Controllers;

use App\Models\LostFoundReport;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LostFoundController extends Controller
{
    public function submitReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:lost,found',
            'item_name' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'circuit' => 'required|string|max:100',
            'incident_location' => 'required|string|max:255',
            'incident_date' => 'required|date',
            'description' => 'required|string|max:1500',
            'contact_person' => 'required|string|max:120',
            'contact_phone' => 'required|string|max:30',
        ]);

        $user = Auth::user();

        $report = LostFoundReport::create([
            'user_id' => $user ? $user->id : null,
            'report_type' => $validated['report_type'],
            'item_name' => $validated['item_name'],
            'category' => $validated['category'],
            'circuit' => $validated['circuit'],
            'incident_location' => $validated['incident_location'],
            'incident_date' => $validated['incident_date'],
            'description' => $validated['description'],
            'contact_person' => $validated['contact_person'],
            'contact_phone' => $validated['contact_phone'],
            'status' => 'reported',
            'is_approved' => true,
        ]);

        AuditLog::log('LOST_FOUND_REPORTED', $user ? $user->id : null, $user ? $user->email : null, 'LostFoundReport', $report->id, "Item: {$report->item_name} ({$report->report_type})");

        return back()->with('success', 'Report recorded successfully and displayed in Lost & Found board.');
    }
}
