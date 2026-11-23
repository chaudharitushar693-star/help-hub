<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'language' => 'nullable|string|in:en,hi',
            'circuit' => 'nullable|string|max:100',
        ]);

        $message = $request->input('message');
        $language = $request->input('language', 'en');
        $circuit = $request->input('circuit');

        $result = $this->aiService->ask($message, $language, $circuit);

        return response()->json([
            'success' => true,
            'reply' => $result['reply'],
            'source' => $result['source'] ?? 'yatri_knowledge_base',
            'action' => $result['action'] ?? null,
        ]);
    }
}
