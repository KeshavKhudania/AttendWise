<x-structure />
<x-header heading="Blocks" />

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body table-card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Blocks</h5>
                <x-btn-add route="institution.blocks.add.view" />
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered msc-smart-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Block Name</th>
                            <th>Created At</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blocks as $block)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $block->name }}</td>
                                <td>{{ $block->created_at?->format('d M Y') }}</td>
                                <td>
                                    <x-btn-edit 
                                        route="institution.blocks.edit.view" 
                                        id="{{ Crypt::encrypt($block->id) }}" 
                                    />
                                    <x-btn-delete 
                                        route="institution.blocks.delete" 
                                        id="{{ Crypt::encrypt($block->id) }}" 
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr class="empty">
                                <td colspan="5" class="text-center text-muted">
                                    No blocks found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>

<x-footer />
