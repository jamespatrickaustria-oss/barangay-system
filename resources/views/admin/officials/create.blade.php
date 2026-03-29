@extends('layouts.app')

@section('title', 'Register')

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
        max-width: 640px;
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

    .form-group input {
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

    .form-group input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 4px rgba(26, 111, 204, 0.1);
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

<a href="{{ route('admin.officials.index') }}" class="back-link">← Back to Barangay Officials List</a>

<div class="form-card">
    <h1 class="form-title">Register a Barangay Official</h1>
    <p class="form-subtitle">Grant system administrator access to an official</p>

    <div class="info-box">
        The log-in credentials will be sent to the registered email address.
    </div>

    <form method="POST" action="{{ route('admin.officials.store') }}" id="officialCreateForm">
        @csrf

        <div class="form-grid">
            <div class="full">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Juan Dela Cruz"
                        required
                    >
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="full">
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="official@barangay.gov.ph"
                        required
                    >
                    @error('email')
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
                    <div style="margin-top: 6px; color: var(--text-muted); font-size: 12px;">
                        Use at least 8 characters with uppercase, lowercase, number, and special character (e.g. @ $ % ! *).
                    </div>
                    <ul id="password_rule_list" style="margin: 8px 0 0 18px; color: var(--text-muted); font-size: 12px; line-height: 1.5;">
                        <li id="pw_rule_len">At least 8 characters</li>
                        <li id="pw_rule_lower">At least one lowercase letter (a-z)</li>
                        <li id="pw_rule_upper">At least one uppercase letter (A-Z)</li>
                        <li id="pw_rule_number">At least one number (0-9)</li>
                        <li id="pw_rule_symbol">At least one special character (e.g. @ $ % ! *)</li>
                    </ul>
                    <div class="error-message" id="js_password_error" style="display: none;"></div>
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
                    <div class="error-message" id="js_password_confirmation_error" style="display: none;"></div>
                    @error('password_confirmation')
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
                        value="{{ old('phone') }}"
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
                    <label for="address">Address</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address"
                        value="{{ old('address') }}"
                        placeholder="Office address"
                    >
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="submit-btn">Register</button>
    </form>
</div>

<script>
    function evaluatePasswordRules(value) {
        return {
            length: value.length >= 8,
            lower: /[a-z]/.test(value),
            upper: /[A-Z]/.test(value),
            number: /[0-9]/.test(value),
            symbol: /[^A-Za-z0-9]/.test(value),
        };
    }

    function getFirstPasswordRuleMessage(rules) {
        if (!rules.length) return 'Password must be at least 8 characters.';
        if (!rules.lower) return 'Password must include at least one lowercase letter.';
        if (!rules.upper) return 'Password must include at least one uppercase letter.';
        if (!rules.number) return 'Password must include at least one number.';
        if (!rules.symbol) return 'Password must include at least one special character.';
        return '';
    }

    function setPasswordError(message) {
        const el = document.getElementById('js_password_error');
        if (!el) return;
        el.textContent = message;
        el.style.display = message ? 'block' : 'none';
    }

    function setPasswordConfirmationError(message) {
        const el = document.getElementById('js_password_confirmation_error');
        if (!el) return;
        el.textContent = message;
        el.style.display = message ? 'block' : 'none';
    }

    function updatePasswordRuleChecklist() {
        const pwField = document.getElementById('password');
        if (!pwField) return;

        const rules = evaluatePasswordRules(pwField.value || '');
        const styleRule = (id, passed) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.color = passed ? '#2c5f35' : 'var(--text-muted)';
            el.style.fontWeight = passed ? '700' : '500';
        };

        styleRule('pw_rule_len', rules.length);
        styleRule('pw_rule_lower', rules.lower);
        styleRule('pw_rule_upper', rules.upper);
        styleRule('pw_rule_number', rules.number);
        styleRule('pw_rule_symbol', rules.symbol);
    }

    const officialCreateForm = document.getElementById('officialCreateForm');
    const passwordField = document.getElementById('password');
    const passwordConfirmationField = document.getElementById('password_confirmation');

    if (passwordField) {
        passwordField.addEventListener('input', function () {
            updatePasswordRuleChecklist();
            const rules = evaluatePasswordRules(passwordField.value || '');
            setPasswordError(getFirstPasswordRuleMessage(rules));
            if (rules.length && rules.lower && rules.upper && rules.number && rules.symbol) {
                setPasswordError('');
            }
        });

        passwordField.addEventListener('blur', updatePasswordRuleChecklist);
        updatePasswordRuleChecklist();
    }

    if (passwordConfirmationField) {
        passwordConfirmationField.addEventListener('input', function () {
            if ((passwordField.value || '') === (passwordConfirmationField.value || '')) {
                setPasswordConfirmationError('');
            }
        });
    }

    if (officialCreateForm) {
        officialCreateForm.addEventListener('submit', function (e) {
            const value = passwordField ? (passwordField.value || '') : '';
            const rules = evaluatePasswordRules(value);
            const validPassword = rules.length && rules.lower && rules.upper && rules.number && rules.symbol;

            setPasswordError(validPassword ? '' : getFirstPasswordRuleMessage(rules));

            const matches = passwordField && passwordConfirmationField && passwordField.value === passwordConfirmationField.value;
            setPasswordConfirmationError(matches ? '' : 'Passwords do not match.');

            if (!validPassword || !matches) {
                e.preventDefault();
            }
        });
    }

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
