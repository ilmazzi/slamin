@extends('layout.master')

@section('title', __('groups.invite_members') . ' - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-envelope me-2 text-success"></i>
                        {{ __('groups.invite_members') }} - {{ $group->name }}
                    </h4>
                    <a href="{{ route('groups.members.index', $group) }}" class="btn btn-light">
                        <i class="ph-duotone ph-arrow-left me-2"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('groups.invitations.store', $group) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('groups.invite_email') }} *</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           placeholder="{{ __('groups.invite_email_placeholder') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('groups.invite_email_help') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="message" class="form-label">{{ __('groups.invite_message') }}</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" 
                                              name="message" 
                                              rows="4" 
                                              placeholder="{{ __('groups.invite_message_placeholder') }}">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('groups.invite_message_help') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ph-duotone ph-paper-plane me-2"></i>
                                        {{ __('groups.send_invitation') }}
                                    </button>
                                    <a href="{{ route('groups.members.index', $group) }}" class="btn btn-light">
                                        {{ __('common.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Informazioni aggiuntive -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-light-info">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-info me-2 text-info"></i>
                        {{ __('groups.invitation_info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>{{ __('groups.invitation_info_1') }}</li>
                        <li>{{ __('groups.invitation_info_2') }}</li>
                        <li>{{ __('groups.invitation_info_3') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 