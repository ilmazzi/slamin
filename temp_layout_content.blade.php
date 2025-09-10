                        <!-- Layout Articles - Editor Controlled -->
                        
                        <!-- Featured Article 1 (Horizontal) -->
                        @if(isset($layoutArticles['horizontal1']))
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    @php $article = $layoutArticles['horizontal1']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($article->featured_image_url)
                                                <img src="{{ $article->featured_image_url }}"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="{{ $article->title }}">
                                            @else
                                                {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 250, 'card-img-top', route('articles.show', $article->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $article->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($article->excerpt, 150) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $article->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $article->published_at->format('d/m/Y') }}</span>
                                                    <x-social-view-counter :content="$article" type="article" size="sm" />
                                                    <x-social-like-button :content="$article" type="article" size="sm" />
                                                    <x-social-comment-button :content="$article" type="article" size="sm" />
                                                </div>
                                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-primary">
                                                    {{ __('articles.read_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Column Articles 1 & 2 -->
                        @if(isset($layoutArticles['column1']) || isset($layoutArticles['column2']))
                            <div class="row g-3 mb-4">
                                @if(isset($layoutArticles['column1']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column1']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(isset($layoutArticles['column2']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column2']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Featured Article 2 (Horizontal) -->
                        @if(isset($layoutArticles['horizontal2']))
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    @php $article = $layoutArticles['horizontal2']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($article->featured_image_url)
                                                <img src="{{ $article->featured_image_url }}"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="{{ $article->title }}">
                                            @else
                                                {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $article->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($article->excerpt, 150) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $article->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $article->published_at->format('d/m/Y') }}</span>
                                                    <x-social-view-counter :content="$article" type="article" size="sm" />
                                                    <x-social-like-button :content="$article" type="article" size="sm" />
                                                    <x-social-comment-button :content="$article" type="article" size="sm" />
                                                </div>
                                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-primary">
                                                    {{ __('articles.read_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Column Articles 3 & 4 -->
                        @if(isset($layoutArticles['column3']) || isset($layoutArticles['column4']))
                            <div class="row g-3 mb-4">
                                @if(isset($layoutArticles['column3']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column3']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(isset($layoutArticles['column4']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column4']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Featured Article 3 (Horizontal) -->
                        @if(isset($layoutArticles['horizontal3']))
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    @php $article = $layoutArticles['horizontal3']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($article->featured_image_url)
                                                <img src="{{ $article->featured_image_url }}"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="{{ $article->title }}">
                                            @else
                                                {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $article->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($article->excerpt, 150) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $article->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $article->published_at->format('d/m/Y') }}</span>
                                                    <x-social-view-counter :content="$article" type="article" size="sm" />
                                                    <x-social-like-button :content="$article" type="article" size="sm" />
                                                    <x-social-comment-button :content="$article" type="article" size="sm" />
                                                </div>
                                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-primary">
                                                    {{ __('articles.read_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Column Articles 5 & 6 -->
                        @if(isset($layoutArticles['column5']) || isset($layoutArticles['column6']))
                            <div class="row g-3 mb-4">
                                @if(isset($layoutArticles['column5']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column5']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(isset($layoutArticles['column6']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column6']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
