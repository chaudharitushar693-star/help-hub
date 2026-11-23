<?php

namespace App\Http\Controllers;

use App\Models\AssistanceRequest;
use App\Models\RequestUpdate;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssistanceController extends Controller
{
    /**
     * Submit an Emergency SOS or standard assistance request.
     */
    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'request_type' => 'required|in:emergency_sos,medical,wheelchair,lost_person,general',
            'priority' => 'nullable|in:critical,high,medium,low',
            'pilgrim_name' => 'required|string|max:120',
            'contact_number' => 'required|string|max:30',
            'alternate_contact' => 'nullable|string|max:30',
            'circuit' => 'required|string|max:100',
            'current_location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'required|string|max:2000',
        ]);

        $isEmergency = ($validated['request_type'] === 'emergency_sos' || $validated['request_type'] === 'medical');
        $priority = $validated['priority'] ?? ($isEmergency ? 'critical' : 'medium');
        $trackingCode = AssistanceRequest::generateTrackingCode();
        $user = Auth::user();

        $assistanceRequest = AssistanceRequest::create([
            'user_id' => $user ? $user->id : null,
            'tracking_code' => $trackingCode,
            'request_type' => $validated['request_type'],
            'priority' => $priority,
            'pilgrim_name' => $validated['pilgrim_name'],
            'contact_number' => $validated['contact_number'],
            'alternate_contact' => $validated['alternate_contact'] ?? null,
            'circuit' => $validated['circuit'],
            'current_location' => $validated['current_location'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'description' => $validated['description'],
            'status' => 'pending',
            'assigned_to' => null,
            'resolution_notes' => null,
        ]);

        // Create initial update log
        RequestUpdate::create([
            'assistance_request_id' => $assistanceRequest->id,
            'updated_by' => 'System',
            'status' => 'pending',
            'comment' => $isEmergency
                ? '🚨 Emergency assistance request logged. Alert displayed on coordinator dashboard.'
                : 'Assistance request registered and placed in queue for verification.',
        ]);

        AuditLog::log('ASSISTANCE_REQUEST_CREATED', $user ? $user->id : null, $user ? $user->email : null, 'AssistanceRequest', $assistanceRequest->id, "Tracking Code: {$trackingCode}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'tracking_code' => $trackingCode,
                'message' => 'Your assistance request has been recorded. Our coordination desk has received the alert.',
                'track_url' => route('assistance.track', $trackingCode)
            ]);
        }

        return redirect()->route('assistance.track', $trackingCode)->with('success', "Assistance request logged successfully with Tracking Code: {$trackingCode}");
    }

    /**
     * Public request status tracking page.
     */
    public function track($trackingCode = null, Request $request)
    {
        $code = $trackingCode ?? $request->query('code');
        $requestData = null;

        if ($code) {
            $requestData = AssistanceRequest::with('updates')
                ->where('tracking_code', trim($code))
                ->first();
        }

        return view('pages.track-request', compact('code', 'requestData'));
    }
}
