<div class="container-scroller">

  <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <!-- Brand Wrapper -->
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
      <button class="navbar-toggler-sidebar d-none d-lg-flex align-items-center justify-content-center me-2"
        onclick="document.body.classList.toggle('sidebar-icon-only')"
        style="background:none;border:1px solid #E5E7EB;border-radius:8px;width:32px;height:32px;cursor:pointer;color:#6B7280;flex-shrink:0;">
        <i class="fas fa-bars" style="font-size:13px;"></i>
      </button>
      <!-- Full logo (shown when sidebar is expanded) -->
      <a class="navbar-brand brand-logo d-flex align-items-center text-decoration-none"
        href="{{route('dashboard_view')}}">
        <img src="assets/images/logo.png" alt="AttendWise" style="height:34px;width:auto;object-fit:contain;">
      </a>
      <!-- Mini logo (shown when sidebar is collapsed / icon-only) -->
      <a class="navbar-brand brand-logo-mini d-flex align-items-center text-decoration-none"
        href="{{route('dashboard_view')}}">
        <!-- <img src="assets/images/logo.png" alt="AttendWise" style="height:32px;width:32px;object-fit:contain;"> -->
      </a>
    </div>

    <!-- Navbar menu wrapper -->
    <div class="navbar-menu-wrapper d-flex align-items-center">

      <!-- Hamburger for mobile -->
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
        data-bs-toggle="offcanvas" style="background:none;border:1px solid #E5E7EB;border-radius:8px;padding:6px 10px;">
        <i class="fas fa-bars" style="font-size:14px;color:#6B7280;"></i>
      </button>

      <!-- Breadcrumb -->
      <div class="breadcrumb pb-0 border-0 me-auto d-none d-md-flex align-items-center" style="gap:4px;">
        <?php $path = ""; ?>
        @foreach (Request::segments() as $item)
        <?php $path .= $item."/"; ?>
        @if (strlen($item) < 100) <span style="color:#9CA3AF;font-size:12px;">/</span>
          <a href="javascript:void(0)" class="text-capitalize text-decoration-none breadcrumb-item"
            style="font-size:13px;color:#6B7280;font-weight:500;">{{ str_replace("-", " ", $item) }}</a>
          @endif
          @endforeach
      </div>

      <!-- Right-side nav icons -->
      <ul class="navbar-nav ms-auto d-flex align-items-center" style="gap:4px;">

        <!-- Notifications -->
        <li class="nav-item dropdown">
          <a class="nav-link d-flex align-items-center justify-content-center" id="notificationDropdown" href="#"
            data-bs-toggle="dropdown"
            style="width:36px;height:36px;border-radius:8px;border:1px solid #E5E7EB;color:#6B7280;">
            <i class="fas fa-bell" style="font-size:14px;"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
            aria-labelledby="notificationDropdown" style="min-width:320px;padding:8px;">
            <div style="padding:8px 12px 12px;border-bottom:1px solid #F3F4F6;margin-bottom:4px;">
              <h6 style="margin:0;font-size:13.5px;font-weight:700;color:#111827;">Notifications</h6>
            </div>
            @php
            $notifications = \App\Models\InstitutionNotification::latest()->take(5)->get();
            @endphp
            @forelse($notifications as $notification)
            <a class="dropdown-item preview-item py-2" href="javascript:void(0)" style="border-radius:8px;">
              <div class="preview-item-content">
                <div style="display:flex;align-items:flex-start;gap:10px;">
                  <div
                    style="width:32px;height:32px;background:#EBF2FF;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-bell" style="font-size:12px;color:#0061FF;"></i>
                  </div>
                  <div>
                    <p
                      style="margin:0;font-size:13px;font-weight:{{ !$notification->is_read ? '700' : '500' }};color:#111827;line-height:1.4;">
                      {{ $notification->title }}</p>
                    <p style="margin:0;font-size:12px;color:#6B7280;margin-top:2px;">{{ $notification->message }}</p>
                  </div>
                </div>
              </div>
            </a>
            @empty
            <div style="padding:20px;text-align:center;">
              <i class="fas fa-bell-slash" style="font-size:24px;color:#D1D5DB;display:block;margin-bottom:8px;"></i>
              <p style="margin:0;font-size:13px;color:#9CA3AF;">No notifications</p>
            </div>
            @endforelse
          </div>
        </li>

        <!-- User dropdown -->
        <li class="nav-item dropdown d-none d-lg-flex">
          <a class="nav-link d-flex align-items-center" id="UserDropdown" href="#" data-bs-toggle="dropdown"
            aria-expanded="false"
            style="gap:8px;padding:4px 8px;border-radius:8px;border:1px solid #E5E7EB;height:36px;">
            <div
              style="width:24px;height:24px;background:linear-gradient(135deg,#0061FF,#00C6AE);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="fas fa-user" style="font-size:10px;color:#fff;"></i>
            </div>
            <span
              style="font-size:13px;font-weight:600;color:#374151;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{$user->name}}</span>
            <i class="fas fa-chevron-down" style="font-size:10px;color:#9CA3AF;"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end navbar-dropdown" aria-labelledby="UserDropdown"
            style="min-width:220px;margin-top:4px;">
            <div style="padding:12px 16px;border-bottom:1px solid #F3F4F6;margin-bottom:4px;">
              <p style="margin:0;font-size:13.5px;font-weight:700;color:#111827;">{{$user->name}}</p>
              <p style="margin:0;font-size:12px;color:#6B7280;margin-top:1px;">{{$user->email}}</p>
            </div>
            <a class="dropdown-item" href="{{route('profile_view')}}">
              <i class="fas fa-user-circle" style="width:16px;color:#6B7280;"></i> My Profile
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="javascript:void(0)"
              onclick="GlobalDialog.confirm({ title:'Sign Out?', message:'Are you sure you want to log out?', okText:'Sign Out', cancelText:'Cancel', variantOk:'danger' }).then(c => { if(c) window.location.href='logout'; });"
              style="color:#F04438 !important;">
              <i class="fas fa-sign-out-alt" style="width:16px;color:#F04438;"></i> Sign Out
            </a>
          </div>
        </li>

        <!-- Mobile logout quick btn -->
        <li class="nav-item d-flex d-lg-none">
          <a href="javascript:void(0)"
            onclick="GlobalDialog.confirm({ title:'Logout?', message:'Do you really want to logout?', okText:'Logout', cancelText:'Cancel', variantOk:'danger' }).then(c => { if(c) window.location.href='logout'; });"
            class="nav-link d-flex align-items-center justify-content-center"
            style="width:36px;height:36px;border-radius:8px;border:1px solid #FECDCA;background:#FEF3F2;color:#F04438;">
            <i class="fas fa-sign-out-alt" style="font-size:14px;"></i>
          </a>
        </li>

      </ul>
    </div>
  </nav>

  <div class="container-fluid page-body-wrapper">

    {{-- <div class="theme-setting-wrapper">
      <div id="settings-trigger"><i class="ti-settings"></i></div>
      <div id="theme-settings" class="settings-panel">
        <i class="settings-close ti-close"></i>
        <p class="settings-heading">SIDEBAR SKINS</p>
        <div class="sidebar-bg-options selected" id="sidebar-light-theme">
          <div class="img-ss rounded-circle bg-light border me-3"></div>Light
        </div>
        <div class="sidebar-bg-options" id="sidebar-dark-theme">
          <div class="img-ss rounded-circle bg-dark border me-3"></div>Dark
        </div>
        <p class="settings-heading mt-2">HEADER SKINS</p>
        <div class="color-tiles mx-0 px-4">
          <div class="tiles success"></div>
          <div class="tiles warning"></div>
          <div class="tiles danger"></div>
          <div class="tiles info"></div>
          <div class="tiles dark"></div>
          <div class="tiles default"></div>
        </div>
      </div>
    </div> --}}
    <div id="right-sidebar" class="settings-panel">
      <i class="settings-close ti-close"></i>
      <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab"
            aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab"
            aria-controls="chats-section">CHATS</a>
        </li>
      </ul>
      <div class="tab-content" id="setting-content">
        <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel"
          aria-labelledby="todo-section">
          <div class="add-items d-flex px-3 mb-0">
            <form class="form w-100">
              <div class="form-group d-flex">
                <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
              </div>
            </form>
          </div>
          <div class="list-wrapper px-3">
            <ul class="d-flex flex-column-reverse todo-list">
              <li>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox">
                    Team review meeting at 3.00 PM
                  </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
              <li>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox">
                    Prepare for presentation
                  </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
              <li>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox">
                    Resolve all the low priority tickets due today
                  </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
              <li class="completed">
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox" checked>
                    Schedule meeting for next week
                  </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
              <li class="completed">
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox" checked>
                    Project review
                  </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
            </ul>
          </div>
          <h4 class="px-3 text-muted mt-5 fw-light mb-0">Events</h4>
          <div class="events pt-4 px-3">
            <div class="wrapper d-flex mb-2">
              <i class="ti-control-record text-primary me-2"></i>
              <span>Feb 11 2018</span>
            </div>
            <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
            <p class="text-gray mb-0">The total number of sessions</p>
          </div>
          <div class="events pt-4 px-3">
            <div class="wrapper d-flex mb-2">
              <i class="ti-control-record text-primary me-2"></i>
              <span>Feb 7 2018</span>
            </div>
            <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
            <p class="text-gray mb-0 ">Call Sarah Graves</p>
          </div>
        </div>

        <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
          <div class="d-flex align-items-center justify-content-between border-bottom">
            <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
            <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 fw-normal">See All</small>
          </div>
          <ul class="chat-list">
            <li class="list active">
              <div class="profile"><img src="assets/images/faces/face1.jpg" alt="image"><span class="online"></span>
              </div>
              <div class="info">
                <p>Thomas Douglas</p>
                <p>Available</p>
              </div>
              <small class="text-muted my-auto">19 min</small>
            </li>
            <li class="list">
              <div class="profile"><img src="assets/images/faces/face2.jpg" alt="image"><span class="offline"></span>
              </div>
              <div class="info">
                <div class="wrapper d-flex">
                  <p>Catherine</p>
                </div>
                <p>Away</p>
              </div>
              <div class="badge badge-success badge-pill my-auto mx-2">4</div>
              <small class="text-muted my-auto">23 min</small>
            </li>
            <li class="list">
              <div class="profile"><img src="assets/images/faces/face3.jpg" alt="image"><span class="online"></span>
              </div>
              <div class="info">
                <p>Daniel Russell</p>
                <p>Available</p>
              </div>
              <small class="text-muted my-auto">14 min</small>
            </li>
            <li class="list">
              <div class="profile"><img src="assets/images/faces/face4.jpg" alt="image"><span class="offline"></span>
              </div>
              <div class="info">
                <p>James Richardson</p>
                <p>Away</p>
              </div>
              <small class="text-muted my-auto">2 min</small>
            </li>
            <li class="list">
              <div class="profile"><img src="assets/images/faces/face5.jpg" alt="image"><span class="online"></span>
              </div>
              <div class="info">
                <p>Madeline Kennedy</p>
                <p>Available</p>
              </div>
              <small class="text-muted my-auto">5 min</small>
            </li>
            <li class="list">
              <div class="profile"><img src="assets/images/faces/face6.jpg" alt="image"><span class="online"></span>
              </div>
              <div class="info">
                <p>Sarah Graves</p>
                <p>Available</p>
              </div>
              <small class="text-muted my-auto">47 min</small>
            </li>
          </ul>
        </div>

      </div>
    </div>


    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">


        @foreach ($all_permissions as $perm)
        @if (!in_array($perm->route_name, $allowed_permissions))

        @else
        @if (App\Models\AdminPermission::where(["deleted_at"=>null, "status"=>"1",
        "action"=>"R","parent_id"=>$perm->id])->count() > 0)

        @php
        $collapseId = Str::slug($perm->name, '_');
        @endphp

        <li class="nav-item">
          <a class="nav-link menu-dropdown collapsed" data-bs-toggle="collapse" href="#{{$collapseId}}"
            aria-expanded="false" aria-controls="{{$collapseId}}">
            <i class="menu-icon  fas {{$perm->icon}}"></i>
            <span class="menu-title">{{$perm->name}}</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="{{$collapseId}}">
            <ul class="nav flex-column sub-menu">
              @foreach (App\Models\AdminPermission::where(["deleted_at"=>null, "status"=>"1",
              "action"=>"R","parent_id"=>$perm->id])->get() as $item)
              @if (in_array($item->route_name, $allowed_permissions))
              <li class="nav-item"> <a class="nav-link dropdown-anchor" href="<?php
                          try {
                            echo route($item->route_name);
                          } catch (\Throwable $th) {
                            echo " $item->route_name";
                  }
                  ?>">{{$item->name}}</a></li>
              @endif
              @endforeach
            </ul>
          </div>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="<?php
                    try {
                      echo route($perm->route_name);
                    } catch (\Throwable $th) {
                      echo "";
                    }
                  ?>">
            <i class=" fas {{$perm->icon}} menu-icon"></i>
            <span class="menu-title">{{$perm->name}}</span>
          </a>
        </li>
        @endif
        @endif
        @endforeach
      </ul>
    </nav>
    <div class="main-panel">
      <div class="content-wrapper">