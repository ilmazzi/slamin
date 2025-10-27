@props([
    'user' => null,
    'showFollowButton' => true,
    'showMessageButton' => true,
    'cardClass' => 'col-lg-4 col-md-6 mb-3'
])

@if($user)
<div class="{{ $cardClass }}">
    <div class="card">
        <div class="card-body">
            <div class="profile-container cursor-pointer"
                onclick="window.location.href='{{ route('user.show', $user) }}'">
                <div class="image-details">
                    <div class="profile-image">
                        <img src="{{ $user->banner_image_url ?? asset('assets/images/avatar/default-banner.webp?v=1') }}"
                            alt="{{ $user->name }}" class="w-100 h-100 img-cover">
                    </div>
                    <div class="profile-pic">
                        <div class="avatar-upload">
                            <div class="avatar-preview">
                                <div id="imgPreview">
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                                        alt="{{ $user->name }}" class="w-100 h-100 img-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="person-details">
                    <h4 class="f-w-600 mb-1">{{ $user->name }}
                        @if ($user->nickname)
                            <span class="text-muted f-s-14 fw-normal">({{ $user->nickname }})</span>
                        @endif
                        @if ($user->verified)
                            <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/profile-app/01.png"
                                class="w-20 h-20" alt="instagram-check-mark">
                        @endif
                    </h4>
                    <p class="f-s-12 mb-3">{{ $user->city ?? __('home.location_not_specified') }}</p>
                    <div class="details">
                        <div>
                            <h4 class="text-primary">{{ $user->poems_count }}</h4>
                            <p class="text-secondary f-s-12">{{ __('common.poems') }}</p>
                        </div>
                        <div>
                            <h4 class="text-primary">{{ $user->articles_count }}</h4>
                            <p class="text-secondary f-s-12">{{ __('common.articles') }}</p>
                        </div>
                        <div>
                            <h4 class="text-primary">{{ number_format($user->total_interactions) }}</h4>
                            <p class="text-secondary f-s-12">{{ __('home.interactions') }}</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        @auth
                            @if($showFollowButton)
                            <button type="button"
                                class="btn {{ $user->is_followed_by_current_user ?? false ? 'btn-success' : 'btn-primary' }} btn-sm"
                                onclick="event.stopPropagation(); followUser({{ $user->id }})"
                                id="followBtn{{ $user->id }}">
                                <i
                                    class="ti {{ $user->is_followed_by_current_user ?? false ? 'ti-user-check' : 'ti-user' }} me-1"></i>
                                <span
                                    id="followText{{ $user->id }}">{{ $user->is_followed_by_current_user ?? false ? __('profile.following_label') : __('profile.follow_label') }}</span>
                            </button>
                            @endif
                            @if($showMessageButton)
                            <button type="button"
                                class="btn btn-secondary btn-sm"
                                onclick="event.stopPropagation(); startChat({{ $user->id }})"
                                id="messageBtn{{ $user->id }}">
                                <i class="ti ti-message-circle me-1"></i>
                                <span>{{ __('profile.send_message_button') }}</span>
                            </button>
                            @endif
                        @else
                            @if($showFollowButton)
                            <div class="btn btn-secondary btn-sm opacity-60">
                                <i class="ti ti-user me-1"></i>
                                {{ __('profile.follow_label') }}
                            </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
