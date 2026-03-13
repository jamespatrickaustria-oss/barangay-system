@extends('layouts.app')

@section('title', 'Edit Resident')

@section('content')
<style>
    :root {
        --blue: #1a6fcc;
        --green: #3a8a3f;
        --blue-light: #daf0fa;
        --surface: #f0f9ff;
        --text: #0d1b2a;
        --text-muted: #5a7a9a;
        --border: #c8e4f8;
    }

    .back-link {
        color: var(--blue);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 20px;
        display: inline-block;
        transition: opacity 0.2s;
    }

    .back-link:hover {
        opacity: 0.8;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        max-width: 720px;
        margin: 0 auto;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
    }

    .form-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 4px 0;
    }

    .form-subtitle {
        font-size: 14px;
        color: var(--text-muted);
        margin: 0 0 20px 0;
    }

    .status-info {
        display: flex;
        gap: 16px;
        background: #f0f9ff;
        border-left: 4px solid var(--blue);
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 28px;
        font-size: 13px;
    }

    .status-item {
        display: flex;
        flex-direction: column;
    }

    .status-label {
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .status-value {
        color: var(--text);
        font-weight: 600;
    }

    .status-pending {
        color: #f59e0b;
    }

    .status-approved {
        color: var(--green);
    }

    .status-rejected {
        color: #dc3545;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-grid .full {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 14px 16px;
        width: 100%;
        box-sizing: border-box;
        font-size: 15px;
        font-family: inherit;
        color: var(--text);
        background: white;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 4px rgba(26, 111, 204, 0.1);
    }

    .form-group select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235a7a9a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 20px;
        padding-right: 40px;
    }

    .form-group input:disabled {
        background: #f8f9fa;
        color: var(--text-muted);
        cursor: not-allowed;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .submit-btn {
        flex: 1;
        background: linear-gradient(135deg, #1a6fcc, #3a8a3f);
        border: none;
        border-radius: 12px;
        padding: 15px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s;
    }

    .submit-btn:hover {
        opacity: 0.92;
    }

    .submit-btn:active {
        transform: scale(0.99);
    }

    .cancel-btn {
        flex: 1;
        background: white;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 15px;
        color: var(--text);
        font-size: 16px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s;
    }

    .cancel-btn:hover {
        background: #f8f9fa;
        border-color: var(--blue);
        color: var(--blue);
    }

    .read-only-field {
        background: #f8f9fa;
        border-color: var(--border);
    }
</style>

<a href="{{ route('official.residents.index') }}" class="back-link">← Back to Residents</a>

<div class="form-card">
    <h1 class="form-title">Edit Resident Profile</h1>
    <p class="form-subtitle">Update resident information</p>

    <div class="status-info">
        <div class="status-item">
            <span class="status-label">Account Status:</span>
            <span class="status-value status-{{ $resident->status }}">
                @if($resident->status === 'pending')
                    ⏳ Pending Review
                @elseif($resident->status === 'approved')
                    ✓ Approved
                @elseif($resident->status === 'rejected')
                    ✗ Rejected
                @endif
            </span>
        </div>
        <div class="status-item">
            <span class="status-label">Registered:</span>
            <span class="status-value">{{ $resident->created_at->format('M d, Y') }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('official.residents.update', $resident->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div>
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name"
                        value="{{ old('first_name', $resident->first_name) }}"
                        placeholder="Juan"
                        required
                    >
                    @error('first_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <input 
                        type="text" 
                        id="middle_name" 
                        name="middle_name"
                        value="{{ old('middle_name', $resident->middle_name) }}"
                        placeholder="Meow"
                    >
                    @error('middle_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="surname">Surname *</label>
                    <input 
                        type="text" 
                        id="surname" 
                        name="surname"
                        value="{{ old('surname', $resident->surname) }}"
                        placeholder="Cruz"
                        required
                    >
                    @error('surname')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="email">Email Address (Read-only)</label>
                    <input 
                        type="email" 
                        id="email" 
                        class="read-only-field"
                        value="{{ $resident->email }}"
                        disabled
                    >
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone"
                        value="{{ old('phone', $resident->phone) }}"
                        placeholder="+63 9XX XXX XXXX"
                        pattern="[0-9\+\-\(\)\s\.]*"
                        inputmode="numeric"
                    >
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="father_name">Father</label>
                    <input
                        type="text"
                        id="father_name"
                        name="father_name"
                        value="{{ old('father_name', $resident->father_name) }}"
                        placeholder="Enter full name"
                    >
                    @error('father_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="mother_name">Mother</label>
                    <input
                        type="text"
                        id="mother_name"
                        name="mother_name"
                        value="{{ old('mother_name', $resident->mother_name) }}"
                        placeholder="Enter full name"
                    >
                    @error('mother_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="house_no">House No#</label>
                    <input
                        type="text"
                        id="house_no"
                        name="house_no"
                        value="{{ old('house_no', $resident->house_no) }}"
                        placeholder="e.g. 123-B"
                    >
                    @error('house_no')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="barangay">Barangay</label>
                    <input
                        type="text"
                        id="barangay"
                        name="barangay"
                        value="{{ old('barangay', $resident->barangay) }}"
                        placeholder="e.g. Barangay San Juan"
                    >
                    @error('barangay')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="municipality_city">Municipality/City</label>
                    <input
                        type="text"
                        id="municipality_city"
                        name="municipality_city"
                        value="{{ old('municipality_city', $resident->municipality_city) }}"
                        placeholder="e.g. General Trias"
                    >
                    @error('municipality_city')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="nationality">Nationality</label>
                    <input
                        type="text"
                        id="nationality"
                        name="nationality"
                        value="{{ old('nationality', $resident->nationality) }}"
                        placeholder="e.g. Filipino"
                    >
                    @error('nationality')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="full">
                <div class="form-group">
                    <label for="profile_photo">Update Personal Photo</label>
                    <input
                        type="file"
                        id="profile_photo"
                        name="profile_photo"
                        accept=".jpg,.jpeg,.png"
                    >
                    <small style="color: var(--text-muted); font-size: 12px;">Optional. Accepted formats: JPG, JPEG, PNG. Max 5MB.</small>
                    @error('profile_photo')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="full">
                <div class="form-group">
                    <label for="address">Address</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address"
                        value="{{ old('address', $resident->address) }}"
                        placeholder="Street address and Barangay name"
                    >
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input 
                        type="date" 
                        id="birthdate" 
                        name="birthdate"
                        value="{{ old('birthdate', optional($resident->birthdate)->format('Y-m-d')) }}"
                    >
                    @error('birthdate')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="male" @selected(old('gender', $resident->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $resident->gender) === 'female')>Female</option>
                        <option value="other" @selected(old('gender', $resident->gender) === 'other')>Other</option>
                    </select>
                    @error('gender')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="marital_status">Civil Status</label>
                    <select id="marital_status" name="marital_status">
                        <option value="">Select Civil Status</option>
                        <option value="single" @selected(old('marital_status', $resident->marital_status) === 'single')>Single</option>
                        <option value="married" @selected(old('marital_status', $resident->marital_status) === 'married')>Married</option>
                        <option value="divorced" @selected(old('marital_status', $resident->marital_status) === 'divorced')>Divorced</option>
                        <option value="widowed" @selected(old('marital_status', $resident->marital_status) === 'widowed')>Widowed</option>
                        <option value="separated" @selected(old('marital_status', $resident->marital_status) === 'separated')>Separated</option>
                    </select>
                    @error('marital_status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="submit-btn">✓ Save Changes</button>
            <a href="{{ route('official.residents.index') }}" class="cancel-btn">Cancel</a>
        </div>
    </form>
</div>

@endsection
