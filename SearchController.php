<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\MedicalCentre;
use App\Models\Accommodation;
use App\Models\Faq;
use App\Models\Announcement;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));
        $category = $request->query('category', 'all');

        $results = [
            'facilities' => collect(),
            'medical' => collect(),
            'accommodations' => collect(),
            'faqs' => collect(),
            'announcements' => collect(),
        ];

        $totalCount = 0;

        if (strlen($q) >= 2) {
            if ($category === 'all' || $category === 'facilities') {
                $results['facilities'] = Facility::where('name', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('circuit', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->limit(8)->get();
                $totalCount += $results['facilities']->count();
            }

            if ($category === 'all' || $category === 'medical') {
                $results['medical'] = MedicalCentre::where('name', 'like', "%{$q}%")
                    ->orWhere('circuit', 'like', "%{$q}%")
                    ->orWhere('centre_type', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->limit(8)->get();
                $totalCount += $results['medical']->count();
            }

            if ($category === 'all' || $category === 'accommodations') {
                $results['accommodations'] = Accommodation::where('name', 'like', "%{$q}%")
                    ->orWhere('circuit', 'like', "%{$q}%")
                    ->orWhere('type', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->limit(8)->get();
                $totalCount += $results['accommodations']->count();
            }

            if ($category === 'all' || $category === 'faqs') {
                $results['faqs'] = Faq::where('question', 'like', "%{$q}%")
                    ->orWhere('answer', 'like', "%{$q}%")
                    ->orWhere('hindi_question', 'like', "%{$q}%")
                    ->limit(6)->get();
                $totalCount += $results['faqs']->count();
            }

            if ($category === 'all' || $category === 'announcements') {
                $results['announcements'] = Announcement::where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%")
                    ->limit(4)->get();
                $totalCount += $results['announcements']->count();
            }
        }

        return view('pages.search', compact('q', 'category', 'results', 'totalCount'));
    }

    public function autocomplete(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = [];

        $facilities = Facility::where('name', 'like', "%{$q}%")->limit(3)->get(['id', 'name', 'circuit', 'category']);
        foreach ($facilities as $f) {
            $suggestions[] = [
                'type' => 'Facility (' . $f->category . ')',
                'title' => $f->name,
                'circuit' => $f->circuit,
                'url' => route('routes') . '?circuit=' . urlencode($f->circuit)
            ];
        }

        $medical = MedicalCentre::where('name', 'like', "%{$q}%")->limit(3)->get(['id', 'name', 'circuit']);
        foreach ($medical as $m) {
            $suggestions[] = [
                'type' => 'Medical Centre',
                'title' => $m->name,
                'circuit' => $m->circuit,
                'url' => route('medical') . '?circuit=' . urlencode($m->circuit)
            ];
        }

        $acc = Accommodation::where('name', 'like', "%{$q}%")->limit(3)->get(['id', 'name', 'circuit']);
        foreach ($acc as $a) {
            $suggestions[] = [
                'type' => 'Accommodation',
                'title' => $a->name,
                'circuit' => $a->circuit,
                'url' => route('accommodation') . '?circuit=' . urlencode($a->circuit)
            ];
        }

        return response()->json(['suggestions' => $suggestions]);
    }
}
