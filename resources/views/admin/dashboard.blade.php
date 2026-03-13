@extends('layouts.app')

@section('title', 'Admin Dashboard')

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

    .welcome-section {
        margin-bottom: 48px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border-light);
    }

    .welcome-heading {
        font-size: 28px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 8px 0;
        letter-spacing: -0.5px;
    }

    .welcome-subtext {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 0;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 48px;
        flex-wrap: wrap;
    }

    .action-btn {
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

    .action-btn:hover {
        background: #2980b9;
    }

    .action-btn.secondary {
        background: var(--white);
        color: var(--accent);
        border: 1px solid var(--border-light);
    }

    .action-btn.secondary:hover {
        border-color: var(--accent);
        background: #f5f5f5;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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

    .official-cell {
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

    .official-info {
        display: flex;
        flex-direction: column;
    }

    .official-name {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0;
    }

    .official-email {
        font-size: 12px;
        color: var(--text-secondary);
        margin: 2px 0 0 0;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        background: rgba(39, 174, 96, 0.1);
        color: var(--success);
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

    /* Tablet and below */
    @media (max-width: 1024px) {
        .welcome-heading {
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
        .welcome-heading {
            font-size: 22px;
        }

        .welcome-subtext {
            font-size: 13px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            text-align: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
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
            min-width: 600px;
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

        .official-name {
            font-size: 13px;
        }

        .official-email {
            font-size: 11px;
        }
    }

    /* Small mobile devices */
    @media (max-width: 480px) {
        .welcome-section {
            margin-bottom: 32px;
            padding-bottom: 16px;
        }

        .welcome-heading {
            font-size: 20px;
        }

        .stats-grid,
        .section-header,
        .table-card {
            margin-bottom: 32px;
        }

        .stat-value {
            font-size: 22px;
        }

        .section-title {
            font-size: 15px;
        }
    }
</style>

<div class="welcome-section">
    <p class="welcome-heading">DASHBOARD</p>
    <p class="welcome-subtext">{{ date('l, F d, Y') }} • Admin Portal</p>
</div>

<!-- <div class="action-buttons">
    <a href="{{ route('admin.officials.create') }}" class="action-btn">Add Official</a>
    <a href="{{ route('admin.officials.index') }}" class="action-btn secondary">View All</a>
</div> -->

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $totalOfficials ?? 0 }}</div>
        <div class="stat-label">Officials</div>
    </div>

    <div class="stat-card">
        <div class="stat-value">{{ $totalResidents ?? 0 }}</div>
        <div class="stat-label">Residents</div>
    </div>

    <div class="stat-card">
        <div class="stat-value">{{ $pendingResidents ?? 0 }}</div>
        <div class="stat-label">Pending</div>
    </div>
</div>

@if(isset($recentOfficials) && $recentOfficials->count() > 0)
    <div class="section-header">
        <h2 class="section-title">Recently Approved</h2>
        <a href="{{ route('admin.officials.index') }}" class="view-all-link">View All</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Approved By</th>
                    <th>Date Approved</th>

                </tr>
            </thead>
            <tbody>
                @foreach($recentOfficials as $official)
                    <tr>
                        <td>
                            <div class="official-cell">
                                <div class="avatar">{{ strtoupper(substr($official->getFullName(), 0, 1)) }}</div>
                                <div class="official-info">
                                    <p class="official-name">{{ $official->getFullName() }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $official->email }}</td>
                        <td>
                            <span class="status-badge">Approved</span>
                        </td>
                        <td>    
                            @if($official->approved_by)
                                {{ $official->approver ? $official->approver->getFullName() : 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $official->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
