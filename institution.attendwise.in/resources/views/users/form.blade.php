<x-structure />
<x-header heading="{{$title}}" />

<div class="aw-page-header mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="page-heading mb-1">{{ $title }}</h1>
            <p class="text-muted small mb-0">Manage administrative account credentials, assign RBAC user groups, and control activation status.</p>
        </div>
        <div>
            <a href="{{ route('institution.admin.users.manage') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1.5 opacity-75"></i> Back to Users
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card aw-form-card shadow-sm border-0">
        <div class="card-body">
            <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
                @csrf
                @if(isset($type) && ($type === 'edit' || $type === 'EDIT'))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    {{-- User Credentials Section --}}
                    <div class="col-md-12">
                        <div class="aw-form-section-header">
                            <div class="aw-form-section-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h3 class="aw-form-section-title">Account Credentials & Access Profile</h3>
                                <p class="aw-form-section-subtitle">User personal details, login password, and role permissions</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="name" class="form-label aw-field-label">Full Name <span class="aw-field-required">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" required placeholder="e.g. Alexander Pierce" value="{{$fields['name']}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="email" class="form-label aw-field-label">Email Address <span class="aw-field-required">*</span></label>
                            <input type="email" class="form-control" name="email" id="email" required placeholder="e.g. admin@institution.edu" value="{{$fields['email']}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="mobile" class="form-label aw-field-label">Mobile Number <span class="aw-field-required">*</span></label>
                            <input type="number" class="form-control" name="mobile" id="mobile" required placeholder="10-digit mobile number" value="{{$fields['mobile']}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="password" class="form-label aw-field-label">Password <span class="aw-field-required">*</span></label>
                            <input type="password" class="form-control" name="password" id="password" required placeholder="••••••••••••" value="{{$fields['password']}}">
                        </div>
                    </div>

                    @if (in_array("institution.admin.group.manage", $permissions))
                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="group_id" class="form-label aw-field-label">Assigned User Group (Role)</label>
                            <select name="group_id" id="group_id" class="form-control form-select">
                                @foreach ($all_groups as $item)
                                    <option value="{{Crypt::encrypt($item->id)}}" {{$fields['group_id'] == $item->id ? "selected":""}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <div class="aw-field-group">
                            <label for="status" class="form-label aw-field-label">Account Status <span class="aw-field-required">*</span></label>
                            <select name="status" id="status" class="form-control form-select" required>
                                <option value="1">Active</option>  
                                <option value="0" {{$fields['status'] == "0" ? "selected":""}}>Inactive</option>  
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <x-form-buttons />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<x-footer />