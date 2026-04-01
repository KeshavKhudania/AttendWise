<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 card-section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <label for="image">Doctor Image</label>
                                        <br>
                                        <label for="image" class="p-2 border">
                                            <img src="{{$fields['image'] !== null ? env("FILE_UPLOAD_PATH").$fields['image'] : "assets/svg/doctor.svg"}}" src-def="{{$fields['image'] !== null ? env("FILE_UPLOAD_PATH").$fields['image'] : "assets/svg/doctor.svg"}}" alt="" width="100px" height="100px">
                                        </label>
                                        <input type="file" class="form-control" hidden name="image" id="image" value="{{$fields['image']}}" onchange="previewImage(this)">
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <label for="signature_image">Doctor Signature</label>
                                        <br>
                                        <label for="signature_image" class="p-2 border">
                                            <img src="{{$fields['signature_image'] !== null ? env("FILE_UPLOAD_PATH").$fields['signature_image'] : "assets/svg/signature.svg"}}" src-def="{{$fields['signature_image'] !== null ? env("FILE_UPLOAD_PATH").$fields['signature_image'] : "assets/svg/signature.svg"}}" alt="" width="100px" height="100px">
                                        </label>
                                        <input type="file" class="form-control" hidden name="signature_image" id="signature_image" value="{{$fields['signature_image']}}" onchange="previewImage(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 card-section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Personal Information</h4>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input placeholder="Name" type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="liscence_number">Liscence Number</label>
                                        <input type="text" placeholder="Liscence Number" class="form-control" name="liscence_number" id="liscence_number" value="{{$fields['liscence_number']}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input placeholder="Email" type="email" class="form-control" name="email" id="email" value="{{$fields['email']}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="phone_number">Mobile</label>
                                        <input placeholder="Mobile" type="number" class="form-control" name="phone_number" id="phone_number" required value="{{$fields['phone_number']}}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="adhaar_number">Adhaar Number</label>
                                        <input type="tel" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}" placeholder="XXXX-XXXX-XXXX" class="form-control" name="adhaar_number" id="adhaar_number" value="{{$fields['adhaar_number']}}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select placeholder="Gender" name="gender" id="gender" class="form-control form-select" required>
                                          <option value="male">Male</option>  
                                          <option value="female" {{$fields['gender'] == "female" ? "selected":""}}>Female</option>  
                                          <option value="other" {{$fields['gender'] == "other" ? "selected":""}}>Other</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="dob">Date of birth</label>
                                        <input placeholder="Date of birth" type="date" class="form-control" name="dob" id="dob" value="{{$fields['dob']}}">
                                    </div>
                                </div>
                                
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="experience">Experience in years</label>
                                        <input placeholder="Experience in years" type="number" class="form-control" name="experience" id="experience" value="{{$fields['experience']}}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="blood_group">Blood Group</label>
                                        <select placeholder="Gender" name="blood_group" id="blood_group" class="form-control form-select">
                                            <option value="O+" {{$fields['blood_group'] == "O+"?"selected":""}}>O+</option>
                                            <option value="A+" {{$fields['blood_group'] == "A+"?"selected":""}}>A+</option>
                                            <option value="B+" {{$fields['blood_group'] == "B+"?"selected":""}}>B+</option>
                                            <option value="AB+ {{$fields['blood_group'] == "AB+"?"selected":""}}">AB+</option>
                                            <option value="O-" {{$fields['blood_group'] == "O-"?"selected":""}}>O-</option>
                                            <option value="A-" {{$fields['blood_group'] == "A-"?"selected":""}}>A-</option>
                                            <option value="B-" {{$fields['blood_group'] == "B-"?"selected":""}}>B-</option>
                                            <option value="AB- {{$fields['blood_group'] == "AB-"?"selected":""}}">AB-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select placeholder="Status" name="status" id="status" class="form-control form-select" required>
                                          <option value="1">Active</option>  
                                          <option value="0" {{$fields['status'] == "0" ? "selected":""}}>Inactive</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="qualification_id">Qualification</label>
                                        <select placeholder="Qualification" name="qualification_id" id="qualification_id" class="form-control form-select" required>
                                          @foreach ($qualifications as $item)
                                            <option value="{{Crypt::encrypt($item->id)}}" {{$fields['qualification_id'] == $item->id ? "selected":""}}>{{$item->name}}</option>  
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="department_id">Department</label>
                                        <select placeholder="Department" name="department_id" id="department_id" class="form-control form-select" required>
                                          @foreach ($departments as $item)
                                            <option value="{{Crypt::encrypt($item->id)}}" {{$fields['department_id'] == $item->id ? "selected":""}}>{{$item->name}}</option>  
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="consulting_fee">Consulting Fee</label>
                                        <input type="number" placeholder="Consulting Fee" class="form-control" name="consulting_fee" id="consulting_fee" value="{{$fields['consulting_fee']}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" placeholder="Address" class="form-control" name="address" id="address" value="{{$fields['address']}}">
                                    </div>
                                </div>
                                <x-form-buttons />
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
<x-footer />