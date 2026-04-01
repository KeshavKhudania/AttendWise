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
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Staff Category Details</h4>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input placeholder="Category Name" type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status </label>
                            <select placeholder="Status" name="status" id="status" class="form-control form-select" required>
                              <option value="1"  {{$fields['status'] == "1" ? "selected":""}}>Active</option>  
                              <option value="0"  {{$fields['status'] == "0" ? "selected":""}} >Dsiabled</option>  
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input name="description" placeholder="Description" class="form-control" id="description" value="{{$fields['description']}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h5>Permissions</h5>
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Name</th>
                              <th>Read</th>
                              <th>Create</th>
                              <th>Update</th>
                              <th>Delete</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($all_permissions as $item)
                                <tr>
                                  <td class="fw-bold">{{$item->name}}</td>
                                  <td>
                                    <div class="form-check">
                                      <input type="checkbox" name="allowed_permissions[]" value="{{App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$item->name])->count() > 0 ? Crypt::encrypt(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$item->name])->first()->id):""}}" <?php if(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$item->name])->count() > 0){
                                        echo in_array(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$item->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                      }else{
                                        echo "disabled";
                                      } ?> class="ms-5 form-check-input p-2">
                                    </div>
                                  </td>
                                  <td>  
                                    <div class="form-check">
                                      <input type="checkbox" name="allowed_permissions[]" value="{{App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$item->name])->count() > 0 ? Crypt::encrypt(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$item->name])->first()->id):""}}" <?php if(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$item->name])->count() > 0){
                                        echo in_array(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$item->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                      }else{
                                        echo "disabled";
                                      } ?> class="ms-5 form-check-input p-2">
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-check">
                                      <input type="checkbox" name="allowed_permissions[]" value="{{App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$item->name])->count() > 0 ? Crypt::encrypt(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$item->name])->first()->id):""}}" <?php if(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$item->name])->count() > 0){
                                        echo in_array(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$item->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                      }else{
                                        echo "disabled";
                                      } ?> class="ms-5 form-check-input p-2">
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-check">
                                      <input type="checkbox" name="allowed_permissions[]" value="{{App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$item->name])->count() > 0 ? Crypt::encrypt(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$item->name])->first()->id):""}}" <?php if(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$item->name])->count() > 0){
                                        echo in_array(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$item->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                      }else{
                                        echo "disabled";
                                      } ?> class="ms-5 form-check-input p-2">
                                    </div>
                                  </td>
                                </tr>
                                @if (App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R","parent_id"=>$item->id])->count() > 0)
                                    @foreach (App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R","parent_id"=>$item->id])->get() as $perm)
                                    <tr>
                                      <td>{{$perm->name}}</td>
                                      <td>
                                        <div class="form-check">
                                          <input type="checkbox" name="allowed_permissions[]" value="{{App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$perm->name])->count() > 0 ? Crypt::encrypt(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$perm->name])->first()->id):""}}" <?php if(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$perm->name])->count() > 0){
                                            echo in_array(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$perm->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                          }else{
                                            echo "disabled";
                                          } ?> class="ms-5 form-check-input p-2">
                                        </div>
                                      </td>
                                      <td>  
                                        <div class="form-check">
                                          <input type="checkbox" name="allowed_permissions[]" value="{{App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$perm->name])->count() > 0 ? Crypt::encrypt(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$perm->name])->first()->id):""}}" <?php if(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$perm->name])->count() > 0){
                                            echo in_array(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$perm->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                          }else{
                                            echo "disabled";
                                          } ?> class="ms-5 form-check-input p-2">
                                        </div>
                                      </td>
                                      <td>
                                        <div class="form-check">
                                          <input type="checkbox" name="allowed_permissions[]" value="{{App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$perm->name])->count() > 0 ? Crypt::encrypt(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$perm->name])->first()->id):""}}" <?php if(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$perm->name])->count() > 0){
                                            echo in_array(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$perm->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                          }else{
                                            echo "disabled";
                                          } ?> class="ms-5 form-check-input p-2">
                                        </div>
                                      </td>
                                      <td>
                                        <div class="form-check">
                                          <input type="checkbox" name="allowed_permissions[]" value="{{App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$perm->name])->count() > 0 ? Crypt::encrypt(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$perm->name])->first()->id):""}}" <?php if(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$perm->name])->count() > 0){
                                            echo in_array(App\Models\HospitFrontPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$perm->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                          }else{
                                            echo "disabled";
                                          } ?> class="ms-5 form-check-input p-2">
                                        </div>
                                      </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    <tr><td colspan="5"></td></tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                    <x-form-buttons />
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
<x-footer />