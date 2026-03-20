<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Show the page with the list of contacts
    public function index()
    {
        $contacts = Contact::latest()->get();
        return view('contacts', compact('contacts'));
    }

    // Add a new contact
    public function add(Request $request)
    {
        $request->validate([
            // Must be 11 digits, but treat as string so leading 0 is preserved
            'contact' => 'required|string|regex:/^[0-9]{11}$/',
        ]);

        // Store exactly what the user inputs as string
        Contact::create([
            'value' => $request->contact,
        ]);

        return back()->with('success', 'Contact Number Added!');
    }

    // Verify a contact
    public function verify(Request $request)
    {
        $request->validate([
            'contact' => 'required|string|regex:/^[0-9]{11}$/',
        ]);

        $contact = $request->contact;

        // Search for exact match in database
        $record = Contact::where('value', $contact)->first();

        if ($record) {
            // Format the message with ID and number
            $message = "✓ Verified! #{$record->id} - {$record->value}";
            return back()->with('verified', $message);
        } else {
            return back()->with('error', 'No records found.');
        }
    }
}