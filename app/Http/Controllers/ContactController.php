<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // display the contact form
    public function index()
    {
        return view('contact');
    }

    // process the form submission
    public function store(Request $request)
    {
        // validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Inquiry::create($request->all());

        return redirect()->back()->with('success', 'Thank you for reaching out! Your message has been sent successfully. We will get back to you soon.');
    }
}