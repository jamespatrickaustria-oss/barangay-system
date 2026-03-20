@extends('layouts.app')

@section('content')

@section('title', 'Verifier')



<link rel="icon" type="image/x-icon" href="{{ asset('images/city_of_general_trias_seal.png') }}">

<style>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    .contacts-wrapper {
        font-family: 'Syne', sans-serif;
        padding: 2rem 1.5rem;
        min-height: 100vh;
        color: #0f0f11;
    }

    .card {
        max-width: 680px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #d0cee8;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 24px rgba(124, 111, 247, 0.07);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #2c5f35; 
        margin-bottom: 1.5rem;
    }

    /* Flash Messages */
    .flash {
        max-width: 680px;
        margin: 0 auto 1rem;
        padding: 0.75rem 1.1rem;
        border-radius: 10px;
        font-size: 0.88rem;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        letter-spacing: 0.04em;
    }

    .flash-success {
        background: #edfaf3;
        color: #1a7a4a;
        border: 1px solid #a8e6c3;
    }

    .flash-info {
        background: #e3f3fa;
        color: #1a6ec7;
        border: 1px solid #a8dff5;;
    }

    .flash-error {
        background: #fff0f0;
        color: #c0392b;
        border: 1px solid #f5b7b1;
    }

    .input-row {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .input-row input[type="text"] {
        flex: 1;
        min-width: 180px;
        padding: 0.8rem 1.1rem;
        background: #f5f4fb;
        border: 1px solid #d0cee8;
        border-radius: 10px;
        color: #0f0f11;
        font-family: 'DM Mono', monospace;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .input-row input[type="text"]:focus {
        border-color: #7c6ff7;
    }

    .input-row input[type="text"]::placeholder {
        color: #9d96c0;
    }


    .btn {
        padding: 0.8rem 1.4rem;
        border: none;
        border-radius: 10px;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        font-size: 0.88rem;
        letter-spacing: 0.06em;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
    }

    .btn:hover { opacity: 0.85; transform: translateY(-1px); }
    .btn:active { transform: translateY(0); }

    .btn-add {
        background: #3a7d44;
        color: #fff;
    }

    .btn-verify {
        background: #1a6ec7;
        color:  #fff;
     
    }

    .divider {
        border: none;
        border-top: 1px solid #e0deee;
        margin: 2rem 0;
    }

    .section-label {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #9d96c0;
        margin-bottom: 1.2rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
        font-size: 0.72rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #9d96c0;
        font-weight: 600;
        padding: 0 0.75rem 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e0deee;
    }

    tbody tr {
        transition: background 0.15s;
    }

    tbody tr:hover {
        background: #f5f4fb;
    }

    tbody td {
        padding: 0.85rem 0.75rem;
        font-size: 0.9rem;
        color: #4a4a60;
        border-bottom: 1px solid #f0eef8;
        font-family: 'DM Mono', monospace;
    }

    tbody td:first-child {
        color: #b0aad4;
        font-size: 0.8rem;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem;
        color: #b0aad4;
        font-size: 0.88rem;
        letter-spacing: 0.04em;
    }
</style>

<div class="contacts-wrapper">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash flash-success">✓ {{ session('success') }}</div>
    @endif

    @if(session('verified'))
        <div class="flash flash-info"> {{ session('verified') }}</div>

        
    @endif

    @if(session('error'))
        <div class="flash flash-error">✕ {{ session('error') }}</div>
    @endif

    <div class="card">

        <p class="card-title">Contact Number Verifier</p>

        <form method="POST">
            @csrf
            
               <div class="input-row">
                   <input type="text" id="contact" name="contact" placeholder="Enter valid number">

                </div>
                <div style="margin: 0 auto; padding-top: 10px;">
                <button class="btn btn-add" formaction="{{ route('contacts.add') }}">Add</button>
                <button class="btn btn-verify" formaction="{{ route('contacts.verify') }}">Verify</button>
                </div>
     
        </form>

        <hr class="divider">

        <p class="section-label">Recently Added</p>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Verified contact Number </th>
                    <!-- <th>Date Added</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $num)
                <tr>
                    <td>#{{ $num->id }}</td>
                    <td>{{ $num->value }}</td>
                    <!-- <td>{{ $num->created_at }}</td> -->
                </tr>
                @empty
                <tr>
                    <td colspan="3">
                        <div class="empty-state">No contacts added yet.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

<script>
    const input = document.getElementById('contact');

    input.addEventListener('input', function() {
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }
    });
</script>

@endsection

