@extends('layouts.app')

@section('title', 'My Profile')

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

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
    }

    .left-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
        height: fit-content;
    }

    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, var(--blue), var(--green));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        color: white;
        font-weight: 800;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 4px 0;
    }

    .role-badge {
        display: inline-block;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .role-badge.official {
        background: var(--blue-light);
        color: var(--blue);
    }

    .divider {
        border: none;
        border-top: 1px solid #e0e0e0;
        margin: 20px 0;
    }

    .change-photo-label {
        display: inline-block;
        border: 2px solid var(--blue);
        color: var(--blue);
        border-radius: 10px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.2s;
        margin-bottom: 12px;
    }

    .change-photo-label:hover {
        background: var(--blue-light);
    }

    .photo-note {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 8px;
    }

    .right-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
    }

    .section-header {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 24px 0;
        padding-left: 12px;
        border-left: 4px solid var(--blue);
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

    .form-group input:readonly {
        background: #f8fafc;
        color: var(--text-muted);
        cursor: not-allowed;
    }

    .readonly-note {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .form-group select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235a7a9a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 20px;
        padding-right: 40px;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 28px;
    }

    .save-btn {
        background: linear-gradient(135deg, #1a6fcc, #3a8a3f);
        border: none;
        border-radius: 10px;
        padding: 12px 28px;
        color: white;
        font-weight: 700;
        font-size: 14px;
        font-family: inherit;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s;
    }

    .save-btn:hover {
        opacity: 0.92;
    }

    .save-btn:active {
        transform: scale(0.99);
    }

    .success-message {
        background: #e8f5e9;
        border-left: 4px solid var(--green);
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #2d6a31;
    }

    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if(session('success'))
    <div class="success-message">
        ✓ {{ session('success') }}
    </div>
@endif
<a href="{{ route('official.dashboard') }}" class="back-link">← Back to Dashboard</a>

<div class="profile-grid">
    <div class="left-card">
        <div class="avatar" id="avatarPreview">
            {{ strtoupper(substr(auth()->user()->getFullName() ?? 'User', 0, 1)) }}
        </div>
        <h2 class="user-name">{{ auth()->user()->getFullName() }}</h2>
        <span class="role-badge official">Barangay Official</span>
        
        <input type="file" id="photoInput" name="profile_photo" accept="image/*" style="display: none" onchange="previewPhoto(this)">
        <hr class="divider">
        
        <label for="photoInput" class="change-photo-label">📷 Change Photo</label>
        <p class="photo-note">JPG or PNG, max 2MB</p>
    </div>

    <div class="right-card">
        <h2 class="section-header">Personal Information</h2>

        <form method="POST" action="{{ route('official.profile.update') }}" enctype="multipart/form-data">
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
                            value="{{ old('first_name', auth()->user()->first_name) }}"
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
                            value="{{ old('middle_name', auth()->user()->middle_name) }}"
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
                            value="{{ old('surname', auth()->user()->surname) }}"
                            required
                        >
                        @error('surname')
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
                            value="{{ old('birthdate', auth()->user()->birthdate) }}"
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
                            <option value="male" @selected(old('gender', auth()->user()->gender) === 'male')>Male</option>
                            <option value="female" @selected(old('gender', auth()->user()->gender) === 'female')>Female</option>
                            <option value="other" @selected(old('gender', auth()->user()->gender) === 'other')>Other</option>
                        </select>
                        @error('gender')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="marital_status">Marital Status</label>
                        <select id="marital_status" name="marital_status">
                            <option value="">Select Marital Status</option>
                            <option value="single" @selected(old('marital_status', auth()->user()->marital_status) === 'single')>Single</option>
                            <option value="married" @selected(old('marital_status', auth()->user()->marital_status) === 'married')>Married</option>
                            <option value="divorced" @selected(old('marital_status', auth()->user()->marital_status) === 'divorced')>Divorced</option>
                            <option value="widowed" @selected(old('marital_status', auth()->user()->marital_status) === 'widowed')>Widowed</option>
                            <option value="separated" @selected(old('marital_status', auth()->user()->marital_status) === 'separated')>Separated</option>
                        </select>
                        @error('marital_status')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone"
                            value="{{ old('phone', auth()->user()->phone) }}"
                            pattern="[0-9\+\-\(\)\s\.]*"
                            inputmode="numeric"
                        >
                        @error('phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            value="{{ auth()->user()->email }}"
                            readonly
                        >
                        <p class="readonly-note">Email address cannot be changed.</p>
                    </div>
                </div>

                <div class="full">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address"
                            value="{{ old('address', auth()->user()->address) }}"
                        >
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                
            </div>

            <div class="form-footer">
                <button type="submit" class="save-btn"> Update Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                preview.style.backgroundImage = 'url(' + e.target.result + ')';
                preview.style.backgroundSize = 'cover';
                preview.style.backgroundPosition = 'center';
                preview.innerHTML = '';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection
