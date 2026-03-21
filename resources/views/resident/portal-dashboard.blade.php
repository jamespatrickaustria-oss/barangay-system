@extends('layouts.resident')

@section('title', 'Home')

@section('content')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #2563eb 0%, #10b981 100%);
        border-radius: 20px;
        padding: 48px 40px;
        color: white;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .welcome-banner::before {
        content: '🏛️';
        position: absolute;
        right: 40px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 120px;
        opacity: 0.1;
    }

    .welcome-title {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .welcome-subtitle {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 24px;
    }

    .welcome-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 16px;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 600;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 48px;
    }

    .service-card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .service-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary);
    }

    .service-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 16px;
    }

    .service-card.primary .service-icon {
        background: var(--primary-light);
    }

    .service-card.secondary .service-icon {
        background: var(--secondary-light);
    }

    .service-card.accent .service-icon {
        background: var(--accent-light);
    }

    .service-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 8px;
    }

    .service-description {
        font-size: 14px;
        color: var(--gray-600);
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .service-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
    }

    .service-card.secondary .service-action {
        color: var(--secondary);
    }

    .service-card.accent .service-action {
        color: var(--accent);
    }

    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 48px;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .info-card-icon {
        font-size: 32px;
        margin-bottom: 12px;
    }

    .info-card-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 4px;
    }

    .info-card-label {
        font-size: 13px;
        color: var(--gray-500);
        font-weight: 500;
    }

    .announcements-section {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
    }

    .announcement-item {
        padding: 20px;
        border-left: 4px solid var(--accent);
        background: var(--gray-50);
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .announcement-item:last-child {
        margin-bottom: 0;
    }

    .announcement-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 8px;
    }

    .announcement-content {
        font-size: 14px;
        color: var(--gray-700);
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .announcement-date {
        font-size: 12px;
        color: var(--gray-500);
    }

    .no-announcements {
        text-align: center;
        padding: 48px 20px;
        color: var(--gray-500);
    }

    .no-announcements-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <h1 class="welcome-title">Welcome, {{ auth()->user()->getFullName() }}! 👋</h1>
    <p class="welcome-subtitle">Stay updated with community announcements</p>
    <div class="welcome-status">
        
        <span> 👤 Member Since: </span>
        <span>{{ auth()->user()->created_at->format('M d, Y') }} </span>
        
    </div>
</div>

<!-- Quick Info Cards -->
<!-- <div class="info-cards">
    <div class="info-card">
        <div class="info-card-icon">🔔</div>
        <div class="info-card-value">{{ $unread ?? 0 }}</div>
        <div class="info-card-label">New Notifications</div>
    </div>

    <div class="info-card">
        <div class="info-card-icon">✅</div>
        <div class="info-card-value">Active</div>
        <div class="info-card-label">Account Status</div>
    </div>

    <div class="info-card">
        <div class="info-card-icon">📅</div>
        <div class="info-card-value">{{ auth()->user()->created_at->format('M d, Y') }}</div>
        <div class="info-card-label">Member Since</div>
    </div>
</div>


SERVICES --->
<!-- <h2 class="section-title">
    <span>Available Services</span>
</h2>

<div class="services-grid">
    <a href="{{ route('resident.online-id') }}" class="service-card primary">
        <div class="service-icon">🪪</div>
        <h3 class="service-title">Barangay Online ID</h3>
        <p class="service-description">View, download, and print your official barangay identification card</p>
        <span class="service-action">View My ID →</span>
    </a>

    <a href="{{ route('resident.notifications') }}" class="service-card secondary">
        <div class="service-icon">📢</div>
        <h3 class="service-title">Community Announcements</h3>
        <p class="service-description">Stay informed about important barangay announcements and updates</p>
        <span class="service-action">View Announcements →</span>
    </a>

    <a href="{{ route('resident.profile') }}" class="service-card accent">
        <div class="service-icon">👤</div>
        <h3 class="service-title">My Profile</h3>
        <p class="service-description">Update your personal information and manage account settings</p>
        <span class="service-action">Manage Profile →</span>
    </a> -->
<!-- </div> --> 

<!-- Recent Announcements -->
@if(isset($announcements) && $announcements->count() > 0)
    <h2 class="section-title">
        <span>📣</span>
        <span>Latest Announcements</span>
    </h2>

    <div class="announcements-section">
        @foreach($announcements as $announcement)
            <div class="announcement-item">
                <h3 class="announcement-title">{{ $announcement->title }}</h3>
                <p class="announcement-content">{{ Str::limit($announcement->content, 200) }}</p>
                <p class="announcement-date">📅 Published: {{ optional($announcement->published_at)->format('F d, Y') ?? optional($announcement->created_at)->format('F d, Y') }}</p>
            </div>
        @endforeach

        @if($announcements->count() >= 5)
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('resident.notifications') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">
                    View All Announcements →
                </a>
            </div>
        @endif
    </div>
@else
    <h2 class="section-title">
        <span>📣</span>
        <span>Latest Announcements</span>
    </h2>

    <div class="announcements-section">
        <div class="no-announcements">
            <div class="no-announcements-icon">📭</div>
            <p>No announcements at this time</p>
        </div>
    </div>
@endif

@endsection
