<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\SavedPlace;
use App\Models\Itinerary;
use App\Models\AssistanceRequest;
use App\Models\Notification;
use App\Models\Feedback;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $savedPlacesCount = SavedPlace::where('user_id', $user->id)->count();
        $itineraries = Itinerary::where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(3)->get();
        $requests = AssistanceRequest::where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(4)->get();
        $notifications = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(5)->get();

        return view('dashboard.index', compact('user', 'savedPlacesCount', 'itineraries', 'requests', 'notifications'));
    }

    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        return view('dashboard.profile', compact('user', 'profile'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:20',
            'preferred_language' => 'required|in:en,hi',
            'emergency_contact_name' => 'nullable|string|max:120',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'accessibility_needs' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'preferred_language' => $validated['preferred_language'],
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'blood_group' => $validated['blood_group'],
                'medical_conditions' => $validated['medical_conditions'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'accessibility_needs' => $validated['accessibility_needs'],
            ]
        );

        AuditLog::log('PROFILE_UPDATE', $user->id, $user->email, 'Profile', $user->id, 'User updated emergency and personal profile.');

        return back()->with('success', 'Profile and emergency preferences updated successfully.');
    }

    public function savedPlaces()
    {
        $user = Auth::user();
        $places = SavedPlace::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(12);
        return view('dashboard.saved-places', compact('places'));
    }

    public function toggleSavePlace(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'place_type' => 'required|string',
            'place_id' => 'required|integer',
            'place_name' => 'required|string',
            'circuit' => 'required|string',
        ]);

        $existing = SavedPlace::where('user_id', $user->id)
            ->where('place_type', $request->place_type)
            ->where('place_id', $request->place_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['saved' => false, 'message' => 'Removed from saved places.']);
        }

        SavedPlace::create([
            'user_id' => $user->id,
            'place_type' => $request->place_type,
            'place_id' => $request->place_id,
            'place_name' => $request->place_name,
            'circuit' => $request->circuit,
        ]);

        return response()->json(['saved' => true, 'message' => 'Saved to your pilgrimage dashboard!']);
    }

    public function requests()
    {
        $user = Auth::user();
        $requests = AssistanceRequest::with('updates')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.requests', compact('requests'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        Notification::where('user_id', $user->id)->where('is_read', false)->update(['is_read' => true]);

        return view('dashboard.notifications', compact('notifications'));
    }

    public function submitFeedback(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'circuit' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'service_aspect' => 'required|string',
            'comment' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => $user ? $user->id : null,
            'pilgrim_name' => $user ? $user->name : ($request->input('pilgrim_name') ?? 'Anonymous Pilgrim'),
            'email' => $user ? $user->email : $request->input('email'),
            'circuit' => $validated['circuit'],
            'rating' => $validated['rating'],
            'service_aspect' => $validated['service_aspect'],
            'comment' => $validated['comment'],
            'is_moderated' => true,
        ]);

        return back()->with('success', 'Har Har Mahadev! Thank you for your valuable feedback.');
    }
}
