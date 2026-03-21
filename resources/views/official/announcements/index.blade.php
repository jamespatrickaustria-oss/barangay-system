@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
@php
    $routePrefix = request()->segment(1) === 'admin' ? 'admin' : 'official';
@endphp
<style>
    :root {
        --blue: #1a6fcc;
        --green: #3a8a3f;
        --green-dark: #2d6a31;
        --blue-light: #daf0fa;
        --green-light: #e8f5e9;
        --surface: #f0f9ff;
        --text: #0d1b2a;
        --text-muted: #5a7a9a;
        --border: #c8e4f8;
        --danger: #dc3545;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .create-btn {
        background: var(--green);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
        transition: opacity 0.2s;
        display: inline-block;
    }

    .create-btn:hover {
        opacity: 0.9;
    }

    .announcement-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
        margin-bottom: 20px;
        border-left: 4px solid var(--blue);
        transition: all 0.2s;
    }

    .announcement-card:hover {
        box-shadow: 0 4px 16px rgba(26, 111, 204, 0.12);
    }

    .announcement-card.inactive {
        opacity: 0.7;
        border-left-color: #999;
    }

    .announcement-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .announcement-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        flex: 1;
    }

    .announcement-meta {
        display: flex;
        gap: 16px;
        margin-bottom: 12px;
        font-size: 12px;
        color: var(--text-muted);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: var(--green-light);
        color: var(--green);
    }

    .status-inactive {
        background: #f0f0f0;
        color: #666;
    }

    .status-expired {
        background: #fce8e6;
        color: #b91c1c;
    }

    .announcement-content {
        color: var(--text);
        margin-bottom: 16px;
        line-height: 1.6;
    }

    .announcement-content p {
        margin: 0;
        max-height: 80px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .announcement-photo {
        margin-bottom: 14px;
    }

    .announcement-photo img {
        width: 100%;
        max-width: 380px;
        border-radius: 12px;
        border: 1px solid var(--border);
        object-fit: cover;
    }

    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-btn {
        border: 1px solid;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        background: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .edit-btn {
        border-color: var(--blue);
        color: var(--blue);
    }

    .edit-btn:hover {
        background: var(--blue-light);
    }

    .toggle-btn {
        border-color: var(--green);
        color: var(--green);
    }

    .toggle-btn:hover {
        background: var(--green-light);
    }

    .toggle-btn.inactive {
        border-color: var(--green);
    }

    .delete-btn {
        border-color: var(--danger);
        color: var(--danger);
    }

    .delete-btn:hover {
        background: #fce8e6;
    }

    .empty-message {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
        color: var(--text-muted);
    }

    .empty-message p {
        font-size: 16px;
        margin: 12px 0;
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 28px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        color: var(--blue);
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: var(--blue);
        color: white;
        border-color: var(--blue);
    }

    .pagination .active span {
        background: var(--blue);
        color: white;
        border-color: var(--blue);
    }

    .pagination span:not(.page-item) {
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }

        .announcement-header {
            flex-direction: column;
        }

        .actions {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Announcements</h1>
    <a href="{{ route($routePrefix . '.announcements.create') }}" class="create-btn">+ Create Announcement</a>
</div>

@if($announcements->count() > 0)
    @foreach($announcements as $announcement)
        <div class="announcement-card {{ !$announcement->is_active ? 'inactive' : '' }}">
            <div class="announcement-header">
                <div>
                    <h2 class="announcement-title">{{ $announcement->title }}</h2>
                    <div class="announcement-meta">
                        <div class="meta-item">📅 {{ optional($announcement->published_at)->format('M d, Y h:i A') ?? optional($announcement->created_at)->format('M d, Y h:i A') }}</div>
                        <div class="meta-item">👤 {{ optional($announcement->user)->getFullName() ?? 'Unknown Author' }}</div>
                        @if($announcement->expires_at)
                            <div class="meta-item">⏰ Expires: {{ $announcement->expires_at->format('M d, Y') }}</div>
                        @endif
                    </div>
                </div>
                <div>
                    @if($announcement->is_active)
                        @if($announcement->expires_at && $announcement->expires_at->isPast())
                            <span class="status-badge status-expired">Expired</span>
                        @else
                            <span class="status-badge status-active">Active</span>
                        @endif
                    @else
                        <span class="status-badge status-inactive">Inactive</span>
                    @endif
                </div>
            </div>

            @if($announcement->photo_path)
                <div class="announcement-photo">
                    <img src="{{ asset('storage/' . $announcement->photo_path) }}" alt="Announcement image">
                </div>
            @endif

            <div class="announcement-content">
                <p>{{ $announcement->content }}</p>
            </div>

            <div class="actions">
                <a href="{{ route($routePrefix . '.announcements.edit', $announcement->id) }}" class="action-btn edit-btn">
                    Edit
                </a>
                <form method="POST" action="{{ route($routePrefix . '.announcements.toggle', $announcement->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="action-btn toggle-btn {{ !$announcement->is_active ? 'inactive' : '' }}">
                        {{ $announcement->is_active ? 'Hide' : 'Show' }}
                    </button>
                </form>
                <form method="POST" action="{{ route($routePrefix . '.announcements.destroy', $announcement->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete-btn">Delete</button>
                </form>
            </div>
        </div>
    @endforeach

    <div class="pagination">
        {{ $announcements->links() }}
    </div>
@else
    <div class="empty-message">
        <div class="empty-icon">📭</div>
        <p>No announcements yet</p>
        <p style="font-size: 13px; color: var(--text-muted);">
            <a href="{{ route($routePrefix . '.announcements.create') }}" style="color: var(--blue); font-weight: 700; text-decoration: none;">Create your first announcement</a> to share updates with residents
        </p>
    </div>
@endif

@endsection
