@extends('layout.master')

@section('title', 'Test Upload - Slamin')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600 text-dark">
                        <i class="ph ph-upload me-2"></i>
                        Test Upload Foto Profilo
                    </h5>
                </div>
                <div class="card-body pa-30">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="testForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label f-w-600">Seleziona Immagine</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*" required>
                            <small class="text-muted f-s-12">Formati supportati: Tutti i formati immagine (JPG, PNG, GIF, WebP, ecc.). Max {{ \App\Models\SystemSetting::get('profile_photo_max_size', 5120) / 1024 }}MB</small>
                        </div>

                        <button type="submit" class="btn btn-primary hover-effect">
                            <i class="ph ph-upload me-2"></i>Carica Immagine
                        </button>
                    </form>

                    <hr class="my-4">

                    <h6 class="f-w-600 mb-3">Test AJAX Upload</h6>
                    <div class="mb-3">
                        <label class="form-label f-w-600">Seleziona Immagine (AJAX)</label>
                        <input type="file" id="ajaxUpload" class="form-control" accept="image/*">
                        <small class="text-muted f-s-12">Questo testa l'upload AJAX</small>
                    </div>

                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.getElementById('ajaxUpload').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        console.log('File selezionato:', file);

        const formData = new FormData();
        formData.append('profile_photo', file);
        formData.append('_method', 'PUT');

        fetch('{{ route("profile.update") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            document.getElementById('result').innerHTML = `
                <div class="alert alert-${data.success ? 'success' : 'danger'}">
                    <strong>${data.success ? 'Successo' : 'Errore'}:</strong> ${data.message}
                    ${data.profile_photo_url ? `<br><img src="${data.profile_photo_url}" class="mt-2" style="max-width: 200px;">` : ''}
                </div>
            `;
        })
        .catch(error => {
            console.error('Errore:', error);
            document.getElementById('result').innerHTML = `
                <div class="alert alert-danger">
                    <strong>Errore:</strong> ${error.message}
                </div>
            `;
        });
    }
});
</script>
@endsection
