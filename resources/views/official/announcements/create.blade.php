@extends('layouts.app')

@section('title', 'Create Announcement')

@section('content')
@php
    $routePrefix = request()->segment(1) === 'admin' ? 'admin' : 'official';
@endphp
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

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 15px;
        font-family: inherit;
        color: var(--text);
        background: white;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 4px rgba(26, 111, 204, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 200px;
        font-family: inherit;
    }

    .char-counter {
        text-align: right;
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 28px;
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
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cancel-btn:hover {
        background: #f8f9fa;
        border-color: var(--blue);
        color: var(--blue);
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }

        .button-group {
            flex-direction: column;
        }
    }
</style>

<a href="{{ route($routePrefix . '.announcements.index') }}" class="back-link">← Back to Announcements</a>

<div class="form-card">
    <h1 class="form-title">📢 Create Announcement</h1>
    <p class="form-subtitle">Share important updates with all residents</p>

    <div class="info-box">
        ✉️ This announcement will be displayed on the resident dashboard. All approved residents will be able to see it.
    </div>

    <form method="POST" action="{{ route($routePrefix . '.announcements.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Announcement Title *</label>
            <input 
                type="text" 
                id="title" 
                name="title"
                value="{{ old('title') }}"
                placeholder="e.g., Barangay Fiesta Schedule Announced"
                required
            >
            @error('title')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Announcement Content *</label>
            <textarea 
                id="content" 
                name="content"
                placeholder="Write your announcement here. You can include important dates, details, and instructions for residents..."
                required
            >{{ old('content') }}</textarea>
            @error('content')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="photo">Attach Photo (Optional)</label>
            <input
                type="file"
                id="photo"
                name="photo"
                accept=".jpg,.jpeg,.png"
                onchange="previewPhoto(this)"
            >
            <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">
                Supported formats: JPG, JPEG, PNG. Max 5MB.
            </small>
            @error('photo')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div id="photo-preview-wrap" style="display:none; margin-top: 12px;">
                <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-muted); margin: 0 0 6px 0;">Preview</p>
                <img id="photo-preview" src="" alt="Photo preview" style="max-width: 260px; max-height: 200px; border-radius: 10px; border: 2px solid var(--border); object-fit: cover; display: block;">
                <button type="button" onclick="clearPhotoPreview()" style="margin-top: 8px; background: none; border: none; color: #dc3545; font-size: 13px; font-weight: 600; cursor: pointer; padding: 0;">✕ Remove</button>
            </div>
        </div>

        <div class="form-group">
            <label for="expires_at">Expiration Date (Optional)</label>
            <input 
                type="date" 
                id="expires_at" 
                name="expires_at"
                value="{{ old('expires_at') }}"
            >
            <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">
                Leave empty to keep this announcement active indefinitely. Or set a date when it should expire.
            </small>
            @error('expires_at')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="button-group">
            <button type="submit" class="submit-btn">📢 Publish Announcement</button>
            <a href="{{ route($routePrefix . '.announcements.index') }}" class="cancel-btn">Cancel</a>
        </div>
    </form>
</div>

<script>
function previewPhoto(input) {
    const wrap = document.getElementById('photo-preview-wrap');
    const preview = document.getElementById('photo-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            wrap.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        wrap.style.display = 'none';
        preview.src = '';
    }
}

function clearPhotoPreview() {
    const input = document.getElementById('photo');
    const wrap = document.getElementById('photo-preview-wrap');
    const preview = document.getElementById('photo-preview');
    input.value = '';
    wrap.style.display = 'none';
    preview.src = '';
}
</script>

@endsection
