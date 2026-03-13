@extends('layouts.app')

@section('title', 'Register Resident')

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

    .info-box {
        background: var(--blue-light);
        border-left: 4px solid var(--blue);
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 28px;
        font-size: 13px;
        color: #1a4a8a;
        line-height: 1.6;
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

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 45px;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        color: var(--blue);
    }

    .password-toggle svg {
        width: 20px;
        height: 20px;
    }

    .submit-btn {
        width: 100%;
        background: linear-gradient(135deg, #1a6fcc, #3a8a3f);
        border: none;
        border-radius: 12px;
        padding: 15px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        margin-top: 8px;
        transition: opacity 0.2s, transform 0.1s;
    }

    .submit-btn:hover {
        opacity: 0.92;
    }

    .submit-btn:active {
        transform: scale(0.99);
    }
</style>

<a href="{{ route('official.residents.index') }}" class="back-link">← Back to Residents</a>

<div class="form-card">
    <h1 class="form-title">Register New Resident</h1>
    <p class="form-subtitle">Registered residents are automatically approved.</p>

    <div class="info-box">
        ✉️ The resident will receive a welcome email with their login credentials.
    </div>

    <form method="POST" action="{{ route('official.residents.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <div>
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name"
                        value="{{ old('first_name') }}"
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
                        value="{{ old('middle_name') }}"
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
                        value="{{ old('surname') }}"
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
                    <label for="email">Email Address *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="juan@example.com"
                        required
                    >
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="+63 9XX XXX XXXX"
                        pattern="[0-9\+\-\(\)\s\.]*"
                        inputmode="numeric"
                        required
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
                        value="{{ old('father_name') }}"
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
                        value="{{ old('mother_name') }}"
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
                        value="{{ old('house_no') }}"
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
                        value="{{ old('barangay') }}"
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
                        value="{{ old('municipality_city') }}"
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
                        value="{{ old('nationality') }}"
                        placeholder="e.g. Filipino"
                    >
                    @error('nationality')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            placeholder="••••••••••••"
                            required
                        >
                        <span class="password-toggle" onclick="togglePassword('password')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path id="eye-icon-password" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            placeholder="••••••••••••"
                            required
                        >
                        <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path id="eye-icon-password_confirmation" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="full">
                <div class="form-group">
                    <label for="profile_photo">Upload Personal Photo *</label>
                    <input 
                        type="file" 
                        id="profile_photo" 
                        name="profile_photo"
                        accept=".jpg,.jpeg,.png"
                        required
                    >
                    <small style="color: var(--text-muted); font-size: 12px;">Accepted formats: JPG, JPEG, PNG. Max 5MB.</small>
                    @error('profile_photo')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="full">
                <div class="form-group">
                    <label for="address">Address *</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address"
                        value="{{ old('address') }}"
                        placeholder="Street address, Barangay name"
                        required
                    >
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="birthdate">Birthdate *</label>
                    <input 
                        type="date" 
                        id="birthdate" 
                        name="birthdate"
                        value="{{ old('birthdate') }}"
                        required
                    >
                    @error('birthdate')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="gender">Gender *</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                        <option value="other" @selected(old('gender') === 'other')>Other</option>
                    </select>
                    @error('gender')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="marital_status">Marital Status</label>
                    <select id="marital_status" name="marital_status">
                        <option value="">Select Civil Status</option>
                        <option value="single" @selected(old('marital_status') === 'single')>Single</option>
                        <option value="married" @selected(old('marital_status') === 'married')>Married</option>
                        <option value="divorced" @selected(old('marital_status') === 'divorced')>Divorced</option>
                        <option value="widowed" @selected(old('marital_status') === 'widowed')>Widowed</option>
                        <option value="separated" @selected(old('marital_status') === 'separated')>Separated</option>
                    </select>
                    @error('marital_status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="submit-btn">✓ Create & Approve Resident</button>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const toggleIcon = document.getElementById('eye-icon-' + fieldId);
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            // Change to eye-slash (hidden) icon
            toggleIcon.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
        } else {
            passwordField.type = 'password';
            // Change to normal eye icon
            toggleIcon.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
        }
    }
</script>

@endsection
