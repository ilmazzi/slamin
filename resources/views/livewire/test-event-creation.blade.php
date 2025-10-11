<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Test Event Creation</h5>
                </div>
                <div class="card-body">
                    <h1>Test Livewire Component</h1>
                    <p>Title: {{ $title }}</p>
                    <input type="text" wire:model.live="title" class="form-control" placeholder="Enter title">
                </div>
            </div>
        </div>
    </div>
</div>
