<?php

namespace App\Http\Controllers;

use App\Services\YatraPlannerService;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YatraPlannerController extends Controller
{
    protected YatraPlannerService $plannerService;

    public function __construct(YatraPlannerService $plannerService)
    {
        $this->plannerService = $plannerService;
    }

    public function index()
    {
        $user = Auth::user();
        $savedItineraries = $user ? Itinerary::where('user_id', $user->id)->with('items')->get() : collect();
        return view('dashboard.planner', compact('savedItineraries'));
    }

    public function plan(Request $request)
    {
        $validated = $request->validate([
            'circuit' => 'required|string',
            'start_date' => 'required|date',
            'duration_days' => 'required|integer|min:1|max:7',
            'group_size' => 'required|integer|min:1|max:30',
            'travel_mode' => 'required|string|in:train,bus,flight,own_vehicle',
            'accessibility' => 'nullable|string',
            'budget' => 'nullable|numeric|min:500',
        ]);

        $generated = $this->plannerService->generate($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'itinerary' => $generated,
            ]);
        }

        return view('dashboard.itinerary-view', [
            'itineraryData' => $generated,
            'isSaved' => false
        ]);
    }

    public function save(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Please sign in to save your pilgrimage itinerary.'], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'destination_circuit' => 'required|string',
            'start_date' => 'required|date',
            'duration_days' => 'required|integer',
            'group_size' => 'required|integer',
            'estimated_budget_inr' => 'required|numeric',
            'travel_mode' => 'required|string',
            'accessibility_needs' => 'nullable|string',
            'items' => 'required|array',
        ]);

        $itinerary = Itinerary::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'destination_circuit' => $validated['destination_circuit'],
            'start_date' => $validated['start_date'],
            'duration_days' => $validated['duration_days'],
            'group_size' => $validated['group_size'],
            'estimated_budget_inr' => $validated['estimated_budget_inr'],
            'travel_mode' => $validated['travel_mode'],
            'accessibility_needs' => $validated['accessibility_needs'] ?? null,
            'is_public' => true,
        ]);

        foreach ($validated['items'] as $item) {
            ItineraryItem::create([
                'itinerary_id' => $itinerary->id,
                'day_number' => $item['day_number'] ?? 1,
                'time_slot' => $item['time_slot'] ?? 'Morning',
                'activity_title' => $item['activity_title'] ?? 'Pilgrim Activity',
                'activity_description' => $item['activity_description'] ?? null,
                'location' => $item['location'] ?? $validated['destination_circuit'],
                'estimated_cost' => $item['estimated_cost'] ?? 0,
                'tips' => $item['tips'] ?? null,
            ]);
        }

        AuditLog::log('SAVE_ITINERARY', $user->id, $user->email, 'Itinerary', $itinerary->id, "Saved itinerary: {$itinerary->title}");

        return response()->json([
            'success' => true,
            'message' => 'Itinerary saved successfully to your pilgrimage dashboard!',
            'itinerary_id' => $itinerary->id,
            'redirect_url' => route('planner.show', $itinerary->id)
        ]);
    }

    public function show($id)
    {
        $itinerary = Itinerary::with('items', 'user')->findOrFail($id);
        return view('dashboard.itinerary-view', [
            'itinerary' => $itinerary,
            'isSaved' => true
        ]);
    }
}
