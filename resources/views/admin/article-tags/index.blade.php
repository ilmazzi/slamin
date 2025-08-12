@extends('layout.master')
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.tags_management') }}</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
                            <i class="ti ti-plus"></i> {{ __('articles.create_tag') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($tags->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('articles.name') }}</th>
                                        <th>{{ __('articles.description') }}</th>
                                        <th>{{ __('articles.articles_count') }}</th>
                                        <th>{{ __('articles.created') }}</th>
                                        <th>{{ __('articles.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tags as $tag)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-primary me-2">{{ $tag->name }}</span>
                                                    @if($tag->is_featured)
                                                        <span class="badge bg-warning">{{ __('articles.featured') }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ Str::limit($tag->description, 100) }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $tag->articles_count }}</span>
                                            </td>
                                            <td>{{ $tag->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="editTag({{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->description }}', {{ $tag->is_featured ? 'true' : 'false' }})">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <a href="{{ route('admin.article-tags.show', $tag) }}" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    @if($tag->articles_count == 0)
                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                onclick="deleteTag({{ $tag->id }}, '{{ $tag->name }}')">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($tags->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $tags->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-tag display-1 text-muted"></i>
                            <h5 class="mt-3">{{ __('articles.no_tags') }}</h5>
                            <p class="text-muted">{{ __('articles.no_tags_description') }}</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTagModal">
                                <i class="ti ti-plus"></i> {{ __('articles.create_first_tag') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Creazione Tag -->
<div class="modal fade" id="createTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.article-tags.store') }}" method="POST" id="createTagForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('articles.create_tag') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('articles.name') }} *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('articles.description') }}</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                        <label class="form-check-label" for="is_featured">
                            {{ __('articles.mark_as_featured') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('articles.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-plus"></i> {{ __('articles.create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifica Tag -->
<div class="modal fade" id="editTagModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editTagForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('articles.edit_tag') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">{{ __('articles.name') }} *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">{{ __('articles.description') }}</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_is_featured" name="is_featured" value="1">
                        <label class="form-check-label" for="edit_is_featured">
                            {{ __('articles.mark_as_featured') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('articles.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy"></i> {{ __('articles.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editTag(id, name, description, isFeatured) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_is_featured').checked = isFeatured;
    
    document.getElementById('editTagForm').action = `/admin/article-tags/${id}`;
    
    new bootstrap.Modal(document.getElementById('editTagModal')).show();
}

function deleteTag(id, name) {
    Swal.fire({
        title: '{{ __("articles.confirm_delete_tag") }}',
        text: `"${name}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("articles.delete") }}',
        cancelButtonText: '{{ __("articles.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/article-tags/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('{{ __("articles.tag_deleted") }}', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showNotification(data.message || '{{ __("articles.delete_error") }}', 'error');
                }
            })
            .catch(error => {
                showNotification('{{ __("articles.delete_error") }}', 'error');
            });
        }
    });
}

function showNotification(message, type = 'info') {
    Swal.fire({
        title: message,
        icon: type,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

// Gestione form di creazione
document.getElementById('createTagForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('{{ __("articles.tag_created") }}', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showNotification(data.message || '{{ __("articles.create_error") }}', 'error');
        }
    })
    .catch(error => {
        showNotification('{{ __("articles.create_error") }}', 'error');
    });
});

// Gestione form di modifica
document.getElementById('editTagForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('{{ __("articles.tag_updated") }}', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showNotification(data.message || '{{ __("articles.update_error") }}', 'error');
        }
    })
    .catch(error => {
        showNotification('{{ __("articles.update_error") }}', 'error');
    });
});
</script>
@endpush
