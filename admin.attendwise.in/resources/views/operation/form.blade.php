<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form action="{{ $action }}" method="POST" id="mainForm" data-form-type="{{ $type }}">
                @csrf
                <div class="container-fluid">
                    <div class="row">
                        <!-- Operation Name -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Operation Name</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="name" 
                                    id="name"
                                    placeholder="Operation Name"
                                    required
                                    value="{{ $fields['name'] ?? '' }}">
                            </div>
                        </div>
                        <!-- Operation Code -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code">Operation Code</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="code" 
                                    id="code"
                                    placeholder="Code (CPT/Custom/Optional)"
                                    value="{{ $fields['code'] ?? '' }}">
                            </div>
                        </div>
                        <!-- Base Price -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price">Base Price</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    class="form-control" 
                                    name="price" 
                                    id="price"
                                    required
                                    placeholder="Base Price"
                                    value="{{ $fields['price'] ?? '' }}">
                            </div>
                        </div>
                        <!-- Category -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control form-select" required>
                                    
                                    @foreach($categories as $c)
                                        <option value="{{ Crypt::encrypt($c->id) }}"
                                            {{ isset($fields['category_id']) && $fields['category_id'] == $c->id ? "selected" : "" }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Department -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department_id">Department</label>
                                <select name="department_id" id="department_id" class="form-control form-select" required>
                                    
                                    @foreach($departments as $d)
                                        <option value="{{ Crypt::encrypt($d->id) }}"
                                            {{ isset($fields['department_id']) && $fields['department_id'] == $d->id ? "selected" : "" }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Rate List -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rate_list_id">Rate List</label>
                                <select name="rate_list_id" id="rate_list_id" class="form-control form-select" required>
                                    
                                    @foreach($rateLists as $rl)
                                        <option value="{{ Crypt::encrypt($rl->id) }}"
                                            {{ isset($fields['rate_list_id']) && $fields['rate_list_id'] == $rl->id ? "selected" : "" }}>
                                            {{ $rl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Status (Active/Inactive) -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control form-select" required>
                                    <option value="1" {{ (isset($fields['status']) && $fields['status'] == "1") ? "selected" : "" }}>Active</option>  
                                    <option value="0" {{ (isset($fields['status']) && $fields['status'] == "0") ? "selected" : "" }}>Inactive</option>  
                                </select>
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description (optional)</label>
                                <textarea 
                                    name="description" 
                                    id="description" 
                                    class="form-control" 
                                    placeholder="Additional details about the operation">{{ $fields['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <x-form-buttons />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<x-footer />
