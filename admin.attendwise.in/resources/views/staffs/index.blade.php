<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="text-end mb-2"><x-btn-add route="hospit.staffs.add.view" /></h4>
        <div class="table-responsive">
          <table class="table table-hover msc-smart-table">
            <thead>
                <tr>
                  {{-- <th>Room Number</th>
                  <th>Room Category</th>
                  <th>Status</th>
                  <th>Floor</th>
                  <th>Rate</th>
                  <th>Description</th> --}}
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Last Name</th>
                  <th>Gender</th>
                  <th>Date of Birth</th>
                  <th>Contact Number</th>
                  <th>Alternate Contact Number</th>
                  <th>Email</th>
                  <th>Emergency Contact Number</th>
                  <th>Emergency Contact Name</th>
                  <th>Marital Status</th>
                  <th>Status</th>
                  <th>Blood Group</th>
                  <th>Address</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Postal Code</th>
                  <th>Nationality</th>
                  <th>Designation</th>
                  <th>Joining Date</th>
                  <th>Department</th>
                  <th>Shift Timing</th>
                  <th>Salary</th>
                  <th>Bank Account Number</th>
                  <th>Bank Name</th>
                  <th>IFSC Code</th>
                  <th>PAN Number</th>
                  <th>Aadhaar Number</th>
                  <th>Role</th>
                  <th>Qualifications</th>
                  <th>Certifications</th>
                  <th>Experience Years</th>
                  <th>Is Active</th>
                  <th>Action</th>
                  {{-- <th>Profile Picture</th> --}}
                  
                </tr>
            </thead>
            <tbody>
                @foreach ($staffs as $item)
                    <tr>
                      {{-- <td>{{$item->room_number}}</td>
                      <td>{{$item->roomcategory->name}}</td>
                      <td>{{$item->room_status}}</td>
                      <td>{{$item->floor}}</td>
                      <td>{{$item->rate_list_id}}</td> --}}
                      <td>{{$item->first_name}}</td>
                      <td>{{$item->middle_name}}</td>
                      <td>{{$item->last_name}}</td>
                      <td>{{$item->gender}}</td>
                      <td>{{$item->date_of_birth}}</td>
                      <td>{{$item->contact_number}}</td>
                      <td>{{$item->alternate_contact_number}}</td>
                      <td>{{$item->email}}</td>
                      <td>{{$item->emergency_contact_number}}</td>
                      <td>{{$item->emergency_contact_name}}</td>
                      <td>{{$item->marital_status}}</td>
                      <td>{{$item->status}}</td>
                      <td>{{$item->blood_group}}</td>
                      <td>{{$item->address}}</td>
                      <td>{{$item->city}}</td>
                      <td>{{$item->state}}</td>
                      <td>{{$item->postal_code}}</td>
                      <td>{{$item->nationality}}</td>
                      <td>{{$item->designation}}</td>
                      <td>{{$item->joining_date}}</td>
                      <td>{{$item->department}}</td>
                      <td>{{$item->shift_timing}}</td>
                      <td>{{$item->salary}}</td>
                      <td>{{$item->bank_account_number}}</td>
                      <td>{{$item->bank_name}}</td>
                      <td>{{$item->ifsc_code}}</td>
                      <td>{{$item->pan_number}}</td>
                      <td>{{$item->aadhaar_number}}</td>
                      <td>{{$item->role}}</td>
                      <td>{{$item->qualifications}}</td>
                      <td>{{$item->certifications}}</td>
                      <td>{{$item->experience_years}}</td>
                      <td>{{$item->is_active}}</td>                      
                        <td>
                            <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="hospit.staffs.edit.view"/>
                            <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="hospit.staffs.delete"/>
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<x-footer />