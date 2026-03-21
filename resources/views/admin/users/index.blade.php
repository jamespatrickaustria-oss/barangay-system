@extends('layouts.app')

@section('title', 'User Requests')

@section('content')
<style>
    :root {
        --blue: #1a6fcc;
        --green: #3a8a3f;
        --orange: #f59e0b;
        --red: #dc2626;
        --surface: #f0f9ff;
        --text: #0d1b2a;
        --text-muted: #5a7a9a;
        --border: #c8e4f8;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .filter-tabs {
        display: flex;
        gap: 8px;
        background: #ffffff;
        padding: 4px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(26, 111, 204, 0.08);
    }

    .filter-tab {
        padding: 8px 16px;
        border: none;
        background: transparent;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--text-muted);
        text-decoration: none;
    }

    .filter-tab.active {
        background: var(--blue);
        color: #ffffff;
    }

    .filter-tab:not(.active):hover {
        background: var(--surface);
        color: var(--blue);
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(26, 111, 204, 0.08);
    }

    .stat-label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text);
    }

    .stat-pending { border-left: 4px solid var(--orange); }
    .stat-approved { border-left: 4px solid var(--green); }
    .stat-rejected { border-left: 4px solid var(--red); }

    .table-card {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px;
    }

    thead {
        background: var(--surface);
    }

    th {
        padding: 16px;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        font-weight: 700;
        border-bottom: 2px solid var(--border);
        white-space: nowrap;
    }

    td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: var(--text);
        vertical-align: middle;
    }

    tbody tr {
        transition: all 0.2s;
    }

    tbody tr:hover {
        background: #f8fbff;
        transform: scale(1.002);
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--blue), var(--green));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }

    .user-email {
        font-size: 12px;
        color: var(--text-muted);
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .role-resident {
        background: #e3f2fd;
        color: #1565c0;
    }

    .role-official {
        background: #fff3e0;
        color: #e65100;
    }

    .role-admin {
        background: #f3e5f5;
        color: #6a1b9a;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .status-pending {
        background: #fff8e1;
        color: #f57c00;
    }

    .status-pending::before {
        content: '⏱';
        font-size: 14px;
    }

    .status-approved {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-approved::before {
        content: '✓';
        font-size: 14px;
    }

    .status-rejected {
        background: #ffebee;
        color: #c62828;
    }

    .status-rejected::before {
        content: '✕';
        font-size: 14px;
    }

    .actions-cell {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: center;
    }

    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: inherit;
        white-space: nowrap;
    }

    .approve-btn {
        background: var(--green);
        color: #ffffff;
    }

    .approve-btn:hover {
        background: #2d6a31;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(58, 138, 63, 0.3);
    }

    .reject-btn {
        background: var(--red);
        color: #ffffff;
    }

    .reject-btn:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .view-btn {
        background: var(--blue);
        color: #ffffff;
    }

    .view-btn:hover {
        background: #155aa8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(26, 111, 204, 0.3);
    }

    .approved-info {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .no-actions {
        font-size: 12px;
        color: var(--text-muted);
        font-style: italic;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s;
    }

    .modal-content {
        background: #ffffff;
        border-radius: 16px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: var(--surface);
        color: var(--text);
    }

    .modal-body {
        padding: 24px;
    }

    .approve-role-form {
        display: grid;
        gap: 14px;
    }

    .approve-role-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .approve-role-select {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 14px;
        color: var(--text);
        background: #ffffff;
    }

    .approve-role-help {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .detail-item {
        margin-bottom: 4px;
    }

    .detail-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 6px;
    }

    .detail-value {
        font-size: 14px;
        color: var(--text);
        word-break: break-word;
    }

    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        color: var(--blue);
        background: #ffffff;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: var(--surface);
        border-color: var(--blue);
    }

    .pagination .active {
        background: var(--blue);
        color: #ffffff;
        border-color: var(--blue);
    }

    .empty-message {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">User Requests</h1>
    
    <div class="filter-tabs">
        <a href="?filter=all" class="filter-tab {{ request('filter', 'all') === 'all' ? 'active' : '' }}">All</a>
        <a href="?filter=pending" class="filter-tab {{ request('filter') === 'pending' ? 'active' : '' }}">Pending</a>
        <a href="?filter=approved" class="filter-tab {{ request('filter') === 'approved' ? 'active' : '' }}">Approved</a>
        <a href="?filter=rejected" class="filter-tab {{ request('filter') === 'rejected' ? 'active' : '' }}">Rejected</a>
    </div>
</div>

<div class="stats-cards">
    <div class="stat-card stat-pending">
        <div class="stat-label">⏱ Pending</div>
        <div class="stat-value">{{ $pendingCount }}</div>
    </div>
    <div class="stat-card stat-approved">
        <div class="stat-label">✓ Approved</div>
        <div class="stat-value">{{ $approvedCount }}</div>
    </div>
    <div class="stat-card stat-rejected">
        <div class="stat-label">✕ Rejected</div>
        <div class="stat-value">{{ $rejectedCount }}</div>
    </div>
</div>

@if($users->count() > 0)
    <div class="table-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ substr($user->first_name ?? 'U', 0, 1) }}</div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $user->getFullName() }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge role-{{ $user->role }}">
                                    @if($user->role === 'admin')
                                        Admin
                                    @elseif($user->role === 'official')
                                        Official
                                    @else
                                        Resident
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px;">
                                    <div style="font-weight: 600;">{{ $user->barangay ?? 'N/A' }}</div>
                                    <div style="color: var(--text-muted); font-size: 12px;">{{ $user->municipality_city ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ $user->status }}">{{ ucfirst($user->status) }}</span>
                                @if($user->status === 'approved' && $user->approver_role)
                                    <div class="approved-info">
                                        By {{ ucfirst($user->approver_role) }} • {{ $user->approved_at?->format('M d, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-size: 13px; color: var(--text-muted);">
                                {{ $user->created_at?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td>
                                <div class="actions-cell">
                                    @if($user->status === 'pending')
                                        @php
                                            $currentUser = auth()->user();
                                            $canApprove = $currentUser->role === 'admin' || 
                                                         ($currentUser->role === 'official' && $user->role === 'resident');
                                        @endphp
                                        
                                        @if($canApprove)
                                            @if($currentUser->role === 'admin')
                                                <button
                                                    type="button"
                                                    class="action-btn approve-btn"
                                                    onclick="openApproveModal({{ $user->id }}, '{{ addslashes($user->getFullName()) }}', '{{ $user->role }}')"
                                                >
                                                    ✓ Approve
                                                </button>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.approve', $user) }}" onsubmit="return confirm('✓ Approve {{ $user->getFullName() }} as Resident?')">
                                                    @csrf
                                                    <button type="submit" class="action-btn approve-btn">
                                                        ✓ Approve
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.users.reject', $user) }}" onsubmit="return confirm('✕ Reject {{ $user->getFullName() }}\'s registration?')">
                                                @csrf
                                                <button type="submit" class="action-btn reject-btn">
                                                    ✕ Reject
                                                </button>
                                            </form>
                                        @else
                                            <span class="no-actions">Only admins can approve officials</span>
                                        @endif
                                    @else
                                        <!-- <span class="no-actions">{{ ucfirst($user->status) }}</span> -->
                                    @endif
                                    <button type="button" class="action-btn view-btn" onclick="showUserDetails({{ $user->id }})">
                                        👁 View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        {{ $users->withQueryString()->links() }}
    </div>
@else
    <div class="table-card">
        <div class="empty-message">
            <div class="empty-icon">📭</div>
            <div>No users found matching your filter.</div>
        </div>
    </div>
@endif

<!-- User Details Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Resident Information</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <p style="text-align: center; color: var(--text-muted);">Loading...</p>
        </div>
    </div>
</div>

<div id="approveUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Approve User</h2>
            <button class="modal-close" type="button" onclick="closeApproveModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="approveUserForm" method="POST" class="approve-role-form">
                @csrf
                <div>
                    <label class="approve-role-label" for="assigned_role">Assign Role</label>
                    <select id="assigned_role" name="assigned_role" class="approve-role-select" required>
                        <option value="official">Barangay Official</option>
                        <option value="resident">Resident</option>
                    </select>
                </div>
                <p class="approve-role-help" id="approveUserHelpText">
                    Select the role to save on this account before approving the user.
                </p>
                <div class="modal-footer" style="padding: 0; border-top: 0;">
                    <button type="button" class="action-btn view-btn" onclick="closeApproveModal()">Cancel</button>
                    <button type="submit" class="action-btn approve-btn">Approve User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showUserDetails(userId) {
    const modal = document.getElementById('userModal');
    const modalBody = document.getElementById('modalBody');
    
    modal.classList.add('active');
    modalBody.innerHTML = '<p style="text-align: center; color: var(--text-muted);">Loading...</p>';
    
    fetch(`{{ url('/admin/users') }}/${userId}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const detailsBody = doc.querySelector('.details-body');
            
            if (detailsBody) {
                modalBody.innerHTML = '<div class="detail-grid">' + detailsBody.innerHTML + '</div>';
            } else {
                modalBody.innerHTML = '<p style="text-align: center;">Unable to load user details.</p>';
            }
        })
        .catch(error => {
            modalBody.innerHTML = '<p style="text-align: center; color: var(--red);">Error loading details.</p>';
        });
}

function closeModal() {
    document.getElementById('userModal').classList.remove('active');
}

function openApproveModal(userId, userName, currentRole) {
    const modal = document.getElementById('approveUserModal');
    const form = document.getElementById('approveUserForm');
    const roleSelect = document.getElementById('assigned_role');
    const helpText = document.getElementById('approveUserHelpText');

    form.action = `{{ url('/admin/users') }}/${userId}/approve`;
    roleSelect.value = currentRole === 'official' ? 'official' : 'resident';
    helpText.textContent = `Assign a role to ${userName} before completing approval.`;
    modal.classList.add('active');
}

function closeApproveModal() {
    document.getElementById('approveUserModal').classList.remove('active');
}

// Close modal on outside click
document.getElementById('userModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('approveUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApproveModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeApproveModal();
    }
});
</script>

@endsection
