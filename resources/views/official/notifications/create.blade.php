@extends('layouts.app')

@section('title', 'Send Alert')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        max-width: 700px;
        margin: 0 auto;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
    }

    .form-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 24px 0;
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
        min-height: 6em;
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

    .send-to-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 12px;
        display: block;
    }

    .recipient-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .recipient-card {
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 16px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
    }

    .recipient-card input[type="radio"] {
        width: auto;
        margin: 0;
        border: none;
        padding: 0;
    }

    .recipient-card.selected {
        border-color: var(--blue);
        background: var(--blue-light);
    }

    .recipient-content {
        flex: 1;
    }

    .recipient-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }

    .recipient-desc {
        font-size: 12px;
        color: var(--text-muted);
    }

    .resident-list-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        margin-top: 20px;
        margin-bottom: 8px;
        display: block;
    }

    .resident-list {
        max-height: 240px;
        overflow-y: auto;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 8px;
    }

    .resident-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
        user-select: none;
    }

    .resident-item:hover {
        background: var(--blue-light);
    }

    .resident-item input[type="checkbox"] {
        accent-color: var(--blue);
        cursor: pointer;
        margin: 0;
    }

    .resident-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--blue-light);
        color: var(--blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 11px;
        flex-shrink: 0;
    }

    .resident-name {
        font-size: 14px;
        font-weight: 500;
        color: var(--text);
    }

    .submit-btn {
        width: 100%;
        background: linear-gradient(135deg, #1a6fcc, #3a8a3f);
        border: none;
        border-radius: 12px;
        padding: 16px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        margin-top: 28px;
        transition: opacity 0.2s, transform 0.1s;
    }

    .submit-btn:hover {
        opacity: 0.92;
    }

    .submit-btn:active {
        transform: scale(0.99);
    }

    [x-cloak] {
        display: none;
    }
</style>

<div class="form-card">
    <h1 class="form-title">📢 Send Alert</h1>

    <form method="POST" action="{{ route('official.notifications.store') }}" x-data="{ recipientType: 'all', charCount: 0 }" x-cloak>
        @csrf

        <div class="form-group">
            <label for="title">Alert Title *</label>
            <input 
                type="text" 
                id="title" 
                name="title"
                value="{{ old('title') }}"
                placeholder="Important Update for All Residents"
                required
            >
            @error('title')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="message">Message *</label>
            <textarea 
                id="message" 
                name="message"
                placeholder="Compose your message here..."
                @input="charCount = $event.target.value.length"
                maxlength="500"
                required
            >{{ old('message') }}</textarea>
            <div class="char-counter"><span x-text="charCount"></span>/500</div>
            @error('message')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <label class="send-to-label">Send To</label>
        
        <div class="recipient-cards">
            <label class="recipient-card" :class="{ selected: recipientType === 'all' }">
                <input type="radio" name="recipient_type" value="all" x-model="recipientType">
                <div class="recipient-content">
                    <div class="recipient-title">All Residents</div>
                    <div class="recipient-desc">Send to every approved resident</div>
                </div>
            </label>

            <label class="recipient-card" :class="{ selected: recipientType === 'specific' }">
                <input type="radio" name="recipient_type" value="specific" x-model="recipientType">
                <div class="recipient-content">
                    <div class="recipient-title">Specific Residents</div>
                    <div class="recipient-desc">Choose individual residents</div>
                </div>
            </label>
        </div>

        <div x-show="recipientType === 'specific'" x-transition>
            <label class="resident-list-label">Select Residents</label>
            <div class="resident-list">
                @forelse($residents ?? [] as $resident)
                    <label class="resident-item">
                        <input type="checkbox" name="resident_ids[]" value="{{ $resident->id }}">
                        <div class="resident-avatar">{{ strtoupper(substr($resident->getFullName(), 0, 1)) }}</div>
                        <span class="resident-name">{{ $resident->getFullName() }}</span>
                    </label>
                @empty
                    <div style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 13px;">
                        No residents available
                    </div>
                @endforelse
            </div>
            @error('resident_ids')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="submit-btn">Send Alert →</button>
    </form>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection
