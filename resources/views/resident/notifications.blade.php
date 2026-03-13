@extends('layouts.resident')

@section('title', 'Announcements')

@section('content')
<style>
    .page-header {
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 8px;
    }

    .page-subtitle {
        font-size: 16px;
        color: var(--gray-600);
    }

    .stats-bar {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-700);
    }

    .stat-badge.unread {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Tabs */
    .tabs-bar {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        border-bottom: 2px solid var(--gray-200);
        padding-bottom: 0;
    }

    .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        font-size: 15px;
        font-weight: 600;
        color: var(--gray-500);
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: var(--primary);
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        background: var(--gray-100);
        color: var(--gray-600);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .tab-count.unread {
        background: var(--primary);
        color: white;
    }

    .alerts-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .badge-new {
        color: var(--primary);
        font-weight: 700;
        font-size: 12px;
    }

    .notif-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 16px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s;
    }

    .notif-card:hover {
        box-shadow: var(--shadow);
        border-color: var(--primary);
    }

    .notif-card.unread {
        border-left: 4px solid var(--primary);
    }

    .notif-card.read {
        opacity: 0.8;
    }

    .notif-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 12px;
    }

    .notif-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .notif-card.read .notif-icon {
        background: var(--gray-100);
        color: var(--gray-400);
    }

    .notif-content {
        flex: 1;
    }

    .notif-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 4px;
    }

    .notif-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: var(--gray-500);
        margin-bottom: 12px;
    }

    .notif-message {
        font-size: 15px;
        color: var(--gray-700);
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .notif-photo {
        margin: 8px 0 14px;
    }

    .notif-photo img {
        width: 100%;
        max-width: 420px;
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        object-fit: cover;
        box-shadow: var(--shadow-sm);
    }

    .notif-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sender-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--gray-100);
        color: var(--gray-700);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
    }

    .mark-read-btn {
        border: none;
        background: var(--primary);
        color: white;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .mark-read-btn:hover {
        background: var(--primary-dark);
    }

    .mark-all-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .mark-all-btn:hover {
        background: var(--primary-dark);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        border: 1px solid var(--gray-200);
    }

    .empty-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 8px;
    }

    .empty-subtitle {
        font-size: 14px;
        color: var(--gray-500);
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 32px;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        padding: 10px 16px;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        color: var(--gray-700);
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination .active span {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* Mobile */
    @media (max-width: 768px) {
        .page-title  { font-size: 22px; }
        .page-subtitle { font-size: 14px; }

        .stats-bar {
            flex-wrap: wrap;
            gap: 10px;
        }

        .stat-badge { font-size: 13px; padding: 8px 12px; }

        .mark-all-btn {
            width: 100%;
            padding: 10px 20px;
            font-size: 13px;
        }

        .notif-card {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .notif-header { gap: 12px; }

        .notif-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
            border-radius: 10px;
        }

        .notif-title    { font-size: 15px; }
        .notif-message  { font-size: 14px; }

        .notif-meta {
            flex-wrap: wrap;
            gap: 6px;
            font-size: 12px;
        }

        .notif-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .mark-read-btn {
            width: 100%;
            text-align: center;
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        .page-title  { font-size: 19px; }

        .notif-header { flex-direction: column; gap: 10px; }

        .notif-icon { display: none; }

        .notif-card.unread { border-left: 3px solid var(--primary); }
    }
</style>

<div class="page-header">
    @php($philippineTz = 'Asia/Manila')
    <h1 class="page-title">Notifications</h1>
    <p class="page-subtitle">Stay updated with barangay announcements and personal alerts</p>
</div>

{{-- Tab Navigation --}}
<div class="tabs-bar">
    <button class="tab-btn active" onclick="switchTab('announcements', this)">
        📢 Announcements
    </button>
    <button class="tab-btn" onclick="switchTab('alerts', this)">
        🔔 Alerts
    </button>
</div>

{{-- ===================== ANNOUNCEMENTS TAB ===================== --}}
<div id="tab-announcements" class="tab-panel">
    @if($announcements->count() > 0)
        @foreach($announcements as $announcement)
            <div class="notif-card">
                <div class="notif-header">
                    <div class="notif-icon">📣</div>
                    <div class="notif-content">
                        <h3 class="notif-title">{{ $announcement->title }}</h3>
                        <div class="notif-meta">
                            <span>📅 {{ ($announcement->published_at ?? $announcement->created_at)?->timezone($philippineTz)->format('F d, Y g:i A') }}</span>
                        </div>

                        @if($announcement->photo_path)
                            <div class="notif-photo">
                                <img src="{{ asset('storage/' . $announcement->photo_path) }}" alt="Announcement photo">
                            </div>
                        @endif

                        <p class="notif-message">{{ $announcement->content }}</p>
                        <div class="notif-footer">
                            <span class="sender-tag">
                                <span>👤</span>
                                <span>{{ optional($announcement->user)->getFullName() ?? 'Barangay Official' }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="pagination">
            {{ $announcements->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <p class="empty-title">No announcements yet</p>
            <p class="empty-subtitle">Check back later for important community updates</p>
        </div>
    @endif
</div>

{{-- ===================== ALERTS TAB ===================== --}}
<div id="tab-alerts" class="tab-panel" style="display:none;">
    @if($notifications->count() > 0)
        <div class="alerts-toolbar">
            @if(($unread ?? 0) > 0)
                <form method="POST" action="{{ route('resident.notifications.read-all') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="mark-all-btn">✓ Mark all as read</button>
                </form>
            @endif
            <div class="stat-badge {{ ($unread ?? 0) > 0 ? 'unread' : '' }}" style="margin-left: auto;">
                <span>🔔</span>
                <span>{{ ($unread ?? 0) > 0 ? $unread . ' Unread' : 'All read' }}</span>
            </div>
        </div>

        @foreach($notifications as $notification)
            <div class="notif-card {{ $notification->is_read ? 'read' : 'unread' }}" id="notif-{{ $notification->id }}">
                <div class="notif-header">
                    <div class="notif-icon">{{ $notification->is_read ? '📩' : '📬' }}</div>
                    <div class="notif-content">
                        <h3 class="notif-title">{{ $notification->title }}</h3>
                        <div class="notif-meta">
                            <span>📅 {{ $notification->created_at->timezone($philippineTz)->format('F d, Y g:i A') }}</span>
                            @if(!$notification->is_read)
                                <span class="badge-new">● New</span>
                            @endif
                        </div>
                        <p class="notif-message">{{ $notification->message }}</p>
                        <div class="notif-footer">
                            <span class="sender-tag">
                                <span>👤</span>
                                <span>{{ optional($notification->sender)->getFullName() ?? 'Barangay Office' }}</span>
                            </span>
                            @if(!$notification->is_read)
                                <button
                                    class="mark-read-btn"
                                    onclick="markRead({{ $notification->id }}, this)"
                                >✓ Mark as read</button>
                            @else
                                <span style="font-size:13px; color: var(--gray-400);">✓ Read</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="pagination">
            {{ $notifications->appends(['tab' => 'alerts'])->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">🔕</div>
            <p class="empty-title">No alerts yet</p>
            <p class="empty-subtitle">Personal alerts from barangay officials will appear here</p>
        </div>
    @endif
</div>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).style.display = 'block';
    btn.classList.add('active');
}

function markRead(id, btn) {
    fetch(`/resident/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById('notif-' + id);
            card.classList.remove('unread');
            card.classList.add('read');
            card.querySelector('.notif-icon').textContent = '📩';
            btn.replaceWith(Object.assign(document.createElement('span'), {
                textContent: '✓ Read',
                style: 'font-size:13px; color: var(--gray-400);'
            }));
            // Remove "● New" badge
            const badge = card.querySelector('.badge-new');
            if (badge) badge.remove();

        }
    });
}

// Restore tab from URL param on page load
(function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('tab') === 'alerts') {
        switchTab('alerts', document.querySelectorAll('.tab-btn')[1]);
    }
})();
</script>

@endsection
