@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<style>
    :root {
        --blue: #1a6fcc;
        --surface: #f0f9ff;
        --text: #0d1b2a;
        --text-muted: #5a7a9a;
        --border: #c8e4f8;
    }

    .details-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
        overflow: hidden;
    }

    .details-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        background: var(--surface);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .details-title {
        margin: 0;
        font-size: 18px;
        color: var(--text);
        font-weight: 700;
    }

    .back-link {
        color: var(--blue);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }

    .details-body {
        padding: 24px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px 24px;
    }

    .field-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .field-value {
        font-size: 15px;
        color: var(--text);
        word-break: break-word;
    }

    @media (max-width: 768px) {
        .details-body {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-------------------------------------------
    USER MODAL VIEW
----------------------------------------- -->
<div class="details-card">
    <div class="details-header">
        <h1 class="details-title">{{ $user->getFullName() }}</h1>
        <a href="{{ route('admin.users.index') }}" class="back-link">Back to User List</a>
    </div>

    <div class="details-body">
        <!-- <div>
            <div class="field-label">ID</div>
            <div class="field-value">{{ $user->id }}</div>
        </div> -->

        <div>
            <div class="field-label">Name:</div>
            <div class="field-value">{{ $user->getFullName() }}</div>
        </div>

        <div>
        </div>
        <div>
            
            <div class="field-label">Account Type</div>
            <div class="field-value">
                @if($user->role === 'admin')
                    Admin
                @elseif($user->role === 'official')
                    Brgy Official
                @else
                    Resident
                @endif
            </div>
        </div>

        <div>
            <div class="field-label">Status</div>
            <div class="field-value">{{ ucfirst($user->status) }}</div>
        </div>

        <div>
            <div class="field-label">Email</div>
            <div class="field-value">{{ $user->email }}</div>
        </div>

        <div>
            <div class="field-label">Phone</div>
            <div class="field-value">{{ $user->phone ?? 'N/A' }}</div>
        </div>


        <div>
            <div class="field-label">House No#</div>
            <div class="field-value">{{ $user->house_no ?? 'N/A' }}</div>
        </div>
        <div>
            <div class="field-label">Barangay</div>
            <div class="field-value">{{ $user->barangay ?? 'N/A' }}</div>
        </div>

        <div>
            <div class="field-label">Municipality/City</div>
            <div class="field-value">{{ $user->municipality_city ?? 'N/A' }}</div>
        </div>
        <div>
            <div class="field-label">Nationality</div>
            <div class="field-value">{{ $user->nationality ?? 'N/A' }}</div>
        </div>

        <div>
            <div class="field-label">Father's Name</div>
            <div class="field-value">{{ $user->father_name ?? 'N/A' }}</div>
        </div>

        <div>
            <div class="field-label">Mother's Name</div>
            <div class="field-value">{{ $user->mother_name ?? 'N/A' }}</div>
        </div>

        <!-- <div>
            <div class="field-label">Address</div>
            <div class="field-value">{{ $user->address ?? 'N/A' }}</div>
        </div> -->

        
        <div>
            <div class="field-label">Date Created</div>
            <div class="field-value">{{ $user->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</div>
        </div>
        <div>
            <div class="field-label">Date Updated</div>
            <div class="field-value">{{ $user->updated_at?->format('M d, Y h:i A') ?? 'N/A' }}</div>
        </div>
    </div>
</div>
@endsection
