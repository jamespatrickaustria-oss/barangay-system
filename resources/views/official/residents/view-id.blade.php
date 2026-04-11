@extends('layouts.app')

@section('title', 'Resident ID')

@section('content')
@php
    $backPrefix = request()->segment(1) === 'admin' ? 'admin' : 'official';
@endphp

<style>
    .resident-id-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .resident-id-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .resident-id-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .resident-id-btn {
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #1f2937;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .resident-id-btn.primary {
        border-color: #2563eb;
        background: #2563eb;
        color: #ffffff;
    }

    .resident-id-btn:hover {
        opacity: 0.9;
    }

    .resident-id-page-wrapper {
        display: flex;
        flex-direction: column;
        gap: 18px;
        padding: 18px;
        border: 1px solid #d7e4f4;
        border-radius: 16px;
        background: linear-gradient(170deg, #f9fcff 0%, #edf5ff 100%);
    }

    .resident-id-flip-button {
        align-self: flex-start;
        background: linear-gradient(115deg, #1658a5 0%, #1d75b6 52%, #2f9b69 100%);
        color: #ffffff;
        border: none;
        border-radius: 11px;
        padding: 12px 22px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(26, 101, 169, 0.25);
        transition: transform 0.15s ease, box-shadow 0.2s ease, opacity 0.2s ease;
    }

    .resident-id-flip-button:hover {
        opacity: 0.95;
        box-shadow: 0 12px 26px rgba(24, 91, 150, 0.28);
    }

    .resident-id-flip-button:active {
        transform: translateY(1px);
    }

    @media (max-width: 768px) {
        .resident-id-page-wrapper {
            padding: 12px;
        }

        .resident-id-flip-button {
            align-self: stretch;
            width: 100%;
            text-align: center;
            padding: 14px;
        }
    }

    @media print {
        .resident-id-flip-button {
            display: none !important;
        }
    }
</style>

<div class="resident-id-header">
    <h1 class="resident-id-title">Resident Online ID</h1>

    <div class="resident-id-actions">
        <a href="{{ route($backPrefix . '.residents.index') }}" class="resident-id-btn">Back</a>
    </div>
</div>

<div class="resident-id-page-wrapper">
    <button id="residentIdFlipButton" class="resident-id-flip-button" type="button" onclick="toggleResidentIdCardFromButton()">Flip to Back</button>

    @include('partials.digital-id-card', [
        'user' => $resident,
        'onlineId' => $onlineId,
        'showInlineFlipControls' => false,
    ])
</div>

<script>
    function toggleResidentIdCardFromButton() {
        toggleResidentDigitalIdCard();

        const flipButton = document.getElementById('residentIdFlipButton');
        if (!flipButton) return;

        flipButton.textContent = isResidentDigitalIdCardFlipped() ? 'Flip to Front' : 'Flip to Back';
    }
</script>
@endsection
