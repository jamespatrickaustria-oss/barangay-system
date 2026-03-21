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
            
            'contact' => 'required|string|regex:/^[0-9]{11}$/|unique:contacts,value',
        ]);

        $exists = Contact::where('value', $request->contact)->exists();

        if ($exists) {
        $record = Contact::where('value', $request->contact)->first();
        return back()->with('verified', "✓ Already Registered! #{$record->id} - {$record->value}");
        }
        
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

        
        $record = Contact::where('value', $contact)->first();

        if ($record) {
            
            $message = "✓ Verified! #{$record->id} - {$record->value}";
            return back()->with('verified', $message);
        } else {
            return back()->with('error', 'No records found.');
        }
    }
}