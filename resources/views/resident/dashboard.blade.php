@extends('layouts.resident')

@section('title', 'My Dashboard')

@section('content')
<style>
    /* Hero Banner */
    .hero-banner {
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 40px;
        position: relative;
        background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue) 45%, #1a8fc7 100%);
        padding: 48px 44px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        box-shadow: 0 20px 60px rgba(26, 110, 199, 0.15);
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.01em;
    }

    .hero-content > p {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 18px;
        letter-spacing: 0.01em;
    }

    .status-label {
        display: inline-block;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.75);
        margin-right: 8px;
        font-weight: 500;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid rgba(34, 197, 94, 0.4);
        color: rgba(255, 255, 255, 0.95);
        border-radius: 24px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.03em;
    }

    .status-badge::before {
        content: '✓';
        font-weight: 700;
    }

    .hero-emoji {
        font-size: 120px;
        opacity: 0.12;
        position: absolute;
        right: 44px;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Stats Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 48px;
    }

    .stat-card {
        background: var(--white);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 24px rgba(26, 110, 199, 0.08);
        border: 1px solid var(--border);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 3px;
        width: 0;
        background: linear-gradient(90deg, var(--blue), var(--green));
        transition: width 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 48px rgba(26, 110, 199, 0.15);
    }

    .stat-card:hover::before {
        width: 100%;
    }

    .stat-card.green {
        border: 1px solid rgba(58, 125, 68, 0.15);
    }

    .stat-card.muted {
        border: 1px solid rgba(75, 94, 114, 0.15);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        background: var(--sky);
        color: var(--blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
        transition: all 0.3s;
    }


    .stat-card:hover .stat-icon {
        background: var(--blue);
        color: var(--white);
    }

    .stat-card.green .stat-icon {
        background: rgba(58, 125, 68, 0.15);
        color: var(--green);
    }

    .stat-card.green:hover .stat-icon {
        background: var(--green);
        color: var(--white);
    }

    .stat-card.muted .stat-icon {
        background: rgba(75, 94, 114, 0.15);
        color: var(--text-light);
    }

    .stat-card.muted:hover .stat-icon {
        background: var(--text-light);
        color: var(--white);
    }

    .stat-value {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 14px;
        font-weight: 500;
    }

    .stat-link {
        color: var(--blue);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .stat-link:hover {
        gap: 8px;
        color: var(--blue-dark);
    }

    /* Quick Access Section */
    .quick-access-header {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 24px;
        padding-left: 0;
        border-left: none;
    }

    .quick-access-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 48px;
    }

    .quick-card {
        background: var(--white);
        border: 2px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        text-decoration: none;
        color: inherit;
        display: block;
        position: relative;
        overflow: hidden;
    }

    .quick-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--blue), var(--green));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s;
    }

    .quick-card:hover {
        background: linear-gradient(135deg, var(--sky) 0%, rgba(168, 223, 245, 0.3) 100%);
        border-color: var(--blue);
        transform: translateY(-6px);
        box-shadow: 0 16px 48px rgba(26, 110, 199, 0.12);
    }

    .quick-card:hover::before {
        transform: scaleX(1);
    }

    .quick-card.green {
        border-color: rgba(58, 125, 68, 0.15);
    }

    .quick-card.green:hover {
        background: linear-gradient(135deg, rgba(58, 125, 68, 0.08) 0%, rgba(58, 125, 68, 0.04) 100%);
        border-color: var(--green);
    }

    .quick-icon {
        font-size: 40px;
        margin-bottom: 16px;
        display: block;
    }

    .quick-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }

    .quick-desc {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 18px;
        line-height: 1.5;
    }

    .quick-link {
        color: var(--blue);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .quick-card:hover .quick-link {
        color: var(--blue-dark);
        gap: 8px;
    }

    /* Announcements Section */
    .announcements-section {
        margin-top: 60px;
    }

    .announcements-title {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 32px;
    }

    .ann-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 48px;
        align-items: start;
    }

    .ann-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .ann-card {
        display: flex;
        gap: 20px;
        padding: 24px;
        border: 1px solid var(--border);
        border-radius: 18px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
        background: var(--white);
    }

    .ann-card:hover {
        background: var(--off-white);
        transform: translateX(6px);
        box-shadow: 0 8px 28px rgba(26, 110, 199, 0.1);
    }

    .ann-date {
        flex-shrink: 0;
        width: 68px;
        text-align: center;
        background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue) 100%);
        color: var(--white);
        border-radius: 14px;
        padding: 12px 8px;
        line-height: 1.1;
        box-shadow: 0 4px 12px rgba(26, 110, 199, 0.2);
    }

    .ann-date .day {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        font-weight: 700;
    }

    .ann-date .month {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        opacity: 0.9;
    }

    .ann-body {
        flex: 1;
    }

    .ann-tag {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 100px;
        margin-bottom: 10px;
    }

    .ann-tag.general {
        background: rgba(26, 110, 199, 0.12);
        color: var(--blue);
    }

    .ann-tag.health {
        background: rgba(34, 197, 94, 0.12);
        color: #16a34a;
    }

    .ann-tag.civil {
        background: rgba(26, 110, 199, 0.12);
        color: var(--blue);
    }

    .ann-tag.event {
        background: rgba(232, 184, 75, 0.18);
        color: #b45309;
    }

    .ann-tag.alert {
        background: rgba(239, 68, 68, 0.12);
        color: #dc2626;
    }

    .ann-body h4 {
        font-family: 'Playfair Display', serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }

    .ann-body p {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.6;
    }

    .ann-body .ann-meta {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 10px;
        font-weight: 500;
    }

    /* Quick Links Panel */
    .quick-panel {
        background: linear-gradient(160deg, var(--blue-dark) 0%, var(--blue) 100%);
        border-radius: 24px;
        padding: 32px 28px;
        color: var(--white);
        position: sticky;
        top: 92px;
        box-shadow: 0 12px 36px rgba(26, 110, 199, 0.15);
    }

    .quick-panel h3 {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .quick-panel > p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.75);
        margin-bottom: 24px;
        line-height: 1.5;
    }

    .quick-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .quick-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        text-decoration: none;
        color: var(--white);
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .quick-link:hover {
        background: rgba(255, 255, 255, 0.18);
        transform: translateX(4px);
    }

    .quick-link .ql-icon {
        font-size: 18px;
        flex-shrink: 0;
    }

    .quick-link .ql-arrow {
        margin-left: auto;
        opacity: 0.6;
        font-size: 12px;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .quick-access-grid {
            grid-template-columns: 1fr;
        }

        .ann-layout {
            grid-template-columns: 1fr;
        }

        .quick-panel {
            position: relative;
            top: 0;
        }
    }

    @media (max-width: 768px) {
        .hero-banner {
            flex-direction: column;
            text-align: center;
            padding: 32px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .hero-emoji {
            display: none;
        }

        .hero-content h1 {
            font-size: 22px;
        }

        .hero-content > p {
            font-size: 14px;
            margin-bottom: 14px;
        }

        .stats-row {
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-bottom: 28px;
        }

        .stat-card {
            padding: 20px 16px;
            border-radius: 16px;
        }

        .stat-value {
            font-size: 28px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .quick-access-grid {
            grid-template-columns: 1fr;
            gap: 14px;
            margin-bottom: 32px;
        }

        .quick-card {
            padding: 22px 20px;
            border-radius: 16px;
        }

        .quick-icon { font-size: 32px; margin-bottom: 10px; }

        .announcements-section { margin-top: 40px; }

        .announcements-title { font-size: 20px; margin-bottom: 20px; }

        .ann-card {
            gap: 14px;
            padding: 16px;
            border-radius: 14px;
        }

        .ann-card:hover { transform: none; }

        .ann-date {
            width: 56px;
            padding: 10px 6px;
        }

        .ann-date .day  { font-size: 17px; }
        .ann-date .month { font-size: 10px; }

        .ann-body h4 { font-size: 14px; }
        .ann-body p  { font-size: 13px; }

        .quick-panel {
            padding: 24px 20px;
            border-radius: 16px;
        }

        .quick-access-header { font-size: 18px; margin-bottom: 16px; }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .hero-content h1 { font-size: 19px; }

        .stat-card {
            padding: 18px 14px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon { margin-bottom: 0; flex-shrink: 0; }

        .stat-value { font-size: 24px; margin-bottom: 2px; }
        .stat-label { margin-bottom: 8px; }

        .ann-card { flex-direction: column; gap: 12px; }

        .ann-date {
            width: 100%;
            border-radius: 10px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: left;
        }

        .ann-date .day  { font-size: 20px; }
        .ann-date .month { font-size: 12px; }
    }
</style>

<div class="hero-banner">
    <div class="hero-content">
        <h1>Good day, {{ auth()->user()->getFullName() }}! 👋</h1>
        <p>Welcome to your Barangay Resident Portal</p>
        <div>
            <span class="status-label">Account Status:</span>
            <span class="status-badge">✓ APPROVED</span>
        </div>
    </div>
    <div class="hero-emoji">🏛️</div>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon">🔔</div>
        <div class="stat-value">{{ $unread ?? 0 }}</div>
        <div class="stat-label">Unread Notifications</div>
        <a href="{{ route('resident.notifications') }}" class="stat-link">View all →</a>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value" style="font-size: 20px; font-weight: 700;">APPROVED</div>
        <div class="stat-label">Account Status</div>
    </div>

    <div class="stat-card muted">
        <div class="stat-icon">📅</div>
        <div class="stat-value" style="font-size: 18px;">{{ auth()->user()->created_at->format('M d, Y') }}</div>
        <div class="stat-label">Member Since</div>
    </div>
</div>

<div class="quick-access-header">Quick Access</div>
<div class="quick-access-grid">
    <a href="{{ route('resident.online-id') }}" class="quick-card">
        <div class="quick-icon">🪪</div>
        <div class="quick-title">My Online ID</div>
        <div class="quick-desc">View and print your official Barangay ID</div>
        <div class="quick-link">View ID →</div>
    </a>

    <a href="{{ route('resident.notifications') }}" class="quick-card green">
        <div class="quick-icon">🔔</div>
        <div class="quick-title">Notifications</div>
        <div class="quick-desc">Stay updated with important announcements</div>
        <div class="quick-link">View Notifications →</div>
    </a>
</div>

@if(isset($announcements) && $announcements->count() > 0)
<div class="announcements-section">
    <h2 class="announcements-title">Announcements & Updates</h2>
    <div class="ann-layout">
        <div class="ann-list">
            @foreach($announcements as $announcement)
                <div class="ann-card">
                    <div class="ann-date">
                        <div class="day">{{ $announcement->published_at->format('d') }}</div>
                        <div class="month">{{ $announcement->published_at->format('M') }}</div>
                    </div>
                    <div class="ann-body">
                        <div class="ann-tag general">Announcement</div>
                        <h4>{{ $announcement->title }}</h4>
                        <p>{{ Str::limit($announcement->content, 120) }}</p>
                        <div class="ann-meta">📅 {{ $announcement->published_at->format('M d, Y') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="quick-panel">
            <h3>Quick Links</h3>
            <p>Access important services and information</p>
            <div class="quick-links">
                <a href="{{ route('resident.online-id') }}" class="quick-link">
                    <span class="ql-icon">🪪</span>
                    <span>My Online ID</span>
                    <span class="ql-arrow">→</span>
                </a>
                <a href="{{ route('resident.notifications') }}" class="quick-link">
                    <span class="ql-icon">🔔</span>
                    <span>Notifications</span>
                    <span class="ql-arrow">→</span>
                </a>
                <a href="{{ route('resident.profile') }}" class="quick-link">
                    <span class="ql-icon">👤</span>
                    <span>My Profile</span>
                    <span class="ql-arrow">→</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
