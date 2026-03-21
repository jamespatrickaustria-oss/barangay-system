@extends('layouts.app')

@section('title', 'Manage Resident Photo')

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

    .page-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        max-width: 760px;
        margin: 0 auto;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 6px 0;
    }

    .page-subtitle {
        font-size: 14px;
        color: var(--text-muted);
        margin: 0 0 20px 0;
    }

    .notice {
        background: var(--blue-light);
        border-left: 4px solid var(--blue);
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 24px;
        font-size: 13px;
        color: #1a4a8a;
        line-height: 1.6;
    }

    .resident-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: #fbfeff;
        margin-bottom: 22px;
    }

    .avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        background: linear-gradient(135deg, var(--blue), var(--green));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .resident-meta {
        min-width: 0;
    }

    .resident-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 4px 0;
    }

    .resident-email {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
        word-break: break-word;
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

    .form-group input[type="file"] {
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 10px 12px;
        width: 100%;
        box-sizing: border-box;
        font-size: 14px;
        background: white;
    }

    .form-group input[type="file"]::file-selector-button {
        background: var(--blue);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 12px;
        margin-right: 10px;
        cursor: pointer;
        font-weight: 600;
    }

    .hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 8px;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 6px;
    }

    .client-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 6px;
        display: none;
    }

    .client-error.visible {
        display: block;
    }

    .preview-wrap {
        margin-top: 14px;
        display: none;
        align-items: center;
        gap: 12px;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 10px;
        background: #f8fbff;
    }

    .preview-wrap.visible {
        display: flex;
    }

    .preview-wrap img {
        width: 72px;
        height: 72px;
        border-radius: 10px;
        object-fit: cover;
        background: white;
        border: 1px solid #d7e1ea;
    }

    .preview-meta {
        font-size: 12px;
        color: var(--text-muted);
    }

    .preview-meta strong {
        display: block;
        color: var(--text);
        margin-bottom: 4px;
        word-break: break-word;
    }

    .submit-btn {
        width: 100%;
        background: linear-gradient(135deg, #1a6fcc, #3a8a3f);
        border: none;
        border-radius: 12px;
        padding: 14px;
        color: white;
        font-size: 15px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        margin-top: 18px;
        transition: opacity 0.2s;
    }

    .submit-btn:hover {
        opacity: 0.92;
    }
</style>

<a href="{{ route($routePrefix . '.residents.index') }}" class="back-link">← Back to Residents</a>

<div class="page-card">
    <h1 class="page-title">Manage Resident Photo</h1>
    <p class="page-subtitle">Upload or replace the photo used on the resident's online ID.</p>

    <div class="notice">
        Only authorized users (Admin and Official) can upload or replace resident photos.
    </div>

    <div class="resident-card">
        <div class="avatar" id="residentPhotoCurrent">
            @if($resident->profile_photo_url)
                <img src="{{ $resident->profile_photo_url }}" alt="{{ $resident->getFullName() }} photo">
            @else
                {{ strtoupper(substr($resident->getFullName(), 0, 1)) }}
            @endif
        </div>
        <div class="resident-meta">
            <p class="resident-name">{{ $resident->getFullName() }}</p>
            <p class="resident-email">{{ $resident->email }}</p>
        </div>
    </div>

    <form id="residentPhotoForm" method="POST" action="{{ route($routePrefix . '.residents.photo.update', $resident->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="profile_photo">Resident Photo</label>
            <input
                type="file"
                id="profile_photo"
                name="profile_photo"
                accept=".jpg,.jpeg,.png,.webp"
                required
            >
            <p class="hint">Accepted formats: JPG, JPEG, PNG, WEBP. Max 5MB.</p>
            <div id="profilePhotoClientError" class="client-error" role="alert"></div>
            @error('profile_photo')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="preview-wrap" id="photoPreviewWrap">
            <img id="photoPreviewImage" alt="New photo preview">
            <div class="preview-meta">
                <strong id="photoPreviewName"></strong>
                <span>Selected photo preview</span>
            </div>
        </div>

        <button type="submit" class="submit-btn" id="residentPhotoSubmitBtn">Save Resident Photo</button>
    </form>
</div>

<script>
    (function () {
        const maxFileSize = 5 * 1024 * 1024;
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const input = document.getElementById('profile_photo');
        const form = document.getElementById('residentPhotoForm');
        const submitButton = document.getElementById('residentPhotoSubmitBtn');
        const clientError = document.getElementById('profilePhotoClientError');
        const previewWrap = document.getElementById('photoPreviewWrap');
        const previewImage = document.getElementById('photoPreviewImage');
        const previewName = document.getElementById('photoPreviewName');
        let objectUrl = null;

        if (!input) {
            return;
        }

        const clearClientError = () => {
            if (!clientError) {
                return;
            }

            clientError.textContent = '';
            clientError.classList.remove('visible');
        };

        const setClientError = (message) => {
            if (!clientError) {
                return;
            }

            clientError.textContent = message;
            clientError.classList.add('visible');
        };

        input.addEventListener('change', function () {
            const file = input.files && input.files[0] ? input.files[0] : null;
            clearClientError();

            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }

            if (!file) {
                previewWrap.classList.remove('visible');
                previewImage.removeAttribute('src');
                previewName.textContent = '';
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                input.value = '';
                setClientError('Invalid file type. Please upload JPG, JPEG, PNG, or WEBP.');
                previewWrap.classList.remove('visible');
                previewImage.removeAttribute('src');
                previewName.textContent = '';
                return;
            }

            if (file.size > maxFileSize) {
                input.value = '';
                setClientError('File is too large. Maximum allowed size is 5MB.');
                previewWrap.classList.remove('visible');
                previewImage.removeAttribute('src');
                previewName.textContent = '';
                return;
            }

            objectUrl = URL.createObjectURL(file);
            previewImage.src = objectUrl;
            previewName.textContent = file.name;
            previewWrap.classList.add('visible');
        });

        if (form) {
            form.addEventListener('submit', function () {
                clearClientError();

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Uploading...';
                }
            });
        }
    })();
</script>
@endsection
