<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Show the page with the list of contacts
    public function index()
    {
        $contacts = User::latest()->get();
        return view('contacts', compact('contacts'));
    }

    // Add a new contact
    // public function add(Request $request)
    // {
    //     $request->validate([
            
    //         'contact' => 'required|string|regex:/^[0-9]{11,13}$/|unique:contacts,value',
    //     ]);

    //     $exists = User::where('phone', $request->contact)->exists();

    //     if ($exists) {
    //     $record = User::where('phone', $request->contact)->first();
    //     return back()->with('verified', "✓ Already Registered! #{$record->id} - {$record->phone}");
    //     }
        
    //     User::create([
    //         'phone' => $request->contact,
            
    //     ]);

    //     return back()->with('success', 'Contact Number Added!');
    // }

    // Verify a contact
    public function verify(Request $request)
    {
        $request->validate([
            'contact' => 'required|string|regex:/^\+?[0-9]{10,13}$/',
        ]);

        $contact = $request->contact;

        
        $record = User::where('phone', $contact)->first();

        if ($record) {
            
            $message = "✓ Verified! #{$record->id} - {$record->phone}";
            return back()->with('verified', $message);
        } else {
            return back()->with('error', 'No records found.');
        }
    }
}