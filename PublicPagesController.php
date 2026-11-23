<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\MedicalCentre;
use App\Models\Accommodation;
use App\Models\Announcement;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\LostFoundReport;
use Illuminate\Http\Request;

class PublicPagesController extends Controller
{
    public function home()
    {
        $facilities = Facility::where('is_verified', true)->limit(6)->get();
        $medicalCentres = MedicalCentre::where('is_24x7', true)->limit(4)->get();
        $accommodations = Accommodation::where('is_verified', true)->orderBy('rating', 'desc')->limit(4)->get();
        $announcements = Announcement::where('is_active', true)->orderBy('published_at', 'desc')->limit(3)->get();
        $faqs = Faq::where('is_active', true)->orderBy('display_order')->limit(5)->get();
        $feedbacks = Feedback::where('is_moderated', true)->where('rating', '>=', 4)->limit(3)->get();

        return view('pages.home', compact(
            'facilities',
            'medicalCentres',
            'accommodations',
            'announcements',
            'faqs',
            'feedbacks'
        ));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function yatraGuide()
    {
        return view('pages.yatra-guide');
    }

    public function routes(Request $request)
    {
        $circuit = $request->query('circuit', 'Vaishno Devi');
        $facilities = Facility::where('circuit', $circuit)->get();
        $medicalCentres = MedicalCentre::where('circuit', $circuit)->get();
        return view('pages.routes', compact('circuit', 'facilities', 'medicalCentres'));
    }

    public function accommodation(Request $request)
    {
        $query = Accommodation::query()->where('is_verified', true);
        if ($circuit = $request->query('circuit')) {
            $query->where('circuit', $circuit);
        }
        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }
        $accommodations = $query->orderBy('rating', 'desc')->paginate(9);

        return view('pages.accommodation', compact('accommodations'));
    }

    public function medical(Request $request)
    {
        $query = MedicalCentre::query();
        if ($circuit = $request->query('circuit')) {
            $query->where('circuit', $circuit);
        }
        if ($type = $request->query('centre_type')) {
            $query->where('centre_type', $type);
        }
        $medicalCentres = $query->paginate(9);

        return view('pages.medical', compact('medicalCentres'));
    }

    public function weather(Request $request)
    {
        $circuit = $request->query('circuit', 'Vaishno Devi');
        return view('pages.weather', compact('circuit'));
    }

    public function announcements()
    {
        $announcements = Announcement::where('is_active', true)->orderBy('published_at', 'desc')->paginate(10);
        return view('pages.announcements', compact('announcements'));
    }

    public function faqs(Request $request)
    {
        $category = $request->query('category');
        $query = Faq::where('is_active', true);
        if ($category) {
            $query->where('category', $category);
        }
        $faqs = $query->orderBy('display_order')->get();
        $categories = Faq::where('is_active', true)->pluck('category')->unique();

        return view('pages.faqs', compact('faqs', 'categories', 'category'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:191',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you! Your inquiry has been dispatched to the Yatri Sahayata desk.');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function accessibility()
    {
        return view('pages.accessibility');
    }

    public function checklist()
    {
        return view('pages.checklist');
    }

    public function lostFound(Request $request)
    {
        $query = LostFoundReport::where('is_approved', true);
        if ($circuit = $request->query('circuit')) {
            $query->where('circuit', $circuit);
        }
        if ($type = $request->query('report_type')) {
            $query->where('report_type', $type);
        }
        $reports = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.lost-found', compact('reports'));
    }
}
