<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Management System - Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --blue:       #1a6ec7;
            --blue-dark:  #154f96;
            --sky:        #d9f2fc;
            --sky-mid:    #a8dff5;
            --green:      #3a7d44;
            --green-dark: #2c5f35;
            --white:      #ffffff;
            --off-white:  #f4f8fb;
            --text:       #1a2433;
            --text-light: #4b5e72;
            --border:     rgba(26,110,199,0.12);
            --gold:       #e8b84b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            color: var(--text);
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef3 100%);
            padding: 40px 20px;
        }

        .left-panel {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            max-width: 700px;
            width: 100%;
            background: white;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        }

        /* Step Progress Indicator */
        .step-progress {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 36px;
            padding-bottom: 28px;
            border-bottom: 2px solid #f0f4f8;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
            max-width: 160px;
        }

        .step-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 700;
            border: 2px solid #d1dbe8;
            color: #9aafbf;
            background: white;
            transition: all 0.35s ease;
            position: relative;
            z-index: 1;
        }

        .step-circle .check-icon { display: none; }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, var(--blue) 0%, #2563c8 100%);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 14px rgba(26,110,199,0.35);
        }

        .step-item.completed .step-circle {
            background: var(--green);
            border-color: transparent;
            color: white;
        }

        .step-item.completed .step-circle .num-icon  { display: none; }
        .step-item.completed .step-circle .check-icon { display: block; }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            color: #9aafbf;
            text-align: center;
            line-height: 1.3;
            transition: color 0.3s;
        }

        .step-item.active    .step-label { color: var(--blue); }
        .step-item.completed .step-label { color: var(--green); }

        .step-connector {
            flex: 1;
            height: 2px;
            background: #d1dbe8;
            margin: 0 4px;
            margin-bottom: 30px;
            transition: background 0.35s ease;
        }

        .step-connector.completed { background: var(--green); }

        /* Step Header */
        .step-header { margin-bottom: 28px; }

        .step-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 4px;
        }

        .step-header p {
            font-size: 13px;
            color: var(--text-light);
        }

        /* Step visibility */
        .form-step        { display: block; }
        .form-step.hidden { display: none;  }

        /* Form Fields */
        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 6px;
        }

        .form-group label .required { color: #dc3545; }

        .form-group label .hint {
            text-transform: none;
            letter-spacing: 0;
            font-weight: 500;
            color: #9aafbf;
            font-size: 11px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            color: var(--text);
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-group input[type="file"] { padding: 12px; cursor: pointer; }

        .form-group input[type="file"]::file-selector-button {
            background: var(--blue);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 10px;
            font-weight: 600;
        }

        .form-group input[type="file"]::file-selector-button:hover {
            background: var(--blue-dark);
        }

        .photo-preview {
            margin-top: 10px;
            display: none;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #f8fbff;
        }

        .photo-preview.visible { display: flex; }

        .photo-preview img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #d7e1ea;
            background: #ffffff;
        }

        .photo-preview-meta {
            font-size: 12px;
            color: var(--text-light);
            line-height: 1.45;
        }

        .photo-preview-meta strong {
            display: block;
            color: var(--text);
            font-size: 13px;
            margin-bottom: 3px;
            word-break: break-word;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(26,110,199,0.1);
        }

        .form-group input.error,
        .form-group select.error { border-color: #dc3545; box-shadow: none; }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235a7a9a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 40px;
        }

        .error-message { color: #dc3545; font-size: 12px; margin-top: 4px; }

        /* Phone prefix wrapper */
        .phone-wrapper {
            display: flex;
            align-items: stretch;
            border: 2px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .phone-wrapper:focus-within {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(26,110,199,0.1);
        }

        .phone-wrapper.error { border-color: #dc3545; box-shadow: none; }

        .phone-prefix {
            display: flex;
            align-items: center;
            padding: 0 14px;
            background: #f0f4f8;
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
            border-right: 2px solid var(--border);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .phone-wrapper input {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            flex: 1;
            min-width: 0;
        }

        /* Password toggle */
        .password-wrapper { position: relative; }
        .password-wrapper input { padding-right: 46px; }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-light);
            transition: color 0.2s;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover { color: var(--blue); }
        .password-toggle svg { width: 20px; height: 20px; }

        /* Form grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 20px;
        }

        .form-grid .full { grid-column: 1 / -1; }

        /* Navigation buttons */
        .nav-buttons { display: flex; gap: 12px; margin-top: 28px; }

        .btn-primary {
            flex: 1;
            padding: 15px;
            background: linear-gradient(135deg, #1a6ec7, #3a7d44);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover  { opacity: 0.92; }
        .btn-primary:active { transform: scale(0.99); }

        .btn-back {
            flex: 0 0 auto;
            padding: 15px 22px;
            background: #f0f4f8;
            border: 2px solid #d1dbe8;
            border-radius: 12px;
            color: var(--text-light);
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, color 0.2s;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-back:hover {
            background: #e2eaf2;
            border-color: #b0c4d6;
            color: var(--text);
        }

        /* Info banner */
        .info-banner {
            background: #eaf4ff;
            border-left: 4px solid var(--blue);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 13px;
            color: var(--blue-dark);
            margin-top: 20px;
            line-height: 1.65;
        }

        .register-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-light);
        }

        .register-footer a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .register-footer a:hover { opacity: 0.8; }

        /* Confirm modal */
        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(13,27,42,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .confirm-overlay.hidden { display: none; }

        .confirm-modal {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            padding: 28px 24px;
            box-shadow: 0 10px 32px rgba(26,110,199,0.2);
            text-align: center;
        }

        .confirm-modal h3 { font-size: 20px; color: var(--blue); margin-bottom: 10px; }
        .confirm-modal p  { color: var(--text-light); font-size: 14px; line-height: 1.65; margin-bottom: 20px; }

        .confirm-actions { display: flex; gap: 12px; justify-content: center; }

        .confirm-btn, .cancel-btn {
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .confirm-btn { background: var(--blue); color: #fff; }
        .cancel-btn  { background: #eaf4ff; color: var(--blue); }

        /* Responsive */
        @media (max-width: 768px) {
            body { padding: 24px 16px; }
            .register-card { padding: 28px 20px; border-radius: 16px; }
            .step-circle { width: 36px; height: 36px; font-size: 13px; }
            .step-label  { font-size: 11px; }
            .step-header h2 { font-size: 19px; }
            .form-grid { grid-template-columns: 1fr; gap: 0; }
            .form-grid .full { grid-column: 1; }
            .form-group input,
            .form-group select { padding: 12px 14px; font-size: 16px; border-radius: 10px; min-height: 44px; }
            .nav-buttons { flex-direction: column-reverse; }
            .btn-back { flex: 1; }
            .confirm-actions { flex-direction: column; }
            .confirm-btn, .cancel-btn { width: 100%; }
        }

        @media (max-width: 480px) {
            body { padding: 16px 12px; }
            .register-card { padding: 20px 16px; border-radius: 12px; }
            .step-progress { padding-bottom: 20px; margin-bottom: 24px; }
            .step-label { display: none; }
            .step-connector { margin-bottom: 0; }
            .form-group { margin-bottom: 14px; }
            .form-group label { font-size: 11px; margin-bottom: 5px; letter-spacing: 0.5px; }
            .form-group input,
            .form-group select { padding: 11px 12px; border-radius: 8px; min-height: 44px; font-size: 16px; }
            .btn-primary, .btn-back { padding: 13px 12px; font-size: 15px; min-height: 46px; border-radius: 10px; }
            .info-banner { padding: 12px 14px; font-size: 12px; }
            .register-footer { font-size: 13px; margin-top: 16px; }
            .confirm-modal { padding: 22px 18px; }
            .confirm-modal h3 { font-size: 18px; }
            .confirm-modal p  { font-size: 13px; margin-bottom: 16px; }
        }

        @media (hover: none) and (pointer: coarse) {
            .form-group input,
            .form-group select,
            .btn-primary,
            .btn-back,
            .confirm-btn,
            .cancel-btn { -webkit-tap-highlight-color: transparent; min-height: 44px; }
            .password-toggle { min-width: 44px; min-height: 44px; right: 6px; }
        }
    </style>
</head>
<body>
<div class="left-panel">
<div class="register-card">

    <!-- Step Progress Indicator -->
    <div class="step-progress">
        <div class="step-item active" id="indicator-1">
            <div class="step-circle">
                <span class="num-icon">1</span>
                <svg class="check-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="step-label">Personal Info</span>
        </div>

        <div class="step-connector" id="connector-1-2"></div>

        <div class="step-item" id="indicator-2">
            <div class="step-circle">
                <span class="num-icon">2</span>
                <svg class="check-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="step-label">Contact Details</span>
        </div>

        <div class="step-connector" id="connector-2-3"></div>

        <div class="step-item" id="indicator-3">
            <div class="step-circle">
                <span class="num-icon">3</span>
                <svg class="check-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="step-label">Account Details</span>
        </div>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" id="residentRegisterForm" enctype="multipart/form-data">
        @csrf

        {{-- Hidden field that receives the assembled +63 phone number on submit --}}
        <input type="hidden" name="phone" id="phone_full" value="{{ old('phone') }}">

        <!-- ================================================
             STEP 1 - Personal Information
        ================================================= -->
        <div class="form-step" id="step-1">
            <div class="step-header">
                <h2>Personal Information</h2>
                <p>Step 1 of 3 &mdash; Fill in your personal details</p>
            </div>

            <div class="form-grid">

                <div>
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name"
                               value="{{ old('first_name') }}"
                               placeholder="Juan"
                               autocomplete="given-name"
                               class="@error('first_name') error @enderror">
                        @error('first_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name"
                               value="{{ old('middle_name') }}"
                               placeholder="Santos"
                               autocomplete="additional-name"
                               class="@error('middle_name') error @enderror">
                        @error('middle_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="surname">Surname <span class="required">*</span></label>
                        <input type="text" id="surname" name="surname"
                               value="{{ old('surname') }}"
                               placeholder="Cruz"
                               autocomplete="family-name"
                               class="@error('surname') error @enderror">
                        @error('surname')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="birthdate">
                            Birthdate <span class="required">*</span>
                            <span class="hint">(dd/mm/yyyy)</span>
                        </label>
                        <input type="date" id="birthdate" name="birthdate"
                               value="{{ old('birthdate') }}"
                               class="@error('birthdate') error @enderror">
                        @error('birthdate')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="gender">Gender <span class="required">*</span></label>
                        <select id="gender" name="gender"
                                class="@error('gender') error @enderror">
                            <option value="">Select Gender</option>
                            <option value="male"   @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                            <option value="other"  @selected(old('gender') === 'other')>Other</option>
                        </select>
                        @error('gender')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="marital_status">Civil Status</label>
                        <select id="marital_status" name="marital_status"
                                class="@error('marital_status') error @enderror">
                            <option value="">Select Civil Status</option>
                            <option value="single"    @selected(old('marital_status') === 'single')>Single</option>
                            <option value="married"   @selected(old('marital_status') === 'married')>Married</option>
                            <option value="divorced"  @selected(old('marital_status') === 'divorced')>Divorced</option>
                            <option value="widowed"   @selected(old('marital_status') === 'widowed')>Widowed</option>
                            <option value="separated" @selected(old('marital_status') === 'separated')>Separated</option>
                        </select>
                        @error('marital_status')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <input type="text" id="nationality" name="nationality"
                               value="{{ old('nationality') }}"
                               placeholder="e.g. Filipino"
                               class="@error('nationality') error @enderror">
                        @error('nationality')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="father_name">Father's Name</label>
                        <input type="text" id="father_name" name="father_name"
                               value="{{ old('father_name') }}"
                               placeholder="Enter full name"
                               class="@error('father_name') error @enderror">
                        @error('father_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="mother_name">Mother's Name</label>
                        <input type="text" id="mother_name" name="mother_name"
                               value="{{ old('mother_name') }}"
                               placeholder="Enter full name"
                               class="@error('mother_name') error @enderror">
                        @error('mother_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="full">
                    <div class="form-group">
                        <label for="profile_photo">Upload Personal Photo <span class="required">*</span></label>
                        <input type="file" id="profile_photo" name="profile_photo"
                               accept=".jpg,.jpeg,.png"
                               class="@error('profile_photo') error @enderror">
                        <div class="photo-preview" id="profilePhotoPreview">
                            <img id="profilePhotoPreviewImage" alt="Selected personal photo preview">
                            <div class="photo-preview-meta">
                                <strong id="profilePhotoPreviewName"></strong>
                                <span>Selected photo preview</span>
                            </div>
                        </div>
                        <small style="color:var(--text-light);font-size:12px;margin-top:5px;display:block;">
                            Accepted formats: JPG, JPEG, PNG &mdash; Max 5MB. Used as your Online ID photo.
                        </small>
                        @error('profile_photo')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>

            <div class="nav-buttons">
                <button type="button" class="btn-primary" onclick="goToStep(2)">
                    Next &mdash; Contact Details
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>


        <!-- ================================================
             STEP 2 - Contact Details
        ================================================= -->
        <div class="form-step hidden" id="step-2">
            <div class="step-header">
                <h2>Contact Details</h2>
                <p>Step 2 of 3 &mdash; Provide your contact and address information</p>
            </div>

            <div class="form-grid">

                <div class="full">
                    <div class="form-group">
                        <label for="phone_local">Phone Number <span class="required">*</span></label>
                        <div class="phone-wrapper" id="phone_wrapper">
                            <span class="phone-prefix">+63</span>
                            <input type="tel" id="phone_local"
                                   placeholder="9XX XXX XXXX"
                                   maxlength="12"
                                   inputmode="numeric"
                                   pattern="[0-9\s]*"
                                   autocomplete="tel-national">
                        </div>
                        @error('phone')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="house_no">House No #</label>
                        <input type="text" id="house_no" name="house_no"
                               value="{{ old('house_no') }}"
                               placeholder="e.g. 123-B"
                               class="@error('house_no') error @enderror">
                        @error('house_no')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="barangay">Barangay</label>
                        <input type="text" id="barangay" name="barangay"
                               value="{{ old('barangay') }}"
                               placeholder="e.g. Barangay San Juan"
                               class="@error('barangay') error @enderror">
                        @error('barangay')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="municipality_city">Municipality/City</label>
                        <input type="text" id="municipality_city" name="municipality_city"
                               value="{{ old('municipality_city') }}"
                               placeholder="e.g. General Trias"
                               class="@error('municipality_city') error @enderror">
                        @error('municipality_city')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="full">
                    <div class="form-group">
                        <label for="address">Address <span class="required">*</span></label>
                        <input type="text" id="address" name="address"
                               value="{{ old('address') }}"
                               placeholder="Street address, Barangay name"
                               autocomplete="street-address"
                               class="@error('address') error @enderror">
                        @error('address')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>

            <div class="nav-buttons">
                <button type="button" class="btn-back" onclick="goToStep(1)">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back
                </button>
                <button type="button" class="btn-primary" onclick="goToStep(3)">
                    Next &mdash; Account Details
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>


        <!-- ================================================
             STEP 3 - Account Details
        ================================================= -->
        <div class="form-step hidden" id="step-3">
            <div class="step-header">
                <h2>Account Details</h2>
                <p>Step 3 of 3 &mdash; Set up your login credentials</p>
            </div>

            <div class="form-grid">

                <div class="full">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="your.email@example.com"
                               autocomplete="email"
                               class="@error('email') error @enderror">
                        @error('email')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="password">Password <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password"
                                   placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                   autocomplete="new-password"
                                   class="@error('password') error @enderror">
                            <span class="password-toggle" onclick="togglePassword('password')" title="Toggle visibility">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path id="eye-icon-password" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </span>
                        </div>
                        @error('password')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                                   autocomplete="new-password"
                                   class="@error('password_confirmation') error @enderror">
                            <span class="password-toggle" onclick="togglePassword('password_confirmation')" title="Toggle visibility">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path id="eye-icon-password_confirmation" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </span>
                        </div>
                        @error('password_confirmation')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>

            <div class="nav-buttons">
                <button type="button" class="btn-back" onclick="goToStep(2)">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back
                </button>
                <button type="submit" class="btn-primary" id="submitRegistrationBtn">
                    Submit Registration Request
                </button>
            </div>

            <div class="info-banner">
                &#x23F3; Your account will require approval from an administrator before you can log in.
                You will be notified via email once your registration has been reviewed.
            </div>
        </div>

        <div class="register-footer">
            Already have an account? <a href="{{ route('login') }}">Back to login</a>
        </div>

    </form>
</div>
</div>


<!-- Confirm Modal -->
<div class="confirm-overlay hidden" id="registerConfirmOverlay"
     role="dialog" aria-modal="true" aria-labelledby="registerConfirmTitle">
    <div class="confirm-modal">
        <h3 id="registerConfirmTitle">Confirm Registration</h3>
        <p>Your account will be marked as <strong>pending</strong>. Please wait for
           authorization from an appropriate administrator before you can access the system.</p>
        <div class="confirm-actions">
            <button type="button" class="cancel-btn" id="cancelRegisterConfirm">Cancel</button>
            <button type="button" class="confirm-btn" id="confirmRegisterSubmit">Submit Request</button>
        </div>
    </div>
</div>


<script>
/* ---------------------------------------------------------------
   Multi-step Registration Logic
---------------------------------------------------------------- */
let currentStep = 1;

/* Required fields / validation rules per step */
const stepRules = {
    1: [
        { id: 'first_name',    label: 'First Name' },
        { id: 'surname',       label: 'Surname' },
        { id: 'birthdate',     label: 'Birthdate' },
        { id: 'gender',        label: 'Gender' },
        { id: 'profile_photo', label: 'Personal Photo', type: 'file' },
    ],
    2: [
        { id: 'phone_local', label: 'Phone Number', custom: validatePhone },
        { id: 'address',     label: 'Address' },
    ],
    3: [
        { id: 'email',    label: 'Email Address' },
        { id: 'password', label: 'Password / Confirm Password', custom: validatePasswords },
    ],
};

/* ---- Custom validators ---------------------------------------- */
function validatePhone() {
    const input  = document.getElementById('phone_local');
    const raw    = (input ? input.value : '').trim();
    const digits = raw.replace(/\s/g, '');

    if (!raw) {
        markError('phone_local', 'Phone number is required.');
        document.getElementById('phone_wrapper').classList.add('error');
        return false;
    }
    if (!/^\d{10}$/.test(digits)) {
        markError('phone_local', 'Enter a valid 10-digit number (e.g. 9171234567).');
        document.getElementById('phone_wrapper').classList.add('error');
        return false;
    }
    clearError('phone_local');
    document.getElementById('phone_wrapper').classList.remove('error');
    return true;
}

function validatePasswords() {
    const pw  = document.getElementById('password');
    const pwc = document.getElementById('password_confirmation');
    let ok = true;

    if (!pw.value) {
        markError('password', 'Password is required.');
        ok = false;
    } else if (pw.value.length < 8) {
        markError('password', 'Password must be at least 8 characters.');
        ok = false;
    } else {
        clearError('password');
    }

    if (ok) {
        if (!pwc.value) {
            markError('password_confirmation', 'Please confirm your password.');
            ok = false;
        } else if (pw.value !== pwc.value) {
            markError('password_confirmation', 'Passwords do not match.');
            ok = false;
        } else {
            clearError('password_confirmation');
        }
    }
    return ok;
}

/* ---- Error helpers -------------------------------------------- */
function markError(fieldId, message) {
    const el = document.getElementById(fieldId);
    if (el) el.classList.add('error');

    const errId = 'jserr_' + fieldId;
    let errEl   = document.getElementById(errId);
    if (!errEl) {
        errEl           = document.createElement('div');
        errEl.className = 'error-message';
        errEl.id        = errId;
        /* Insert after wrapper if password, otherwise after field */
        const parent = el ? (el.closest('.password-wrapper') || el.closest('.form-group') || el.parentNode) : null;
        if (parent) parent.appendChild(errEl);
    }
    errEl.textContent   = message;
    errEl.style.display = 'block';
}

function clearError(fieldId) {
    const el    = document.getElementById(fieldId);
    const errEl = document.getElementById('jserr_' + fieldId);
    if (el)    el.classList.remove('error');
    if (errEl) errEl.style.display = 'none';
}

function clearStepErrors(step) {
    (stepRules[step] || []).forEach(r => {
        clearError(r.id);
    });
    if (step === 2) document.getElementById('phone_wrapper').classList.remove('error');
}

/* ---- Validate current step ------------------------------------ */
function validateStep(step) {
    clearStepErrors(step);

    // Always clear pw_confirmation error too when re-validating step 3
    if (step === 3) clearError('password_confirmation');

    let valid = true;

    for (const rule of stepRules[step]) {
        if (rule.custom) {
            if (!rule.custom()) valid = false;
            continue;
        }

        const el = document.getElementById(rule.id);
        if (!el) continue;

        let empty;
        if (rule.type === 'file') {
            empty = el.files.length === 0;
            if (!empty) {
                const file = el.files[0];
                if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                    markError(rule.id, 'Only JPG, JPEG or PNG files are accepted.');
                    valid = false;
                    continue;
                }
                if (file.size > 5 * 1024 * 1024) {
                    markError(rule.id, 'File size must not exceed 5 MB.');
                    valid = false;
                    continue;
                }
            }
        } else {
            empty = !el.value.trim();
        }

        if (empty) {
            markError(rule.id, rule.label + ' is required.');
            valid = false;
        }
    }

    return valid;
}

/* ---- Step navigation ------------------------------------------ */
function goToStep(target) {
    if (target > currentStep && !validateStep(currentStep)) {
        const firstErr = document.querySelector(
            '#step-' + currentStep + ' .error, ' +
            '#step-' + currentStep + ' .error-message[style*="block"]'
        );
        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    document.getElementById('step-' + currentStep).classList.add('hidden');
    document.getElementById('step-' + target).classList.remove('hidden');

    updateProgress(target);
    currentStep = target;

    document.querySelector('.register-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function updateProgress(active) {
    for (let i = 1; i <= 3; i++) {
        const item = document.getElementById('indicator-' + i);
        item.classList.remove('active', 'completed');
        if      (i < active)  item.classList.add('completed');
        else if (i === active) item.classList.add('active');
    }
    document.getElementById('connector-1-2').classList.toggle('completed', active > 1);
    document.getElementById('connector-2-3').classList.toggle('completed', active > 2);
}

/* ---- Phone assembly before submit ----------------------------- */
function assemblePhone() {
    const local = (document.getElementById('phone_local').value || '').trim().replace(/\s/g, '');
    document.getElementById('phone_full').value = '+63' + local;
}

/* ---- Confirm modal + form submit ------------------------------ */
const registerForm     = document.getElementById('residentRegisterForm');
const confirmOverlay   = document.getElementById('registerConfirmOverlay');
const cancelConfirmBtn = document.getElementById('cancelRegisterConfirm');
const confirmSubmitBtn = document.getElementById('confirmRegisterSubmit');
const profilePhotoInput = document.getElementById('profile_photo');
const profilePhotoPreview = document.getElementById('profilePhotoPreview');
const profilePhotoPreviewImage = document.getElementById('profilePhotoPreviewImage');
const profilePhotoPreviewName = document.getElementById('profilePhotoPreviewName');

let currentPhotoPreviewUrl = null;

function updateProfilePhotoPreview(file) {
    if (currentPhotoPreviewUrl) {
        URL.revokeObjectURL(currentPhotoPreviewUrl);
        currentPhotoPreviewUrl = null;
    }

    if (!file || !file.type.startsWith('image/')) {
        profilePhotoPreview.classList.remove('visible');
        profilePhotoPreviewImage.removeAttribute('src');
        profilePhotoPreviewName.textContent = '';
        return;
    }

    currentPhotoPreviewUrl = URL.createObjectURL(file);
    profilePhotoPreviewImage.src = currentPhotoPreviewUrl;
    profilePhotoPreviewName.textContent = file.name;
    profilePhotoPreview.classList.add('visible');
}

profilePhotoInput.addEventListener('change', function () {
    updateProfilePhotoPreview(this.files && this.files[0] ? this.files[0] : null);
});

registerForm.addEventListener('submit', function (e) {
    if (registerForm.dataset.confirmed === 'true') {
        assemblePhone();
        return;
    }
    e.preventDefault();
    if (!validateStep(3)) return;
    confirmOverlay.classList.remove('hidden');
});

cancelConfirmBtn.addEventListener('click', () => confirmOverlay.classList.add('hidden'));

confirmSubmitBtn.addEventListener('click', () => {
    assemblePhone();
    registerForm.dataset.confirmed = 'true';
    registerForm.submit();
});

/* ---- Password visibility toggle ------------------------------- */
function togglePassword(fieldId) {
    const field    = document.getElementById(fieldId);
    const iconPath = document.getElementById('eye-icon-' + fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        iconPath.setAttribute('d',
            'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7' +
            'a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243' +
            'M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29' +
            'm7.532 7.532l3.29 3.29M3 3l3.59 3.59' +
            'm0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7' +
            'a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
    } else {
        field.type = 'password';
        iconPath.setAttribute('d',
            'M15 12a3 3 0 11-6 0 3 3 0 016 0z ' +
            'M2.458 12C3.732 7.943 7.523 5 12 5' +
            'c4.478 0 8.268 2.943 9.542 7' +
            '-1.274 4.057-5.064 7-9.542 7' +
            '-4.477 0-8.268-2.943-9.542-7z');
    }
}

/* ---- Restore old phone value on server validation error ------- */
@if(old('phone'))
(function () {
    const raw = "{{ addslashes(old('phone')) }}";
    document.getElementById('phone_local').value =
        raw.startsWith('+63') ? raw.substring(3) : raw;
})();
@endif

/* ---- Jump to the step that has server-side errors ------------- */
@if($errors->any())
(function () {
    const step1Keys = ['first_name','middle_name','surname','birthdate','gender',
                       'marital_status','nationality','father_name','mother_name','profile_photo'];
    const step2Keys = ['phone','address','house_no','barangay','municipality_city'];
    const errorKeys = @json($errors->keys());

    let target = 3;
    for (const k of errorKeys) {
        if (step1Keys.includes(k)) { target = 1; break; }
        if (step2Keys.includes(k)) { target = 2; break; }
    }

    if (target > 1) {
        document.getElementById('step-1').classList.add('hidden');
        if (target > 2) document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-' + target).classList.remove('hidden');
        updateProgress(target);
        currentStep = target;
    }
})();
@endif
</script>
</body>
</html>