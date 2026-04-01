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
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Personal Information</h4>
                    </div>
                    {{-- <div class="col-md-12 text-center">
                        <div class="form-group">
                            <label for="image" class="p-2 shadow-sm">
                                <img src="{{$fields['image']}}" alt="" width="100px">
                            </label>
                            <br>
                            <input type="file" class="form-control" hidden name="image" id="image" value="{{$fields['image']}}" onchange="previewImage(this)">
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input placeholder="First Name" type="text" class="form-control" name="first_name" id="first_name" required value="{{$fields['first_name']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input placeholder="Middle Name" type="text" class="form-control" name="last_name" id="middle_name" value="{{$fields['middle_name']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input placeholder="Last Name" type="text" class="form-control" name="last_name" id="last_name" value="{{$fields['last_name']}}">
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <input placeholder="Gender" type="text" class="form-control" name="gender" id="gender" value="{{$fields['gender']}}">
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">D.O.B</label>
                            <input placeholder="D.O.B" type="text" class="form-control" name="date_of_birth" id="date_of_birth" value="{{$fields['date_of_birth']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input placeholder="Email" type="email" class="form-control" name="email" id="email" value="{{$fields['email']}}">
                        </div>
                    </div>
                    <
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input placeholder="Mobile" type="number" class="form-control" name="contact_number" id="contact_number" required value="{{$fields['contact_number']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="alternate_contact_number">Alternate Contact Number</label>
                            <input placeholder="Alternate Contact Number" type="number" class="form-control" name="alternate_contact_number" id="alternate_contact_number" required value="{{$fields['alternate_contact_number']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="emergency_contact_number">Emergency Contact Number</label>
                            <input placeholder="Emergency Contact Number" type="number" class="form-control" name="emergency_contact_number" id="emergency_contact_number" required value="{{$fields['emergency_contact_number']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="emergency_contact_name">Emergency Contact Name</label>
                            <input placeholder="Emergency Contact Name" type="text" class="form-control" name="emergency_contact_name" id="emergency_contact_name" required value="{{$fields['emergency_contact_name']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_birth">Date of birth</label>
                            <input placeholder="Date of birth" type="date" class="form-control" name="date_of_birth" id="date_of_birth" required value="{{$fields['date_of_birth']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="aadhaar_number">Aadhaar Number</label>
                            <input type="tel" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}" placeholder="XXXX-XXXX-XXXX" class="form-control" name="aadhaar_number" id="aadhaar_number" value="{{$fields['aadhaar_number']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" placeholder="Password" class="form-control" name="password" id="password" value="{{$fields['password']}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select placeholder="Gender" name="gender" id="gender" class="form-control form-select" required>
                              <option value="male">Male</option>  
                              <option value="female" {{$fields['gender'] == "female" ? "selected":""}}>Female</option>  
                              <option value="other" {{$fields['gender'] == "other" ? "selected":""}}>Other</option>  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="marital_status">marital_status</label>
                            <select placeholder="marital_status" name="marital_status" id="marital_status" class="form-control form-select" required>
                              <option value="unmarried">Unmarried</option>  
                              <option value="married" {{$fields['marital_status'] == "married" ? "selected":""}}>Married</option>  
                              <option value="other" {{$fields['marital_status'] == "other" ? "selected":""}}>Other</option>  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="blood_group">Blood Group</label>
                            <input placeholder="Blood Group" type="text" class="form-control" name="blood_group" id="blood_group" required value="{{$fields['blood_group']}}">
                        </div>
                    </div>
                    
                    {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select placeholder="Status" name="status" id="status" class="form-control form-select" required>
                              <option value="1">Active</option>  
                              <option value="0" {{$fields['status'] == "0" ? "selected":""}}>Inactive</option>  
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" placeholder="Address" class="form-control" name="address" id="address" value="{{$fields['address']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" placeholder="City" class="form-control" name="city" id="city" value="{{$fields['city']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" placeholder="state" class="form-control" name="state" id="state" value="{{$fields['state']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="postal_code">postal_code</label>
                            <input type="text" placeholder="postal_code" class="form-control" name="postal_code" id="postal_code" value="{{$fields['postal_code']}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <input type="text" placeholder="Nationality" class="form-control" name="nationality" id="nationality" value="{{$fields['nationality']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="designation">Designation</label>
                            <input type="text" placeholder="Designation" class="form-control" name="designation" id="designation" value="{{$fields['designation']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="joining_date">Joining Date</label>
                            <input type="date" class="form-control" name="joining_date" id="joining_date" value="{{$fields['joining_date']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" placeholder="Department" class="form-control" name="department" id="department" value="{{$fields['department']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="shift_timing">Shift Timing</label>
                            <input type="text" placeholder="Shift Timing" class="form-control" name="shift_timing" id="shift_timing" value="{{$fields['shift_timing']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="salary">Salary</label>
                            <input type="number" placeholder="Salary" class="form-control" name="salary" id="salary" value="{{$fields['salary']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bank_account_number">Bank Account Number</label>
                            <input type="text" placeholder="Bank Account Number" class="form-control" name="bank_account_number" id="bank_account_number" value="{{$fields['bank_account_number']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" placeholder="Bank Name" class="form-control" name="bank_name" id="bank_name" value="{{$fields['bank_name']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ifsc_code">IFSC Code</label>
                            <input type="text" placeholder="IFSC Code" class="form-control" name="ifsc_code" id="ifsc_code" value="{{$fields['ifsc_code']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pan_number">PAN Number</label>
                            <input type="text" placeholder="PAN Number" class="form-control" name="pan_number" id="pan_number" value="{{$fields['pan_number']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select placeholder="Role" class="form-control form-select" name="role" id="role">
                                <option value="">Select Role </option>
                                @foreach ($staffs_category as $item)
                                    <option value="{{$item->id}}" {{$item->id == $fields['role']?"selected":""}} >{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @php
    use Illuminate\Support\Str;

    $labOperatorId = collect($staffs_category)->first(function ($item) {
        return Str::of($item->name)->lower()->trim() == 'lab operator';
    })?->id;
@endphp
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="role">Lab</label>
                            <select placeholder="Lab" class="form-control form-select" name="lab_id" id="lab_id">
                                <option value="">Select Lab</option>
                                @foreach ($labs as $item)
                                    <option value="{{$item->id}}" {{$item->id == $fields['lab_id']?"selected":""}} >{{$item->lab_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="qualifications">Qualifications</label>
                            <input type="text" placeholder="Qualifications" class="form-control" name="qualifications" id="qualifications" value="{{$fields['qualifications']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="certifications">Certifications</label>
                            <input type="text" placeholder="Certifications" class="form-control" name="certifications" id="certifications" value="{{$fields['certifications']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="experience_years">Experience Years</label>
                            <input type="number" placeholder="Experience Years" class="form-control" name="experience_years" id="experience_years" value="{{$fields['experience_years']}}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="is_active">Is Active</label>
                            <select class="form-control" name="is_active" id="is_active">
                                <option value="1" {{$fields['is_active'] == 1 ? 'selected' : ''}}>Yes</option>
                                <option value="0" {{$fields['is_active'] == 0 ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <input type="file" class="form-control" name="profile_picture" id="profile_picture">
                        </div>
                    </div>
                    
                    <x-form-buttons />
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    const roleSelect = document.getElementById("role");
const labSelect = document.getElementById("lab_id");
const labOperatorId = "{{ $labOperatorId }}";

function toggleLabSelect() {
    if (roleSelect.value == labOperatorId) {
        labSelect.closest(".form-group").style.display = "block";
    } else {
        labSelect.closest(".form-group").style.display = "none";
        labSelect.value = ""; 
    }
}

roleSelect.addEventListener("change", toggleLabSelect);
window.addEventListener("DOMContentLoaded", toggleLabSelect);

  </script>
<x-footer />