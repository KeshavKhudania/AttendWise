<x-structure />
<x-header />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">{{$title}}</h4>
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Bed Category Details</h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_name">Category Name</label>
                            <input placeholder="Bed Category Name" type="text" class="form-control" name="category_name" id="category_name" required value="{{$fields['category_name']}}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cat_status">Category Status </label>
                            <select placeholder="Room Category" name="cat_status" id="cat_status" class="form-control form-select" required>
                                <option value="">Select Category Status</option>
                              <option value="active"  {{$fields['cat_status'] == "active" ? "selected":""}} >Active</option>  
                              <option value="disabled"  {{$fields['cat_status'] == "disabled" ? "selected":""}} >Disabled</option>  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" placeholder="Description" class="form-control" id="description" cols="20" rows="20">{{$fields['description']}}</textarea>
                        </div>
                    </div>
                    {{-- <div class="col-md-3 mt-2"> --}}
                        <x-form-buttons />
                    {{-- </div> --}}
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
<x-footer />