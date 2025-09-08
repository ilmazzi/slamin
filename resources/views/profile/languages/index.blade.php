@extends('layout.master')

@section('title', __('languages.title'))

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}">
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-translate me-2 text-primary"></i>
                        {{ __('languages.title') }}
                    </h4>
                    <a href="{{ route('profile.languages.create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        {{ __('languages.add_language') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messaggi Flash -->
    @if(session('success'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-x-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Lista Lingue -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($languages->count() > 0)
                        <div class="row">
                            @foreach($languages->groupBy('language_code') as $languageCode => $languageGroup)
                                <div class="col-12 col-md-6 col-lg-4 mb-3">
                                    <div class="card hover-effect equal-card">
                                        <div class="card-body">
                                            <!-- Nome Lingua -->
                                            <h5 class="card-title mb-3">
                                                {!! \App\Helpers\FlagHelper::getFlagIconWithName($languageGroup->first()->language_code, $languageGroup->first()->language_name) !!}
                                            </h5>

                                            <!-- Competenze -->
                                            <div class="mb-3">
                                                @foreach($languageGroup as $language)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-{{ $language->type === 'native' ? 'success' : ($language->type === 'spoken' ? 'info' : 'warning') }}">
                                                            {{ $language->competence_description }}
                                                        </span>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('profile.languages.edit', $language) }}"
                                                               class="btn btn-outline-primary btn-sm"
                                                               title="{{ __('common.edit') }}">
                                                                <i class="ph-duotone ph-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('profile.languages.destroy', $language) }}"
                                                                  method="POST"
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('{{ __('languages.delete_confirm') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        title="{{ __('common.delete') }}">
                                                                    <i class="ph-duotone ph-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate text-muted f-s-64 mb-3"></i>
                            <h5 class="text-muted">{{ __('languages.no_languages') }}</h5>
                            <p class="text-muted mb-4">{{ __('languages.no_languages_description') }}</p>
                            <a href="{{ route('profile.languages.create') }}" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-2"></i>
                                {{ __('languages.add_first_language') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
