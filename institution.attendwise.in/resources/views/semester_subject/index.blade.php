<x-structure />
<x-header heading="Semester Subject Mapping" />
<style>
  .card:hover {
    transform: translateY(-2px);
    transition: 0.2s ease;
  }

  .dropdown-menu {
    font-size: 14px;
  }

  <style>

  /* Light gradient background (subtle) */
  .modern-card {
    background: linear-gradient(135deg, rgba(102, 126, 234, .08) 0%, rgba(255, 255, 255, 1) 55%, rgba(118, 75, 162, .06) 100%);
    box-shadow: 0 .5rem 1.2rem rgba(16, 24, 40, .08);
    transition: transform .25s ease, box-shadow .25s ease;
  }

  .modern-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 1rem 2.2rem rgba(16, 24, 40, .14);
  }

  /* thin top accent */
  .modern-accent {
    height: 4px;
    background: linear-gradient(90deg, rgba(102, 126, 234, .35), rgba(118, 75, 162, .25));
  }

  .modern-menu-btn {
    width: 34px;
    height: 34px;
    background: rgba(255, 255, 255, .85);
    backdrop-filter: blur(6px);
  }

  .modern-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, rgba(102, 126, 234, .14), rgba(118, 75, 162, .10));
    border: 1px solid rgba(102, 126, 234, .18);
  }

  .modern-icon-i {
    font-size: 20px;
    color: #5a67d8;
    /* soft indigo */
  }

  .modern-title {
    color: #1f2937;
    line-height: 1.35;
  }

  .modern-label {
    font-size: .7rem;
    letter-spacing: .6px;
  }

  .modern-sem-badge {
    background: rgba(102, 126, 234, .12);
    color: #3949ab;
    border: 1px solid rgba(102, 126, 234, .18);
    font-weight: 600;
  }

  .modern-subject-badge {
    background: rgba(255, 255, 255, .75);
    border: 1px solid rgba(148, 163, 184, .45);
    color: #334155;
    font-weight: 600;
  }

  .modern-footer {
    background: rgba(255, 255, 255, .55);
    backdrop-filter: blur(6px);
  }

  .modern-link {
    color: #5a67d8;
    font-weight: 600;
  }

  .dropdown-item:hover {
    background-color: rgba(102, 126, 234, .08);
  }
</style>
</style>
<div class="container-fluid pt-3">

  <a href="{{ route('institution.subject.manage.mapping.add.view') }}" class="btn btn-primary mb-3">
    <i class="fa fa-plus me-1"></i> Add New Mapping

  </a>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">
      Semester-wise subject structure for courses
    </p>
  </div>

  <div class="row g-3">

    @forelse($groups as $group)

    <div class="col-xl-4 col-lg-4 col-md-6">
      <div class="card h-100 border-0 rounded-4 position-relative overflow-hidden modern-card">

        <!-- Soft top accent -->
        <div class="position-absolute top-0 start-0 w-100 modern-accent"></div>

        <!-- Three-dot menu -->
        <div class="dropdown position-absolute top-0 end-0 p-3" style="z-index: 10;">
          <button
            class="btn btn-sm btn-light rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center modern-menu-btn"
            data-bs-toggle="dropdown">
            <i class="fa fa-ellipsis-v text-muted"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
            <li>
              <a class="dropdown-item rounded-2 py-2"
                href="{{ route('institution.subject.manage.mapping.edit.view', Crypt::encrypt($group->id)) }}">
                <i class="fa fa-edit me-2 text-primary"></i> Edit
              </a>
            </li>
            <li>
              <hr class="dropdown-divider my-1">
            </li>
            <li>
              <form method="POST"
                action="{{ route('institution.subject.manage.mapping.delete', Crypt::encrypt($group->id)) }}">
                @csrf @method('DELETE')
                <button class="dropdown-item text-danger rounded-2 py-2">
                  <i class="fa fa-trash me-2"></i> Delete
                </button>
              </form>
            </li>
          </ul>
        </div>

        <!-- Card Body -->
        <div class="card-body pt-4 pb-3">
          <!-- Soft icon badge -->
          <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3 modern-icon">
            <i class="fa fa-graduation-cap modern-icon-i"></i>
          </div>

          <h5 class="fw-bold mb-2 modern-title">
            {{ $group->course->name }}
          </h5>

          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="badge rounded-pill px-3 py-2 modern-sem-badge">
              Semester {{ $group->semester }}
            </span>
            <small class="text-muted">{{ $group->department->name }}{{ $group->department->is_additional ? '
              (Additional)' : '' }}</small>
          </div>

          <small class="text-uppercase fw-semibold text-muted d-block mb-2 modern-label">
            Mapped Subjects
          </small>

          <div class="d-flex flex-wrap gap-2">
            @foreach((array)$group->subjects as $subjectId)
            @php $subject = $subjectsIndex[$subjectId] ?? null; @endphp
            @if($subject)
            <span class="badge rounded-pill px-3 py-2 modern-subject-badge">
              {{ $subject->code }}
            </span>
            @endif
            @endforeach
          </div>
        </div>

        <!-- Footer -->
        <div class="card-footer border-0 py-3 modern-footer">
          <div class="d-flex align-items-center justify-content-between">
            <small class="text-muted d-flex align-items-center gap-2">
              <i class="fa fa-book" style="font-size: 0.85rem;"></i>
              <span class="fw-semibold">{{ count((array)$group->subjects) }} subjects</span>
            </small>

            <a href="{{ route('institution.subject.manage.mapping.edit.view', Crypt::encrypt($group->id)) }}"
              class="btn btn-sm btn-link text-decoration-none p-0 modern-link">
              View Details <i class="fa fa-arrow-right ms-1" style="font-size: 0.75rem;"></i>
            </a>
          </div>
        </div>

      </div>
    </div>

    @empty

    <div class="col-12">
      <div class="text-center py-5 text-muted">
        <i class="fa fa-layer-group fa-2x mb-2"></i>
        <p>No semester mappings created yet.</p>
      </div>
    </div>

    @endforelse

  </div>
</div>

<x-footer />