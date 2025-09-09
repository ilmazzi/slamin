@props(['group'])

@php
    $socialLinks = [
        'website' => [
            'url' => $group->website,
            'icon' => 'ph-duotone ph-globe',
            'label' => 'Sito Web',
            'color' => 'btn-outline-primary'
        ],
        'social_facebook' => [
            'url' => $group->social_facebook,
            'icon' => 'ph-duotone ph-facebook-logo',
            'label' => 'Facebook',
            'color' => 'btn-facebook'
        ],
        'social_instagram' => [
            'url' => $group->social_instagram,
            'icon' => 'ph-duotone ph-instagram-logo',
            'label' => 'Instagram',
            'color' => 'btn-instagram'
        ],
        'social_youtube' => [
            'url' => $group->social_youtube,
            'icon' => 'ph-duotone ph-youtube-logo',
            'label' => 'YouTube',
            'color' => 'btn-youtube'
        ],
        'social_twitter' => [
            'url' => $group->social_twitter,
            'icon' => 'ph-duotone ph-twitter-logo',
            'label' => 'Twitter',
            'color' => 'btn-twitter'
        ],
        'social_tiktok' => [
            'url' => $group->social_tiktok,
            'icon' => 'ph-duotone ph-tiktok-logo',
            'label' => 'TikTok',
            'color' => 'btn-tiktok'
        ],
        'social_linkedin' => [
            'url' => $group->social_linkedin,
            'icon' => 'ph-duotone ph-linkedin-logo',
            'label' => 'LinkedIn',
            'color' => 'btn-linkedin'
        ]
    ];

    $availableLinks = array_filter($socialLinks, function($link) {
        return !empty($link['url']);
    });
@endphp

@if(count($availableLinks) > 0)
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="ph-duotone ph-share-network me-2 text-primary"></i>
            Segui sui social
        </h6>
    </div>
    <div class="card-body">
        @if(count($availableLinks) <= 4)
            <!-- Layout a griglia per pochi social -->
            <div class="row g-2">
                @foreach($availableLinks as $platform => $link)
                    @php
                        $url = $link['url'];
                        // Assicurati che l'URL abbia il protocollo
                        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                            $url = 'https://' . $url;
                        }
                    @endphp
                    <div class="col-6">
                        <a href="{{ $url }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="btn {{ $link['color'] }} btn-sm w-100 d-flex align-items-center justify-content-center"
                           title="{{ $link['label'] }}"
                           style="border-radius: 8px; padding: 8px 12px; transition: all 0.2s ease;">
                            <i class="{{ $link['icon'] }} f-s-16 me-2"></i>
                            <span class="small">{{ $link['label'] }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Layout compatto per molti social -->
            <div class="d-flex flex-wrap gap-2">
                @foreach($availableLinks as $platform => $link)
                    @php
                        $url = $link['url'];
                        // Assicurati che l'URL abbia il protocollo
                        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                            $url = 'https://' . $url;
                        }
                    @endphp
                    <a href="{{ $url }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="btn {{ $link['color'] }} btn-sm d-flex align-items-center"
                       title="{{ $link['label'] }}"
                       style="border-radius: 20px; padding: 6px 12px; transition: all 0.2s ease;">
                        <i class="{{ $link['icon'] }} f-s-14 me-1"></i>
                        <span class="small">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.group-social-links .btn {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.group-social-links .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.group-social-links .btn-facebook {
    background: linear-gradient(135deg, #1877f2, #42a5f5);
    color: white;
}

.group-social-links .btn-instagram {
    background: linear-gradient(135deg, #e4405f, #fd1d1d, #fcb045);
    color: white;
}

.group-social-links .btn-youtube {
    background: linear-gradient(135deg, #ff0000, #ff4444);
    color: white;
}

.group-social-links .btn-twitter {
    background: linear-gradient(135deg, #1da1f2, #0d8bd9);
    color: white;
}

.group-social-links .btn-tiktok {
    background: linear-gradient(135deg, #000000, #333333);
    color: white;
}

.group-social-links .btn-linkedin {
    background: linear-gradient(135deg, #0077b5, #005885);
    color: white;
}
</style>
@endif
