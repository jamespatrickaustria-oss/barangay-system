@extends('layouts.app')

@section('title', 'Official Dashboard')

@section('content')
<style>
    :root {
        --primary: #2c3e50;
        --secondary: #34495e;
        --accent: #3498db;
        --success: #27ae60;
        --warning: #f39c12;
        --text-primary: #1a1a1a;
        --text-secondary: #666666;
        --bg-light: #f8f9fa;
        --border-light: #e0e0e0;
        --white: #ffffff;
    }

    * {
        box-sizing: border-box;
    }

    .welcome-bar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 48px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border-light);
    }

    .welcome-left h1 {
        font-size: 28px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 8px 0;
        letter-spacing: -0.5px;
    }

    .welcome-left p {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 0;
    }

    .welcome-date {
        font-size: 13px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 48px;
    }

    .stat-card {
        background: var(--white);
        border-radius: 8px;
        padding: 24px;
        border: 1px solid var(--border-light);
        transition: all 0.25s ease;
    }

    .stat-card:hover {
        border-color: var(--accent);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .stat-value {
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-secondary);
        font-weight: 500;
        margin-bottom: 12px;
    }

    .stat-link {
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        color: var(--accent);
        transition: color 0.25s ease;
    }

    .stat-link:hover {
        color: #2980b9;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: -0.3px;
    }

    .view-all-link {
        color: var(--accent);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: color 0.25s ease;
    }

    .view-all-link:hover {
        color: #2980b9;
    }

    .table-card {
        background: var(--white);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border-light);
        margin-bottom: 48px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-light);
    }

    th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
        font-weight: 600;
    }

    td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-light);
        font-size: 14px;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: var(--bg-light);
    }

    .resident-cell {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--bg-light);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 13px;
    }

    .resident-info {
        display: flex;
        flex-direction: column;
    }

    .resident-name {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0;
    }

    .resident-email {
        font-size: 12px;
        color: var(--text-secondary);
        margin: 2px 0 0 0;
    }

    .actions-cell {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        border: none;
        border-radius: 4px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        display: inline-block;
    }

    .approve-btn {
        background: var(--success);
        color: var(--white);
    }

    .approve-btn:hover {
        background: #229954;
    }

    .reject-btn {
        background: #f8f9fa;
        color: #e74c3c;
        border: 1px solid #e0e0e0;
    }

    .reject-btn:hover {
        background: #fde8e8;
    }

    .empty-message {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
        font-size: 14px;
    }

    .empty-message p {
        margin: 0;
    }

    .empty-message a {
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
    }

    .empty-message a:hover {
        text-decoration: underline;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 48px;
        flex-wrap: wrap;
    }

    .action-btn-link {
        background: var(--accent);
        color: var(--white);
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.25s ease;
        display: inline-block;
    }

    .action-btn-link:hover {
        background: #2980b9;
    }

    .action-btn-link.secondary {
        background: var(--white);
        color: var(--accent);
        border: 1px solid var(--border-light);
    }

    .action-btn-link.secondary:hover {
        border-color: var(--accent);
        background: #f5f5f5;
    }

    /* Tablet and below */
    @media (max-width: 1024px) {
        .welcome-left h1 {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-value {
            font-size: 28px;
        }

        th, td {
            padding: 12px 16px;
        }
    }

    /* Mobile devices */
    @media (max-width: 768px) {
        .welcome-bar {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 32px;
        }

        .welcome-left h1 {
            font-size: 22px;
        }

        .welcome-left p {
            font-size: 13px;
        }

        .welcome-date {
            font-size: 12px;
        }

        .action-buttons {
            flex-direction: column;
            margin-bottom: 32px;
        }

        .action-btn-link {
            width: 100%;
            text-align: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card {
            padding: 16px;
        }

        .stat-value {
            font-size: 24px;
        }

        .stat-label {
            font-size: 12px;
        }

        .stat-link {
            font-size: 11px;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .table-card {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            min-width: 650px;
        }

        th, td {
            padding: 10px 12px;
            font-size: 13px;
        }

        th {
            font-size: 11px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .resident-name {
            font-size: 13px;
        }

        .resident-email {
            font-size: 11px;
        }

        .action-btn {
            padding: 6px 10px;
            font-size: 11px;
        }
    }

    /* Small mobile devices */
    @media (max-width: 480px) {
        .welcome-bar {
            margin-bottom: 24px;
        }

        .welcome-left h1 {
            font-size: 20px;
        }

        .action-buttons,
        .stats-grid,
        .section-header,
        .table-card {
            margin-bottom: 24px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-value {
            font-size: 22px;
        }

        .section-title {
            font-size: 15px;
        }

        .actions-cell {
            flex-direction: column;
            gap: 6px;
        }

        .action-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="welcome-bar">
    <div class="welcome-left">
        <h1>Dashboard</h1>
        <p>Manage residents, announcements, and approvals</p>
    </div>
    <div class="welcome-date">{{ date('l, F d, Y') }}</div>
</div>

<div class="action-buttons">
    <a href="{{ route('official.residents.index') }}" class="action-btn-link">Manage Residents</a>
    <a href="{{ route('official.announcements.index') }}" class="action-btn-link">Announcements</a>
    <a href="{{ route('official.notifications.create') }}" class="action-btn-link secondary">Send Notification</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $pending ?? 0 }}</div>
        <div class="stat-label">Pending</div>
        @if(($pending ?? 0) > 0)
            <a href="{{ route('official.residents.index', ['status' => 'pending']) }}" class="stat-link">Review Now →</a>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-value">{{ $approved ?? 0 }}</div>
        <div class="stat-label">Approved</div>
    </div>

    <div class="stat-card">
        <div class="stat-value">{{ $total ?? 0 }}</div>
        <div class="stat-label">Total Residents</div>
    </div>

    <div class="stat-card">
        <div class="stat-value">{{ $notificationCount ?? 0 }}</div>
        <div class="stat-label">Announcements</div>
    </div>
</div>

@if(isset($pendingResidents) && $pendingResidents->count() > 0)
    <div class="section-header">
        <h2 class="section-title">Pending Approvals</h2>
        <a href="{{ route('official.residents.index', ['status' => 'pending']) }}" class="view-all-link">View All</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingResidents as $resident)
                    <tr>
                        <td>
                            <div class="resident-cell">
                                <div class="avatar">{{ strtoupper(substr($resident->getFullName(), 0, 1)) }}</div>
                                <div class="resident-info">
                                    <p class="resident-name">{{ $resident->getFullName() }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $resident->email }}</td>
                        <td>{{ $resident->phone ?? 'N/A' }}</td>
                        <td>{{ $resident->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="actions-cell">
                                <form method="POST" action="{{ route('official.residents.approve', $resident->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="action-btn approve-btn">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('official.residents.reject', $resident->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="action-btn reject-btn" onclick="return confirm('Are you sure?')">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @if(($pending ?? 0) == 0)
        <div class="section-header">
            <h2 class="section-title">All Caught Up</h2>
        </div>
        <div class="empty-message">
            <p>No pending approvals. You can now <a href="{{ route('official.notifications.create') }}">post announcements</a> or manage residents.</p>
        </div>
    @endif
@endif

<div class="section-header">
    <h2 class="section-title">Resident ID List</h2>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Resident</th>
                <th>Account No.</th>
                <th>Issued At</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($recentResidentIds ?? collect()) as $id)
                <tr>
                    <td>{{ $id->user?->getFullName() ?? 'N/A' }}</td>
                    <td>{{ $id->user?->account_number ?? 'N/A' }}</td>
                    <td>{{ optional($id->issued_at)->format('M d, Y h:i A') ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="empty-message">
                        <p>No resident IDs found.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
