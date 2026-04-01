<x-structure />
<x-header heading="{{$title}}"/>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form action="{{$action}}" method="POST" id="mainForm" data-form-type="{{$type}}">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input placeholder="Name" type="text" class="form-control" name="name" id="name" required value="{{$fields['name']}}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="doctor_id">Doctor</label>
                            <select placeholder="Doctor" name="doctor_id" id="doctor_id" class="form-control form-select" required>
                                @foreach ($doctors as $item)
                                    <option value="{{Crypt::encrypt($item->id)}}" {{$fields['doctor_id'] == $item->doctor_id ? "selected":""}}>{{$item->name}}</option>  
                                @endforeach
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
                    <div class="col-md-12 mb-2">
                        <h4 class="border-bottom pb-1 d-inline-block border-2 border-dark">Time Table</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="container-fluid bg-white pt-4 border" id="time-table-container">
                            <div class="row">
                                <div class="col-md-12 text-end pb-2">
                                    <button class="btn btn-success" type="button" id="addTimeTableRowBtn"><i class="fas fa-plus"></i> Add Row</button>
                                </div>
                                @foreach ($time_table_rows as $key => $item)
                                <?php
                                        $selected_week_days= [];
                                    foreach ($item as $value) {
                                        $selected_week_days[] = $value->day;
                                    }
                                ?>
                                        <div class="row border-bottom border-2 mb-3 border-dark" id="row-id-{{$key}}">
                                            <div class="col-md-4">
                                        <table class="table p-0">
                                                <tbody>
                                                    <tr class="p-0">
                                                        <td class="p-0 border-0"><span class="rowOrder badge bg-info"></span></td>
                                                        <td class="p-0 border-0">
                                                    <div class="form-check d-inline-block pe-2">
                                                    <label for="day-mon-{{$key}}">Monday</label>
                                                    <input name="day[{{$key}}][]" type="checkbox" value="Mon" {{in_array("Mon", $selected_week_days) ? "checked":""}} id="day-mon-{{$key}}" 
                                                    class="form-check-input">
                                                </div>
                                            </td>
                                            <td class="p-0 border-0">
                                                <div class="form-check d-inline-block pe-2">
                                                    <label for="day-tue-{{$key}}">Tuesday</label>
                                                    <input name="day[{{$key}}][]" type="checkbox" value="Tue" {{in_array("Tue", $selected_week_days) ? "checked":""}} id="day-tue-{{$key}}" class="form-check-input">
                                                </div>
                                            </td>
                                            <td class="p-0 border-0">
                                                <div class="form-check d-inline-block pe-2">
                                                    <label for="day-wed-{{$key}}">Wednesday</label>
                                                    <input name="day[{{$key}}][]" type="checkbox" value="Wed" {{in_array("Wed", $selected_week_days) ? "checked":""}} id="day-wed-{{$key}}" class="form-check-input">
                                                </div>
                                            </td>
                                            </tr>
                                            <tr class="p-0">
                                                <td class="p-0 border-0"></td>
                                                <td class="p-0 border-0">
                                                <div class="form-check d-inline-block pe-2">
                                                    <label for="day-thu-{{$key}}">Thursday</label>
                                                    <input name="day[{{$key}}][]" type="checkbox" value="Thu" {{in_array("Thu", $selected_week_days) ? "checked":""}} id="day-thu-{{$key}}" class="form-check-input">
                                                </div>
                                            </td>
                                            <td class="p-0 border-0">
                                                <div class="form-check d-inline-block pe-2">
                                                    <label for="day-fri-{{$key}}">Friday</label>
                                                    <input name="day[{{$key}}][]" type="checkbox" value="Fri" {{in_array("Fri", $selected_week_days) ? "checked":""}} id="day-fri-{{$key}}" class="form-check-input">
                                                </div>
                                            </td>
                                            <td class="p-0 border-0">
                                                <div class="form-check d-inline-block pe-2">
                                                    <label for="day-sat-{{$key}}">Saturday</label>
                                                    <input name="day[{{$key}}][]" type="checkbox" value="Sat" {{in_array("Sat", $selected_week_days) ? "checked":""}} id="day-sat-{{$key}}" class="form-check-input">
                                                </div>
                                            </td>
                                            </tr>
                                            <tr class="p-0">
                                                <td class="p-0 border-0"></td>
                                                <td class="p-0 border-0">
                                                <div class="form-check d-inline-block pe-2">
                                                    <label for="day-sun-{{$key}}">Sunday</label>
                                                    <input name="day[{{$key}}][]" type="checkbox" value="Sun" {{in_array("Sun", $selected_week_days) ? "checked":""}} id="day-sun-{{$key}}" class="form-check-input">
                                                </div>
                                            </td>
                                            </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="slot_name-{{$key}}">Slot/Shift Name</label>
                                                <input placeholder="Slot/Shift Name" type="text" value="{{$item[0]->shift}}" class="form-control" name="slot[{{$key}}]" id="slot_name-{{$key}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="start_time-{{$key}}">Start Time</label>
                                                <input placeholder="Start Time" type="time" value="{{$item[0]->start_time}}" class="form-control" name="start_time[{{$key}}]" id="start_time-{{$key}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="end_time-{{$key}}">End Time</label>
                                                <input placeholder="End Time" type="time" class="form-control" name="end_time[{{$key}}]" id="end_time-{{$key}}" value="{{$item[0]->end_time}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group pt-4 mt-2">
                                                <button class="btn btn-sm btn-danger btnRemoveTimeTableRow" type="button" data-del-row="row-id-{{$key}}">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
    $(document).ready(function () {
        let count = $("#time-table-container .row").length;
        $("#addTimeTableRowBtn").click(function(){
            
            $("#time-table-container").append(`<div class="row border-bottom border-2 mb-3 border-dark" id="row-id-${++count}">
                                    <div class="col-md-4">
                                   <table class="table p-0">
                                        <tbody>
                                            <tr class="p-0">
                                                <td class="p-0 border-0"><span class="rowOrder badge bg-info"></span></td>
                                                 <td class="p-0 border-0">
                                            <div class="form-check d-inline-block pe-2">
                                            <label for="day-mon-${count}">Monday</label>
                                            <input name="day[${count}][]" type="checkbox" value="Mon" id="day-mon-${count}" 
                                            class="form-check-input">
                                        </div>
                                    </td>
                                    <td class="p-0 border-0">
                                        <div class="form-check d-inline-block pe-2">
                                            <label for="day-tue-${count}">Tuesday</label>
                                            <input name="day[${count}][]" type="checkbox" value="Tue" id="day-tue-${count}" class="form-check-input">
                                        </div>
                                    </td>
                                    <td class="p-0 border-0">
                                        <div class="form-check d-inline-block pe-2">
                                            <label for="day-wed-${count}">Wednesday</label>
                                            <input name="day[${count}][]" type="checkbox" value="Wed" id="day-wed-${count}" class="form-check-input">
                                        </div>
                                    </td>
                                    </tr>
                                    <tr class="p-0">
                                        <td class="p-0 border-0"></td>
                                        <td class="p-0 border-0">
                                        <div class="form-check d-inline-block pe-2">
                                            <label for="day-thu-${count}">Thursday</label>
                                            <input name="day[${count}][]" type="checkbox" value="Thu" id="day-thu-${count}" class="form-check-input">
                                        </div>
                                    </td>
                                    <td class="p-0 border-0">
                                        <div class="form-check d-inline-block pe-2">
                                            <label for="day-fri-${count}">Friday</label>
                                            <input name="day[${count}][]" type="checkbox" value="Fri" id="day-fri-${count}" class="form-check-input">
                                        </div>
                                    </td>
                                    <td class="p-0 border-0">
                                        <div class="form-check d-inline-block pe-2">
                                            <label for="day-sat-${count}">Saturday</label>
                                            <input name="day[${count}][]" type="checkbox" value="Sat" id="day-sat-${count}" class="form-check-input">
                                        </div>
                                    </td>
                                    </tr>
                                    <tr class="p-0">
                                        <td class="p-0 border-0"></td>
                                        <td class="p-0 border-0">
                                        <div class="form-check d-inline-block pe-2">
                                            <label for="day-sun-${count}">Sunday</label>
                                            <input name="day[${count}][]" type="checkbox" value="Sun" id="day-sun-${count}" class="form-check-input">
                                        </div>
                                    </td>
                                    </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="slot_name-${count}">Slot/Shift Name</label>
                                        <input placeholder="Slot/Shift Name" type="text" class="form-control" name="slot[${count}]" id="slot_name-${count}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="start_time-${count}">Start Time</label>
                                        <input placeholder="Start Time" type="time" class="form-control" name="start_time[${count}]" id="start_time-${count}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="end_time-${count}">End Time</label>
                                        <input placeholder="End Time" type="time" class="form-control" name="end_time[${count}]" id="end_time-${count}" required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group pt-4 mt-2">
                                        <button class="btn btn-sm btn-danger btnRemoveTimeTableRow" type="button" data-del-row="row-id-${count}">Remove</button>
                                    </div>
                                </div>
                            </div>`)
                            $("#time-table-container .row").each(getRowOrder);
        })
        $(document).on("click", ".btnRemoveTimeTableRow", function(){
            $("#"+$(this).data("del-row")).remove();
            $("#time-table-container .row").each(getRowOrder);
        })
        function getRowOrder(){
            let s_no = $(this).index();
            console.log(s_no)
            $(this).find(".rowOrder").html(s_no);
        }
    });
  </script>
<x-footer />