@extends('layouts.club')

@section('header-title', 'Members')
@section('header-subtitle', 'Manage members and their user groups for ' . $club->name)

@section('styles')
<style>
    .modern-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.75rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -2px rgba(0,0,0,0.02);
        margin-bottom: 1.5rem;
    }

    .grid-layout {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1.5rem;
    }

    @media (max-width: 1024px) {
        .grid-layout {
            grid-template-columns: 1fr;
        }
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }
    
    th {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-main);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border);
        background: var(--subtle-bg);
        color: var(--text-main);
        border-radius: 0.5rem;
        font-size: 0.9rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--text-main);
        color: var(--bg);
    }

    .btn-secondary {
        background: var(--subtle-bg);
        color: var(--text-main);
        border: 1px solid var(--border);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-primary { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-secondary { background: var(--subtle-bg); color: var(--text-muted); border: 1px solid var(--border); }
    .badge-danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .action-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.25rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
    }
    .action-btn:hover { background: var(--subtle-bg); color: var(--text-main); }

    /* Modal Styles */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center; z-index: 100;
    }
    .modal-overlay.active { display: flex; }
    .modal-content {
        background: var(--card-bg); width: 100%; max-width: 500px;
        border-radius: 1rem; border: 1px solid var(--border); overflow: hidden;
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .modal-header {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);
        display: flex; justify-content: space-between; align-items: center;
    }
    .modal-header h3 { font-size: 1.1rem; font-weight: 600; }
    .close-btn { background: none; border: none; color: var(--text-muted); cursor: pointer; display: flex; }
    .modal-body { padding: 1.5rem; }
    .modal-footer {
        padding: 1.25rem 1.5rem; border-top: 1px solid var(--border);
        display: flex; justify-content: flex-end; gap: 1rem;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    .checkbox-wrapper input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="grid-layout">
    <!-- Add Member Section -->
    <div>
        <div class="modern-card">
            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="user-plus" style="width: 20px;"></i> Add Member
            </h3>
            
            <form action="{{ route('club.members.add') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Member Type</label>
                    <select name="member_type" id="memberTypeSelect" class="form-control" onchange="toggleMemberSelect()" required>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                    </select>
                </div>

                <div class="form-group" id="studentSelectGroup">
                    <label class="form-label">Select Student</label>
                    <select name="member_id" id="studentSelect" class="form-control">
                        <option value="" disabled selected>Select a student...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->roll_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="facultySelectGroup" style="display: none;">
                    <label class="form-label">Select Faculty</label>
                    <select name="member_id_faculty" id="facultySelect" class="form-control" disabled>
                        <option value="" disabled selected>Select a faculty...</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }} ({{ $faculty->employee_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">User Group</label>
                    <select name="club_user_group_id" class="form-control">
                        <option value="">None (Base Member)</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Designation / Role</label>
                    <input type="text" name="designation" class="form-control" placeholder="e.g. Member, President, Coordinator">
                </div>

                <div class="form-group">
                    <label class="checkbox-wrapper form-label" style="font-weight: 500;">
                        <input type="checkbox" name="can_take_attendance" value="1">
                        Grant Attendance Taking Rights
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Add to Club
                </button>
            </form>
        </div>
    </div>

    <!-- Members List Section -->
    <div>
        <div class="modern-card" style="padding: 0;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="users" style="width: 20px;"></i> Current Members
                    <span class="badge badge-primary">{{ $members->count() }}</span>
                </h3>
            </div>
            
            @if($members->count() > 0)
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Name & ID</th>
                                <th>Designation</th>
                                <th>Rights</th>
                                <th>Status</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-main);">
                                        {{ $member->student->name ?? $member->faculty->name ?? 'N/A' }}
                                    </div>
                                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                                        {{ ucfirst($member->member_type) }} • 
                                        {{ $member->student->roll_number ?? $member->faculty->employee_code ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @if($member->userGroup)
                                        <div style="font-size: 0.8rem; font-weight: 500; color: var(--text-main); margin-bottom: 0.25rem;">
                                            <i data-lucide="shield" style="width: 12px; height: 12px; vertical-align: middle;"></i> {{ $member->userGroup->name }}
                                        </div>
                                    @endif
                                    @if($member->designation)
                                        <span class="badge badge-secondary">{{ $member->designation }}</span>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 0.85rem;">Member</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->can_take_attendance)
                                        <span class="badge badge-success" title="Can take attendance"><i data-lucide="check" style="width: 12px; height: 12px;"></i> Yes</span>
                                    @else
                                        <span class="badge badge-secondary" title="Cannot take attendance"><i data-lucide="x" style="width: 12px; height: 12px;"></i> No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <button class="action-btn" onclick="openEditModal({{ json_encode($member) }})" title="Edit">
                                        <i data-lucide="edit-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    <form action="{{ route('club.members.remove', $member->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove this member?');">
                                        @csrf
                                        <button type="submit" class="action-btn" style="color: #ef4444;" title="Remove">
                                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 4rem 2rem; text-align: center;">
                    <div style="width: 64px; height: 64px; background: var(--subtle-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid var(--border);">
                        <i data-lucide="users" style="width: 32px; height: 32px; color: var(--text-muted);"></i>
                    </div>
                    <h4 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">No members found</h4>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">Add some members using the form on the left.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Member Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <form id="editForm" method="POST" action="">
            @csrf
            <div class="modal-header">
                <h3>Edit Member</h3>
                <button type="button" class="close-btn" onclick="closeEditModal()">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">User Group</label>
                    <select name="club_user_group_id" id="editClubUserGroupId" class="form-control">
                        <option value="">None (Base Member)</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Designation / Role</label>
                    <input type="text" name="designation" id="editDesignation" class="form-control" placeholder="e.g. Member, President, Coordinator">
                </div>

                <div class="form-group">
                    <label class="checkbox-wrapper form-label" style="font-weight: 500;">
                        <input type="checkbox" name="can_take_attendance" id="editCanTakeAttendance" value="1">
                        Grant Attendance Taking Rights
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="editStatus" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleMemberSelect() {
        const type = document.getElementById('memberTypeSelect').value;
        const studentGroup = document.getElementById('studentSelectGroup');
        const facultyGroup = document.getElementById('facultySelectGroup');
        const studentSelect = document.getElementById('studentSelect');
        const facultySelect = document.getElementById('facultySelect');

        if (type === 'student') {
            studentGroup.style.display = 'block';
            facultyGroup.style.display = 'none';
            studentSelect.disabled = false;
            studentSelect.name = 'member_id';
            facultySelect.disabled = true;
            facultySelect.name = 'member_id_faculty';
        } else {
            studentGroup.style.display = 'none';
            facultyGroup.style.display = 'block';
            studentSelect.disabled = true;
            studentSelect.name = 'member_id_student';
            facultySelect.disabled = false;
            facultySelect.name = 'member_id';
        }
    }

    function openEditModal(member) {
        document.getElementById('editClubUserGroupId').value = member.club_user_group_id || '';
        document.getElementById('editDesignation').value = member.designation || '';
        document.getElementById('editCanTakeAttendance').checked = member.can_take_attendance == 1;
        document.getElementById('editStatus').value = member.status;
        
        const form = document.getElementById('editForm');
        form.action = `/club/members/${member.id}/update`;
        
        document.getElementById('editModal').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
    }

    // Close modal on click outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    // Initialize toggle on load
    document.addEventListener('DOMContentLoaded', function() {
        toggleMemberSelect();
    });
</script>
@endsection
