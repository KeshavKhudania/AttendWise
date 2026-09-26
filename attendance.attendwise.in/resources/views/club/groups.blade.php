@extends('layouts.club')

@section('header-title', 'User Groups')
@section('header-subtitle', 'Manage roles and access permissions for your club team.')

@section('styles')
<style>
    .groups-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .btn-create {
        background: var(--text-main);
        color: var(--bg);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    /* Cards Grid */
    .groups-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .group-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 1.25rem;
        padding: 1.5rem;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .group-card:hover {
        border-color: var(--text-muted);
        box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }

    .group-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .group-icon {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        background: var(--subtle-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-main);
        border: 1px solid var(--border);
    }

    .group-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .group-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.4;
        min-height: 2.8em;
    }

    .group-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.8rem;
        background: var(--subtle-bg);
        border: 1px solid var(--border);
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .group-actions {
        display: flex;
        gap: 0.5rem;
        margin-left: auto;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-action:hover {
        background: var(--subtle-bg);
        color: var(--text-main);
    }
    
    .btn-action.delete:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    /* Modal & Form Styles */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6); backdrop-filter: blur(5px);
        display: none; align-items: center; justify-content: center; z-index: 100;
        padding: 1rem;
    }
    .modal-overlay.active { display: flex; }
    
    .modal-content {
        background: var(--card-bg); 
        width: 100%; 
        max-width: 650px; 
        max-height: 90vh;
        border-radius: 1.25rem; 
        border: 1px solid var(--border); 
        display: flex;
        flex-direction: column;
        animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    @keyframes slideUp {
        from { transform: translateY(20px) scale(0.98); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }

    .modal-header {
        padding: 1.5rem 2rem; 
        border-bottom: 1px solid var(--border);
        display: flex; justify-content: space-between; align-items: center;
    }
    
    .modal-header h3 { font-size: 1.25rem; font-weight: 700; }
    .close-btn { background: var(--subtle-bg); border: 1px solid var(--border); border-radius: 50%; width: 32px; height: 32px; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .close-btn:hover { color: var(--text-main); background: var(--border); }
    
    .modal-body { 
        padding: 2rem; 
        overflow-y: auto;
        flex: 1;
        min-height: 0;
    }
    
    .modal-footer {
        padding: 1.5rem 2rem; 
        border-top: 1px solid var(--border);
        display: flex; justify-content: flex-end; gap: 1rem;
        background: var(--card-bg);
        border-radius: 0 0 1.25rem 1.25rem;
    }

    .form-group { margin-bottom: 1.5rem; }
    .form-label { display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main); }
    .form-control { width: 100%; padding: 0.85rem 1rem; border: 1px solid var(--border); background: var(--subtle-bg); color: var(--text-main); border-radius: 0.75rem; font-size: 0.95rem; transition: border-color 0.2s; }
    .form-control:focus { outline: none; border-color: var(--text-muted); }
    textarea.form-control { resize: vertical; min-height: 80px; }

    /* Custom Toggle Switch for Permissions */
    .permission-category {
        margin-bottom: 1.5rem;
        background: var(--subtle-bg);
        border: 1px solid var(--border);
        border-radius: 1rem;
        overflow: hidden;
    }

    .category-header {
        padding: 1rem 1.25rem;
        background: rgba(0,0,0,0.02);
        border-bottom: 1px solid var(--border);
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-main);
    }
    
    [data-theme="dark"] .category-header { background: rgba(255,255,255,0.02); }

    .permissions-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    @media(max-width: 600px) {
        .permissions-list { grid-template-columns: 1fr; }
    }

    .permission-item {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border);
        border-right: 1px solid var(--border);
    }

    .permission-item:nth-child(even) { border-right: none; }
    .permission-item:last-child, .permission-item:nth-last-child(2):nth-child(odd) { border-bottom: none; }

    .permission-info {
        display: flex;
        flex-direction: column;
    }

    .permission-name {
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* iOS Style Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background-color: var(--border); transition: .3s; border-radius: 24px;
    }
    .slider:before {
        position: absolute; content: ""; height: 18px; width: 18px;
        left: 3px; bottom: 3px; background-color: var(--card-bg); transition: .3s; border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    input:checked + .slider { background-color: var(--text-main); }
    input:checked + .slider:before { transform: translateX(20px); }

    .btn-secondary { background: var(--subtle-bg); color: var(--text-main); border: 1px solid var(--border); padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; }
    .btn-primary { background: var(--text-main); color: var(--bg); border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 600; cursor: pointer; }
</style>
@endsection

@section('content')

@php
    // Group permissions dynamically by their prefix (e.g., 'members', 'events')
    $groupedPerms = [];
    foreach($permissions as $key => $label) {
        $prefix = explode('.', $key)[0];
        $categoryName = ucfirst($prefix);
        if($prefix === 'groups') $categoryName = 'Administration';
        $groupedPerms[$categoryName][$key] = $label;
    }
@endphp

<div class="groups-header">
    <div>
        <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem;">Role Management</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Organize your team by assigning roles with tailored access rights.</p>
    </div>
    <button class="btn-create" onclick="openModal('create')">
        <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
        Create Role
    </button>
</div>

@if($groups->count() > 0)
    <div class="groups-grid">
        @foreach($groups as $group)
        <div class="group-card">
            <div class="group-header">
                <div class="group-icon">
                    <i data-lucide="shield" style="width: 24px; height: 24px;"></i>
                </div>
            </div>
            
            <div class="group-title">{{ $group->name }}</div>
            <div class="group-desc">{{ $group->description ?: 'No description provided for this role.' }}</div>
            
            <div class="group-stats">
                <div class="stat-badge" title="Active Members">
                    <i data-lucide="users" style="width: 14px; height: 14px; color: var(--text-muted);"></i>
                    {{ $group->members_count }}
                </div>
                <div class="stat-badge" title="Permissions Granted">
                    <i data-lucide="key" style="width: 14px; height: 14px; color: var(--text-muted);"></i>
                    {{ is_array($group->permissions) ? count($group->permissions) : 0 }}
                </div>

                <div class="group-actions">
                    <button class="btn-action" onclick="openModal('edit', {{ json_encode($group) }})" title="Edit Role">
                        <i data-lucide="edit-2" style="width: 16px; height: 16px;"></i>
                    </button>
                    <form action="{{ route('club.groups.remove', $group->id) }}" method="POST" onsubmit="return confirm('Delete this role? Members assigned to it will lose their permissions.');" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-action delete" title="Delete Role" {{ $group->members_count > 0 ? 'disabled' : '' }} style="opacity: {{ $group->members_count > 0 ? '0.4' : '1' }}">
                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div style="padding: 6rem 2rem; text-align: center; background: var(--card-bg); border: 1px dashed var(--border); border-radius: 1.5rem;">
        <div style="width: 72px; height: 72px; background: var(--subtle-bg); border-radius: 2rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid var(--border);">
            <i data-lucide="shield" style="width: 32px; height: 32px; color: var(--text-muted);"></i>
        </div>
        <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;">No roles created yet</h4>
        <p style="font-size: 0.95rem; color: var(--text-muted); max-width: 400px; margin: 0 auto 1.5rem;">Create specific roles like "Event Managers" or "Finance" to safely delegate tasks to your club members.</p>
        <button class="btn-create" onclick="openModal('create')">
            <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
            Create Your First Role
        </button>
    </div>
@endif

<!-- Shared Modal for Create & Edit -->
<div class="modal-overlay" id="groupModal">
    <form id="groupForm" method="POST" action="" class="modal-content">
        @csrf
        <div class="modal-header">
                <h3 id="modalTitle">Create Role</h3>
                <button type="button" class="close-btn" onclick="closeModal()">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Role Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" id="formName" class="form-control" placeholder="e.g. Event Coordinator" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="formDescription" class="form-control" placeholder="Briefly describe what members with this role can do..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span>Access Permissions</span>
                        <span style="font-size: 0.8rem; font-weight: 500; color: var(--text-muted); cursor: pointer;" onclick="toggleAllPermissions()">Select All</span>
                    </label>

                    @foreach($groupedPerms as $category => $perms)
                    <div class="permission-category">
                        <div class="category-header">
                            @if($category == 'Members') <i data-lucide="users" style="width: 16px;"></i> 
                            @elseif($category == 'Events') <i data-lucide="calendar" style="width: 16px;"></i>
                            @elseif($category == 'Attendance') <i data-lucide="clipboard-check" style="width: 16px;"></i>
                            @else <i data-lucide="settings" style="width: 16px;"></i> @endif
                            {{ $category }} Permissions
                        </div>
                        <div class="permissions-list">
                            @foreach($perms as $key => $label)
                            <div class="permission-item">
                                <div class="permission-info">
                                    <span class="permission-name">{{ $label }}</span>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="permissions[]" class="perm-checkbox" id="perm_{{ str_replace('.', '_', $key) }}" value="{{ $key }}">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-primary" id="submitBtn">Create Role</button>
            </div>
        </form>
</div>

@endsection

@section('scripts')
<script>
    function openModal(type, group = null) {
        const modal = document.getElementById('groupModal');
        const form = document.getElementById('groupForm');
        const title = document.getElementById('modalTitle');
        const btn = document.getElementById('submitBtn');
        
        // Reset form
        form.reset();
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
        
        if(type === 'create') {
            title.textContent = 'Create New Role';
            btn.textContent = 'Create Role';
            form.action = "{{ route('club.groups.add') }}";
        } else if (type === 'edit' && group) {
            title.textContent = 'Edit Role';
            btn.textContent = 'Save Changes';
            form.action = `/club/groups/${group.id}/update`;
            
            document.getElementById('formName').value = group.name || '';
            document.getElementById('formDescription').value = group.description || '';
            
            if (group.permissions && Array.isArray(group.permissions)) {
                group.permissions.forEach(perm => {
                    const cb = document.getElementById('perm_' + perm.replace('.', '_'));
                    if (cb) cb.checked = true;
                });
            }
        }
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeModal() {
        document.getElementById('groupModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close on outside click
    document.getElementById('groupModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    
    // Select All Toggle
    let allSelected = false;
    function toggleAllPermissions() {
        allSelected = !allSelected;
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = allSelected);
    }
</script>
@endsection
