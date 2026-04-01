<x-structure />
<x-header heading="{{$title}}" />
<div class="col-lg-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body table-card-body">
      <div class="text-end mb-2">
        <x-btn-add route="institution.department.add.view">
          <button onclick="openMscFormModal({
                title: 'Add Department',
                action: '{{route('institution.department.create')}}'
              })" class="btn btn-primary">
            Add Department
          </button>
        </x-btn-add>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-bordered msc-smart-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($departments as $item)
            <tr>
              <td>
                {{$item->name}}
                @if($item->is_additional)
                <span class="badge badge-info ms-1">Additional</span>
                @endif
              </td>
              <td>
                <x-btn-edit id="{{Crypt::encrypt($item->id)}}" route="institution.department.edit.view">
                  <button onclick="openMscFormModal({
                                title: 'Edit Department',
                                action: '{{route('institution.department.update', ['id'=>Crypt::encrypt($item->id)])}}',
                                data: {
                                  name: '{{$item->name}}',
                                  description: '{{$item->description}}',
                                  is_additional: '{{$item->is_additional}}'
                                }
                              })" class="btn btn-edit btn-primary">
                    <i class="far fa-edit"></i> Edit
                  </button>
                </x-btn-edit>
                <x-btn-delete id="{{Crypt::encrypt($item->id)}}" route="institution.department.delete" />
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<style>
  /* ===== Modern Modal ===== */
  .msc-modal {
    border-radius: 16px;
    border: none;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
    overflow: hidden;
  }

  /* Header */
  .msc-modal-header {
    padding: 1.25rem 1.5rem;
    border-bottom: none;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
  }

  /* Footer */
  .msc-modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #f1f1f1;
    background-color: #fafafa;
  }

  /* Labels */
  .msc-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.35rem;
  }

  /* Inputs */
  .msc-input {
    border-radius: 10px;
    padding: 0.6rem 0.75rem;
    border: 1px solid #e0e0e0;
    transition: all 0.2s ease;
  }

  .msc-input:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
  }

  /* Buttons */
  .modal-footer .btn {
    border-radius: 10px;
    font-weight: 500;
  }

  /* Subtle animation */
  .modal.fade .modal-dialog {
    transform: scale(0.95);
    transition: transform 0.25s ease-out;
  }

  .modal.show .modal-dialog {
    transform: scale(1);
  }
</style>
<div class="modal fade" id="mscFormModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content msc-modal">

      <!-- Header -->
      <div class="modal-header msc-modal-header">
        <div>
          <h5 class="modal-title" id="mscFormModalTitle">Add Department</h5>
          <small class="text-muted">Fill the details below</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form -->
      <form id="mscForm" class="msc-ord-form" method="POST" data-callback="reloadPage()">
        @csrf

        <!-- Body -->
        <div class="modal-body">
          <div class="row g-4">

            <div class="col-md-12">
              <label class="msc-label">Department Name</label>
              <input type="text" name="name" class="form-control msc-input" placeholder="e.g. Computer Science"
                required>
            </div>
            <div class="col-md-12">
              <label class="msc-label">Description</label>
              <textarea name="description" class="form-control msc-input" rows="3"
                placeholder="Optional description"></textarea>
            </div>
            <div class="col-md-12">
              <div class="form-check form-check-primary">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="is_additional" value="1">
                  Additional Department
                </label>
              </div>
            </div>

          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer msc-modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary px-4">
            Save
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
  function openMscFormModal(options = {}) {
    const modalEl = document.getElementById('mscFormModal');
    const modal = new bootstrap.Modal(modalEl);

    // Set title
    document.getElementById('mscFormModalTitle').innerText =
      options.title || 'Form';

    // Set form action
    const form = document.getElementById('mscForm');
    form.action = options.action || '#';

    // Reset form (important)
    form.reset();

    // Populate values (for edit)
    if (options.data) {
      Object.keys(options.data).forEach(key => {
        const el = form.querySelector(`[name="${key}"]`);
        if (el) {
          if (el.type === 'checkbox') {
            el.checked = (options.data[key] == 1);
          } else {
            el.value = options.data[key];
          }
        }
      });
    }

    modal.show();
  }
  reloadPage = function () {
    // setTimeout(() => {
    location.reload();
    // }, 1000);
  }
</script>
<x-footer />