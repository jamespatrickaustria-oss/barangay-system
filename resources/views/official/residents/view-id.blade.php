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
</style>

<div class="resident-id-header">
    <h1 class="resident-id-title">Resident Online ID</h1>

    <div class="resident-id-actions">
        <a href="{{ route($backPrefix . '.residents.index') }}" class="resident-id-btn">Back</a>
        <button class="resident-id-btn primary" onclick="window.print()">Print ID</button>
    </div>
</div>

@include('partials.digital-id-card', [
    'user' => $resident,
    'onlineId' => $onlineId,
])
@endsection
