@extends('layouts.resident')

@section('title', 'My Online ID')

@section('content')
<style>
    .resident-id-page-header {
        margin-bottom: 22px;
    }

    .resident-id-page-title {
        font-size: 30px;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 6px;
    }

    .resident-id-page-subtitle {
        font-size: 15px;
        color: var(--gray-600);
        max-width: 760px;
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

    .resident-id-print-button {
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

    .resident-id-print-button:hover {
        opacity: 0.95;
        box-shadow: 0 12px 26px rgba(24, 91, 150, 0.28);
    }

    .resident-id-print-button:active {
        transform: translateY(1px);
    }

    @media (max-width: 768px) {
        .resident-id-page-title    { font-size: 22px; }
        .resident-id-page-subtitle { font-size: 13px; }

        .resident-id-page-wrapper {
            padding: 12px;
        }

        .resident-id-print-button {
            align-self: stretch;
            width: 100%;
            text-align: center;
            padding: 14px;
        }
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .digital-id-shell,
        .digital-id-shell * {
            visibility: visible;
        }

        .digital-id-shell {
            position: fixed;
            inset: 0;
            padding: 0;
            margin: auto;
            max-width: 900px;
            height: fit-content;
        }

        .resident-id-page-header,
        .resident-id-print-button {
            display: none !important;
        }
    }
</style>

<div class="resident-id-page-header">
    <h1 class="resident-id-page-title">My Barangay ID</h1>
    <p class="resident-id-page-subtitle">Your profile-synced digital ID card automatically uses your registration photo and details.</p>
</div>

<div class="resident-id-page-wrapper">
    <button class="resident-id-print-button" onclick="window.print()">Print My ID</button>

    @include('partials.digital-id-card', [
        'user' => $onlineId->user,
        'onlineId' => $onlineId,
    ])
</div>
@endsection
