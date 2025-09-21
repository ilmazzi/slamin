<?php

namespace App\Http\Controllers;

use App\Models\Help;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Display help page
     */
    public function help()
    {
        $helps = Help::ofType('help')
            ->active()
            ->ordered()
            ->get();

        return view('help.index', compact('helps'));
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $faqs = Help::ofType('faq')
            ->active()
            ->ordered()
            ->get();

        return view('help.faq', compact('faqs'));
    }

    /**
     * Display specific help item
     */
    public function show(Help $help)
    {
        if (!$help->is_active) {
            abort(404);
        }

        return view('help.show', compact('help'));
    }
}
