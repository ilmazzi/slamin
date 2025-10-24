<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                <i class="ph-duotone ph-video f-s-24 text-primary"></i>
                            </div>
                            <h4 class="mb-1 f-w-600 text-primary">{{ number_format($stats['total_videos']) }}</h4>
                            <p class="text-muted f-s-12 mb-0">{{ __('home.statistics.videos') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-light-success rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                <i class="ph-duotone ph-calendar f-s-24 text-success"></i>
                            </div>
                            <h4 class="mb-1 f-w-600 text-success">{{ number_format($stats['total_events']) }}</h4>
                            <p class="text-muted f-s-12 mb-0">{{ __('home.statistics.events') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-light-info rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                <i class="ph-duotone ph-users f-s-24 text-info"></i>
                            </div>
                            <h4 class="mb-1 f-w-600 text-info">{{ number_format($stats['total_users']) }}</h4>
                            <p class="text-muted f-s-12 mb-0">{{ __('home.statistics.users') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="d-flex flex-column align-items-center">
                            <div class="bg-light-warning rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                <i class="ph-duotone ph-eye f-s-24 text-warning"></i>
                            </div>
                            <h4 class="mb-1 f-w-600 text-warning">{{ number_format($stats['total_views']) }}</h4>
                            <p class="text-muted f-s-12 mb-0">{{ __('home.statistics.views') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
