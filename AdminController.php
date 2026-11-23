<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\AssistanceRequest;
use App\Models\RequestUpdate;
use App\Models\MedicalCentre;
use App\Models\Accommodation;
use App\Models\Announcement;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_requests' => AssistanceRequest::count(),
            'pending_requests' => AssistanceRequest::where('status', 'pending')->count(),
            'resolved_requests' => AssistanceRequest::where('status', 'resolved')->count(),
            'total_medical' => MedicalCentre::count(),
            'total_accommodations' => Accommodation::count(),
            'total_feedback' => Feedback::count(),
        ];

        $recentRequests = AssistanceRequest::orderBy('created_at', 'desc')->limit(6)->get();
        $recentAuditLogs = AuditLog::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.index', compact('stats', 'recentRequests', 'recentAuditLogs'));
    }

    public function users(Request $request)
    {
        $query = User::with('role');
        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        $users = $query->orderBy('id', 'asc')->paginate(15);
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function toggleUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own administrative account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        AuditLog::log('ADMIN_USER_TOGGLE', Auth::id(), Auth::user()->email, 'User', $user->id, "Toggled user active state to: {$user->is_active}");

        return back()->with('success', "User account {$user->name} status updated.");
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->role_id = $validated['role_id'];
        $user->save();

        AuditLog::log('ADMIN_USER_ROLE_CHANGE', Auth::id(), Auth::user()->email, 'User', $user->id, "Changed role to ID {$validated['role_id']}");

        return back()->with('success', "Role for {$user->name} successfully updated.");
    }

    public function requests(Request $request)
    {
        $query = AssistanceRequest::with('updates');
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($priority = $request->query('priority')) {
            $query->where('priority', $priority);
        }
        if ($circuit = $request->query('circuit')) {
            $query->where('circuit', $circuit);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.requests', compact('requests'));
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $assistanceRequest = AssistanceRequest::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,acknowledged,in_progress,resolved,cancelled',
            'assigned_to' => 'nullable|string|max:120',
            'comment' => 'required|string|max:1000',
        ]);

        $oldStatus = $assistanceRequest->status;
        $assistanceRequest->status = $validated['status'];
        if (!empty($validated['assigned_to'])) {
            $assistanceRequest->assigned_to = $validated['assigned_to'];
        }
        if ($validated['status'] === 'resolved') {
            $assistanceRequest->resolution_notes = $validated['comment'];
        }
        $assistanceRequest->save();

        RequestUpdate::create([
            'assistance_request_id' => $assistanceRequest->id,
            'updated_by' => Auth::user()->name,
            'status' => $validated['status'],
            'comment' => $validated['comment'],
        ]);

        AuditLog::log('ADMIN_REQUEST_UPDATE', Auth::id(), Auth::user()->email, 'AssistanceRequest', $assistanceRequest->id, "Status changed from {$oldStatus} to {$validated['status']}");

        return back()->with('success', "Assistance request {$assistanceRequest->tracking_code} updated successfully.");
    }

    public function medical()
    {
        $medicalCentres = MedicalCentre::orderBy('circuit')->paginate(15);
        return view('admin.medical', compact('medicalCentres'));
    }

    public function storeMedical(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'centre_type' => 'required|string|max:100',
            'circuit' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'helpline_number' => 'required|string|max:50',
            'doctor_in_charge' => 'nullable|string|max:120',
            'available_facilities' => 'nullable|string',
            'is_24x7' => 'boolean',
        ]);

        $medical = MedicalCentre::create($validated);
        AuditLog::log('ADMIN_CREATE_MEDICAL', Auth::id(), Auth::user()->email, 'MedicalCentre', $medical->id, "Created medical post: {$medical->name}");

        return back()->with('success', 'Medical Centre added to verified directory.');
    }

    public function accommodations()
    {
        $accommodations = Accommodation::orderBy('circuit')->paginate(15);
        return view('admin.accommodations', compact('accommodations'));
    }

    public function announcements()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'circuit' => 'required|string|max:100',
            'priority' => 'required|in:alert,advisory,info',
            'content' => 'required|string',
        ]);

        Announcement::create($validated);
        return back()->with('success', 'Live announcement published to pilgrim portal.');
    }

    public function faqs()
    {
        $faqs = Faq::orderBy('display_order')->paginate(20);
        return view('admin.faqs', compact('faqs'));
    }

    public function feedback()
    {
        $feedbacks = Feedback::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.feedback', compact('feedbacks'));
    }

    public function auditLogs()
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.audit-logs', compact('logs'));
    }

    public function exportCsv(Request $request, $type)
    {
        $filename = "yatri_sahayata_{$type}_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($type) {
            $handle = fopen('php://output', 'w');
            if ($type === 'requests') {
                fputcsv($handle, ['ID', 'Tracking Code', 'Type', 'Priority', 'Pilgrim Name', 'Contact', 'Circuit', 'Location', 'Status', 'Created At']);
                foreach (AssistanceRequest::cursor() as $req) {
                    fputcsv($handle, [$req->id, $req->tracking_code, $req->request_type, $req->priority, $req->pilgrim_name, $req->contact_number, $req->circuit, $req->current_location, $req->status, $req->created_at]);
                }
            } elseif ($type === 'users') {
                fputcsv($handle, ['ID', 'Role ID', 'Name', 'Email', 'Phone', 'Active', 'Created At']);
                foreach (User::cursor() as $u) {
                    fputcsv($handle, [$u->id, $u->role_id, $u->name, $u->email, $u->phone, $u->is_active ? 'Yes' : 'No', $u->created_at]);
                }
            } else {
                fputcsv($handle, ['ID', 'Pilgrim', 'Rating', 'Circuit', 'Aspect', 'Comment', 'Date']);
                foreach (Feedback::cursor() as $f) {
                    fputcsv($handle, [$f->id, $f->pilgrim_name, $f->rating, $f->circuit, $f->service_aspect, $f->comment, $f->created_at]);
                }
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
