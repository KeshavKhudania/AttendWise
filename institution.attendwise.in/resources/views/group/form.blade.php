<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
        @csrf
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control form-select" required>
                  <option value="1">Active</option>
                  <option value="0" {{$fields['status']=="0" ? "selected" :""}}>Inactive</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <h5>Permissions</h5>
              {{-- <table class="table table-bordered">
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
                    <td class="fw-bold">{{$item['name']}}</td>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" name="allowed_permissions[]"
                          value="{{App\Models\HospitPermission::where([" deleted_at"=>null, "status"=>"1",
                        "action"=>"R", "name"=>$item['name']])->count() > 0 ?
                        Crypt::encrypt(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                        "action"=>"R", "name"=>$item['name']])->first()->id):""}}"
                        <?php if(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$item['name']])->count() > 0){
                                        echo in_array(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$item['name']])->first()->route_name, $fields['permissions'])?"checked":"";
                                      }else{
                                        echo "disabled";
                                      } ?> class="ms-5 form-check-input p-2">
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" name="allowed_permissions[]"
                          value="{{App\Models\HospitPermission::where([" deleted_at"=>null, "status"=>"1",
                        "action"=>"C", "name"=>$item['name']])->count() > 0 ?
                        Crypt::encrypt(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                        "action"=>"C", "name"=>$item['name']])->first()->id):""}}"
                        <?php if(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$item['name']])->count() > 0){
                                        echo in_array(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$item['name']])->first()->route_name, $fields['permissions'])?"checked":"";
                                      }else{
                                        echo "disabled";
                                      } ?> class="ms-5 form-check-input p-2">
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" name="allowed_permissions[]"
                          value="{{App\Models\HospitPermission::where([" deleted_at"=>null, "status"=>"1",
                        "action"=>"U", "name"=>$item['name']])->count() > 0 ?
                        Crypt::encrypt(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                        "action"=>"U", "name"=>$item['name']])->first()->id):""}}"
                        <?php if(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$item['name']])->count() > 0){
                                        echo in_array(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$item['name']])->first()->route_name, $fields['permissions'])?"checked":"";
                                      }else{
                                        echo "disabled";
                                      } ?> class="ms-5 form-check-input p-2">
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" name="allowed_permissions[]"
                          value="{{App\Models\HospitPermission::where([" deleted_at"=>null, "status"=>"1",
                        "action"=>"D", "name"=>$item['name']])->count() > 0 ?
                        Crypt::encrypt(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                        "action"=>"D", "name"=>$item['name']])->first()->id):""}}"
                        <?php if(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$item['name']])->count() > 0){
                                        echo in_array(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$item['name']])->first()->route_name, $fields['permissions'])?"checked":"";
                                      }else{
                                        echo "disabled";
                                      } ?> class="ms-5 form-check-input p-2">
                      </div>
                    </td>
                  </tr>
                  @if (App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                  "action"=>"R","parent_id"=>$item['id']])->count() > 0)
                  @foreach (App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                  "action"=>"R","parent_id"=>$item['id']])->get() as $perm)
                  <tr>
                    <td>{{$perm->name}}</td>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" name="allowed_permissions[]"
                          value="{{App\Models\HospitPermission::where([" deleted_at"=>null, "status"=>"1",
                        "action"=>"R", "name"=>$perm->name])->count() > 0 ?
                        Crypt::encrypt(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                        "action"=>"R", "name"=>$perm->name])->first()->id):""}}"
                        <?php if(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$perm->name])->count() > 0){
                                            echo in_array(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"R", "name"=>$perm->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                          }else{
                                            echo "disabled";
                                          } ?> class="ms-5 form-check-input p-2">
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" name="allowed_permissions[]"
                          value="{{App\Models\HospitPermission::where([" deleted_at"=>null, "status"=>"1",
                        "action"=>"C", "name"=>$perm->name])->count() > 0 ?
                        Crypt::encrypt(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                        "action"=>"C", "name"=>$perm->name])->first()->id):""}}"
                        <?php if(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$perm->name])->count() > 0){
                                            echo in_array(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"C", "name"=>$perm->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                          }else{
                                            echo "disabled";
                                          } ?> class="ms-5 form-check-input p-2">
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" name="allowed_permissions[]"
                          value="{{App\Models\HospitPermission::where([" deleted_at"=>null, "status"=>"1",
                        "action"=>"U", "name"=>$perm->name])->count() > 0 ?
                        Crypt::encrypt(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                        "action"=>"U", "name"=>$perm->name])->first()->id):""}}"
                        <?php if(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$perm->name])->count() > 0){
                                            echo in_array(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"U", "name"=>$perm->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                          }else{
                                            echo "disabled";
                                          } ?> class="ms-5 form-check-input p-2">
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="checkbox" name="allowed_permissions[]"
                          value="{{App\Models\HospitPermission::where([" deleted_at"=>null, "status"=>"1",
                        "action"=>"D", "name"=>$perm->name])->count() > 0 ?
                        Crypt::encrypt(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1",
                        "action"=>"D", "name"=>$perm->name])->first()->id):""}}"
                        <?php if(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$perm->name])->count() > 0){
                                            echo in_array(App\Models\HospitPermission::where(["deleted_at"=>null, "status"=>"1", "action"=>"D", "name"=>$perm->name])->first()->route_name, $fields['permissions'])?"checked":"";
                                          }else{
                                            echo "disabled";
                                          } ?> class="ms-5 form-check-input p-2">
                      </div>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                  @endforeach
                </tbody>
              </table> --}}
              <div class="table-responsive">
                <table class="table permission-table">
                  <thead>
                    <tr>
                      <th><i class="fas fa-cog me-2"></i>Permission Name</th>
                      <th class="text-center"><i class="fas fa-eye me-2"></i>Read</th>
                      <th class="text-center"><i class="fas fa-plus me-2"></i>Create</th>
                      <th class="text-center"><i class="fas fa-edit me-2"></i>Update</th>
                      <th class="text-center"><i class="fas fa-trash me-2"></i>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($all_permissions as $item)
                    <tr>
                      <td>
                        <i class="fas {{$item['icon']}} permission-icon"></i>
                        <span class="permission-name">{{$item['name']}}</span>
                        <span class="badge-new">Parent</span>
                      </td>
                      <td class="text-center">
                        <label class="custom-checkbox">
                          <input type="checkbox" name="allowed_permissions[]" value="{{Crypt::encrypt($item['id'])}}"
                            {{in_array($item['route_name'],$fields['permissions']) ? "checked" :""}}
                            class="ms-5 form-check-input p-2" data-parent={{Str::slug($item['name'])}}>
                          <span class="checkmark"></span>
                        </label>
                      </td>
                      <td class="text-center">
                        <label class="custom-checkbox">
                          <input type="checkbox" disabled>
                          <span class="checkmark"></span>
                        </label>
                      </td>
                      <td class="text-center">
                        <label class="custom-checkbox">
                          <input type="checkbox" disabled>
                          <span class="checkmark"></span>
                        </label>
                      </td>
                      <td class="text-center">
                        <label class="custom-checkbox">
                          <input type="checkbox" disabled>
                          <span class="checkmark"></span>
                        </label>
                      </td>
                    </tr>
                    @foreach (($item['childs'] ?? []) as $child_key => $child_item)
                    <tr>
                      <td>
                        @if(isset($child_item['R'][0]))
                        <i class="fas {{$child_item['R'][0]["icon"]}} permission-icon"></i>
                        @endif
                        <span class="permission-name">{{$child_key}}</span>
                      </td>
                      <td class="text-center">
                        @if(isset($child_item["R"][0]))
                        <label class="custom-checkbox">
                          <input {{in_array($item['route_name'],$fields['permissions']) ? "" :"disabled"}}
                            data-parent-rel="{{Str::slug($item['name'])}}" type="checkbox" name="allowed_permissions[]"
                            value="{{Crypt::encrypt($child_item["R"][0]['id'])}}"
                            {{in_array($child_item["R"][0]['route_name'],$fields['permissions']) ? "checked" :""}}
                            class="ms-5 form-check-input p-2" data-bs-toggle="collapse"
                            data-bs-target="#{{Str::slug($child_key)}}" aria-expanded="false"
                            aria-controls="{{Str::slug($child_key)}}"
                            onchange="toggleSubPermission(this, '{{Str::slug($child_key)}}')">
                          <span class="checkmark"></span>
                        </label>
                        @else
                        <small class="text-muted">-</small>
                        @endif
                      </td>
                      <td class="text-center">
                        @if(isset($child_item["C"][0]))
                        <label class="custom-checkbox">
                          <input {{in_array($item['route_name'],$fields['permissions']) ? "" :"disabled"}}
                            data-parent-rel="{{Str::slug($item['name'])}}" type="checkbox" name="allowed_permissions[]"
                            value="{{Crypt::encrypt($child_item["C"][0]['id'])}}"
                            {{in_array($child_item["C"][0]['route_name'],$fields['permissions']) ? "checked" :""}}
                            class="ms-5 form-check-input p-2">
                          <span class="checkmark"></span>
                        </label>
                        @else
                        <small class="text-muted">-</small>
                        @endif
                      </td>
                      <td class="text-center">
                        @if(isset($child_item["U"][0]))
                        <label class="custom-checkbox">
                          <input {{in_array($item['route_name'],$fields['permissions']) ? "" :"disabled"}}
                            data-parent-rel="{{Str::slug($item['name'])}}" type="checkbox" name="allowed_permissions[]"
                            value="{{Crypt::encrypt($child_item["U"][0]['id'])}}"
                            {{in_array($child_item["U"][0]['route_name'],$fields['permissions']) ? "checked" :""}}
                            class="ms-5 form-check-input p-2">
                          <span class="checkmark"></span>
                        </label>
                        @else
                        <small class="text-muted">-</small>
                        @endif
                      </td>
                      <td class="text-center">
                        @if(isset($child_item["D"][0]))
                        <label class="custom-checkbox">
                          <input {{in_array($item['route_name'],$fields['permissions']) ? "" :"disabled"}}
                            data-parent-rel="{{Str::slug($item['name'])}}" type="checkbox" name="allowed_permissions[]"
                            value="{{Crypt::encrypt($child_item["D"][0]['id'])}}"
                            {{in_array($child_item["D"][0]['route_name'],$fields['permissions']) ? "checked" :""}}
                            class="ms-5 form-check-input p-2">
                          <span class="checkmark"></span>
                        </label>
                        @else
                        <small class="text-muted">-</small>
                        @endif
                      </td>
                    </tr>
                    @if (isset($child_item["R"][0]) && ($child_item["R"][0]['children'] ?? false))
                    <tr class="collapse collapse-row {{in_array($child_item["
                      R"][0]['route_name'],$fields['permissions']) ? "show" :""}}" id="{{Str::slug($child_item["
                      R"][0]['name'])}}">
                      <td colspan="5">
                        <div class="sub-permissions-container">
                          <h6 class="mb-3"><i class="fas fa-list me-2"></i>Sub Permissions</h6>
                          @foreach ($child_item["R"][0]['children'] as $sub_child_key => $sub_child_item)
                          <div class="sub-permission-item">
                            <span class="sub-permission-label">
                              <i class="fas {{$sub_child_item['icon']}} sub-permission-icon"></i>
                              {{$sub_child_item['name']}}
                            </span>
                            <label class="custom-checkbox">
                              <input {{in_array($item['route_name'],$fields['permissions']) ? "" :"disabled"}}
                                data-parent-rel="{{Str::slug($item['name'])}}" type="checkbox"
                                name="allowed_permissions[]" value="{{Crypt::encrypt($sub_child_item['id'])}}"
                                {{in_array($sub_child_item['route_name'],$fields['permissions']) ? "checked" :""}}
                                class="ms-5 form-check-input p-2">
                              <span class="checkmark"></span>
                            </label>
                          </div>
                          @endforeach
                        </div>
                      </td>
                    </tr>
                    @endif
                    @endforeach

                    @endforeach
                  </tbody>
                </table>
              </div>

              <script>
                          $("input[data-parent]").on("change" , fun                                   var parentId = $(this)                  t");
                  if ($(t                    d")) {
                     $ ("input[ d ata-parent-rel='" + parentId +                   sabled",                               } else {
                                        tId).collapse("hide");
                        $("input[data-parent-rel                  Id                .pro                led", true);
                  }
                })                        function toggleSubPermission(checkbox, targetId) {
                                      ew bootstrap.C                  menty                  , {
                                                        }                                               checked) {
                    collapse.show();
                  }                                $(tar                  "i                e='                ']").prop("checked", false);
                            collapse.hide();
                  }
                                          // Add some interactive eff                           document.addEventListener('DOMContentLoaded', function () {
                        // Add click sound effect (opt                             const checkboxes = document.querySe lecto                      eckbox"]');
                  checkboxes.forEach(checkbox => {
                         ckbox.addEventListener('change', function () {
                      this.closest('.custom-checkbox').styl                      (0.95)';
                                          (()                              sest('.custom-checkbox').style.transform = 'scale(1)';
                      }, 100);
                    });
                  });
                });
              </script>
            </div>
            <x-form-buttons />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<x-footer />