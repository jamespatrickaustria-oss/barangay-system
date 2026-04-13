@extends('layouts.resident')

@section('title', 'My Profile')

@section('content')
@php
    $profilePhotoUrl = auth()->user()->profile_photo_url;
    $canEditResidentDetails = in_array(auth()->user()->role, ['admin', 'official'], true);
@endphp
<style>
    .page-header {
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 8px;
    }

    .page-subtitle {
        font-size: 16px;
        color: var(--gray-600);
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
    }

    .left-card {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        height: fit-content;
    }

    .profile-identity {
        display: flex;
        align-items: center;
        gap: 14px;
        text-align: left;
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 12px;
    }

    .profile-identity-meta {
        min-width: 0;
    }

    .avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        font-weight: 800;
        box-shadow: var(--shadow);
        flex-shrink: 0;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0 0 4px 0;
        line-height: 1.3;
        word-break: break-word;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: var(--secondary-light);
        color: var(--secondary-dark);
    }

    .divider {
        border: none;
        border-top: 1px solid var(--gray-200);
        margin: 24px 0;
    }

    .photo-note {
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 16px;
        line-height: 1.6;
    }

    .right-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border);
    }

    .section-header {
        font-size: 26px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 24px 0;
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
        margin-bottom: 4px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--text-light);
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
        box-shadow: 0 0 0 4px rgba(26, 110, 199, 0.1);
    }

    .form-group input:readonly {
        background: var(--gray-100);
        color: var(--gray-600);
        cursor: not-allowed;
    }

    .form-group input:disabled,
    .form-group select:disabled {
        background: var(--gray-100);
        color: var(--gray-600);
        cursor: not-allowed;
        opacity: 1;
    }

    .readonly-note {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 6px;
    }

    .form-group select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 20px;
        padding-right: 40px;
    }

    .error-message {
        color: #dc2626;
        font-size: 12px;
        margin-top: 6px;
    }

    .form-footer {
        display: flex;
        justify-content: stretch;
        margin-top: 32px;
    }

    .save-btn {
        width: 100%;
        background: linear-gradient(135deg, #1a6ec7, #3a7d44);
        border: none;
        border-radius: 12px;
        padding: 15px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        font-family: inherit;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s;
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .save-btn:hover {
        opacity: 0.92;
    }

    .save-btn:active {
        transform: scale(0.99);
    }

    .save-btn:disabled {
        background: var(--gray-300);
        color: var(--gray-600);
        cursor: not-allowed;
        transform: none;
        opacity: 1;
    }

    .success-message {
        background: var(--secondary-light);
        border: 1px solid var(--secondary);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        font-size: 14px;
        font-weight: 500;
        color: var(--secondary-dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .left-card {
            padding: 20px;
        }

        .right-card {
            padding: 28px 20px;
            border-radius: 16px;
        }

        .section-header {
            font-size: 20px;
        }

        .profile-identity {
            padding: 10px;
        }

        .avatar {
            width: 52px;
            height: 52px;
        }

        .user-name {
            font-size: 14px;
        }
    }

    @media (max-width: 768px) {
        .page-title    { font-size: 22px; }
        .page-subtitle { font-size: 14px; }
        .page-header   { margin-bottom: 20px; }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .right-card {
            padding: 20px 16px;
        }

        .section-header { font-size: 18px; margin-bottom: 18px; }

        .form-group input,
        .form-group select {
            padding: 12px 14px;
            font-size: 14px;
        }

        .save-btn {
            font-size: 14px;
            padding: 13px;
        }
    }

    @media (max-width: 480px) {
        .page-title { font-size: 19px; }

        .left-card  { padding: 16px; }

        .right-card { padding: 16px 14px; }

        .form-group label { font-size: 11px; }

        .form-group input,
        .form-group select {
            padding: 11px 12px;
            border-radius: 10px;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">ðŸ‘¤ My Profile</h1>
    <p class="page-subtitle">Manage your personal information and account settings</p>
</div>

@if(session('success'))
    <div class="success-message">
        <span style="font-size: 20px;">âœ“</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="profile-grid">
    <div class="left-card">
        <div class="profile-identity">
            <div class="avatar" id="avatarPreview">
                @if($profilePhotoUrl)
                    <img src="{{ $profilePhotoUrl }}" alt="{{ auth()->user()->getFullName() }} profile photo">
                @else
                    {{ strtoupper(substr(auth()->user()->getFullName() ?? 'User', 0, 1)) }}
                @endif
            </div>
            <div class="profile-identity-meta">
                <h2 class="user-name">{{ auth()->user()->getFullName() }}</h2>
                <span class="role-badge">Verified Resident</span>
            </div>
        </div>
        
        <hr class="divider">
        
        <p class="photo-note">Resident photo updates are managed by authorized Admin or Official staff.</p>
    </div>

    <div class="right-card">
        <h2 class="section-header">Personal Information</h2>

        @unless($canEditResidentDetails)
            <div class="success-message" style="background: var(--gray-50); border-color: var(--gray-200); color: var(--gray-700);">
                <span style="font-size: 20px;">i</span>
                <span>Resident details are view-only for resident accounts. Admin and Official users can edit these fields from the resident management pages.</span>
            </div>
        @endunless

        <form method="POST" action="{{ route('resident.profile.update') }}" id="residentProfileForm">
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
                            @disabled(! $canEditResidentDetails)
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
                            @disabled(! $canEditResidentDetails)
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
                            @disabled(! $canEditResidentDetails)
                        >
                        @error('surname')
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
                            @disabled(! $canEditResidentDetails)
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
                            value="{{ old('father_name', auth()->user()->father_name) }}"
                            placeholder="Enter full name"
                            @disabled(! $canEditResidentDetails)
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
                            value="{{ old('mother_name', auth()->user()->mother_name) }}"
                            placeholder="Enter full name"
                            @disabled(! $canEditResidentDetails)
                        >
                        @error('mother_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="full">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            value="{{ auth()->user()->email }}"
                            readonly
                            @disabled(! $canEditResidentDetails)
                        >
                        <p class="readonly-note">ðŸ“§ Email address cannot be changed</p>
                    </div>
                </div>

                <!-- <div class="full">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address"
                            value="{{ old('address', auth()->user()->address) }}"
                            @disabled(! $canEditResidentDetails)
                        >
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div> -->

                <div>
                    <div class="form-group">
                        <label for="house_no">House No#</label>
                        <input
                            type="text"
                            id="house_no"
                            name="house_no"
                            value="{{ old('house_no', auth()->user()->house_no) }}"
                            placeholder="e.g. 123-B"
                            @disabled(! $canEditResidentDetails)
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
                            value="{{ old('barangay', auth()->user()->barangay) }}"
                            placeholder="e.g. Barangay San Juan"
                            @disabled(! $canEditResidentDetails)
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
                            value="{{ old('municipality_city', auth()->user()->municipality_city) }}"
                            placeholder="e.g. General Trias"
                            @disabled(! $canEditResidentDetails)
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
                            value="{{ old('nationality', auth()->user()->nationality ?? 'Filipino') }}"
                            placeholder="e.g. Filipino"
                            @disabled(! $canEditResidentDetails)
                        >
                        @error('nationality')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="emergency_contact_name">Emergency Contact Name</label>
                        <input
                            type="text"
                            id="emergency_contact_name"
                            name="emergency_contact_name"
                            value="{{ old('emergency_contact_name', auth()->user()->emergency_contact_name) }}"
                            placeholder="Enter full name"
                            @disabled(! $canEditResidentDetails)
                        >
                        @error('emergency_contact_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="emergency_contact_relationship">Emergency Contact Relationship</label>
                        <input
                            type="text"
                            id="emergency_contact_relationship"
                            name="emergency_contact_relationship"
                            value="{{ old('emergency_contact_relationship', auth()->user()->emergency_contact_relationship) }}"
                            placeholder="e.g. Mother, Father, Spouse, Sibling"
                            @disabled(! $canEditResidentDetails)
                        >
                        @error('emergency_contact_relationship')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="emergency_contact_number">Emergency Contact Number</label>
                            <div style="display:flex;align-items:stretch;border:2px solid var(--gray-200);border-radius:12px;overflow:hidden;background:white;" id="emergency_contact_wrapper">
                                <span style="display:flex;align-items:center;padding:0 14px;background:var(--gray-50);font-weight:700;color:var(--gray-700);border-right:2px solid var(--gray-200);white-space:nowrap;flex-shrink:0;">+63</span>
                                <input
                                    type="hidden"
                                    id="emergency_contact_number"
                                    name="emergency_contact_number"
                                    value="{{ old('emergency_contact_number', auth()->user()->emergency_contact_number) }}"
                                    @disabled(! $canEditResidentDetails)
                                >
                                <input
                                    type="tel"
                                    id="emergency_contact_number_local"
                                    value="{{ preg_replace('/^\\+63\\s*/', '', old('emergency_contact_number', auth()->user()->emergency_contact_number)) }}"
                                    placeholder="9XX XXX XXXX"
                                    maxlength="12"
                                    inputmode="numeric"
                                    pattern="[0-9\s]*"
                                    style="border:none;outline:none;flex:1;min-width:0;"
                                    @disabled(! $canEditResidentDetails)
                                >
                            </div>
                        @error('emergency_contact_number')
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
                            value="{{ old('birthdate', optional(auth()->user()->birthdate)->format('Y-m-d')) }}"
                            @disabled(! $canEditResidentDetails)
                        >
                        @error('birthdate')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" @disabled(! $canEditResidentDetails)>
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
                        <select id="marital_status" name="marital_status" @disabled(! $canEditResidentDetails)>
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
            </div>

            <div class="form-footer">
                @if($canEditResidentDetails)
                    <button type="submit" class="save-btn">ðŸ’¾ Update Profile</button>
                @else
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    const residentProfileForm = document.getElementById('residentProfileForm');

    function assembleResidentEmergencyContactNumber() {
        const localInput = document.getElementById('emergency_contact_number_local');
        const hiddenInput = document.getElementById('emergency_contact_number');

        if (!localInput || !hiddenInput) {
            return;
        }

        const local = (localInput.value || '').trim().replace(/\s/g, '');
        hiddenInput.value = local ? '+63' + local : '';
    }

    if (residentProfileForm) {
        residentProfileForm.addEventListener('submit', assembleResidentEmergencyContactNumber);
    }

    const residentDetailInputs = residentProfileForm
        ? residentProfileForm.querySelectorAll('input, select, textarea, button')
        : [];

    if (!{{ $canEditResidentDetails ? 'true' : 'false' }}) {
        residentDetailInputs.forEach((element) => {
            if (element.tagName === 'BUTTON') {
                element.disabled = true;
                return;
            }

            if (element.tagName === 'INPUT' || element.tagName === 'SELECT' || element.tagName === 'TEXTAREA') {
                element.disabled = true;
            }
        });
    }
</script>

@endsection
        if (!localInput || !hiddenInput) {
            return;
        }

        const local = (localInput.value || '').trim().replace(/\s/g, '');
        hiddenInput.value = local ? '+63' + local : '';
    }

    if (residentProfileForm) {
        residentProfileForm.addEventListener('submit', assembleResidentEmergencyContactNumber);
    }
</script>
