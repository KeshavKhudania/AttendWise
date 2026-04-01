<x-structure />
<x-header heading="{{ $title }}" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form action="{{ $action }}" method="POST" id="mainForm" data-form-type="{{ $type }}">
                @csrf
                <div class="container-fluid">
                    <div class="row">
                        <!-- Anesthesia Name -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Anesthesia Name</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="name" 
                                    id="name"
                                    placeholder="Anesthesia Name"
                                    required
                                    value="{{ $fields['name'] ?? '' }}">
                            </div>
                        </div>
                       
                        <!-- Anesthesia Type -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="anesthesia_type_id">Anesthesia Type</label>
                                <select name="anesthesia_type_id" id="anesthesia_type_id" class="form-control form-select" required>
                                    
                                    @foreach($anesthesia_types as $c)
                                        <option value="{{ Crypt::encrypt($c->id) }}"
                                            {{ isset($fields['anesthesia_type_id']) && $fields['anesthesia_type_id'] == $c->id ? "selected" : "" }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Status (Active/Inactive) -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control form-select" required>
                                    <option value="1" {{ (isset($fields['status']) && $fields['status'] == "1") ? "selected" : "" }}>Active</option>  
                                    <option value="0" {{ (isset($fields['status']) && $fields['status'] == "0") ? "selected" : "" }}>Inactive</option>  
                                </select>
                            </div>
                        </div>
                        <!-- Rate List -->
                        <div class="col-md-2">
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
                        
                        
                         <!-- Cost -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="price">Cost</label>
                                <input 
                                    type="number" 
                                    step="1" 
                                    class="form-control" 
                                    name="price" 
                                    id="price"
                                    min="0"
                                    required
                                    placeholder="Cost"
                                    value="{{ $fields['price'] ?? '' }}">
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
