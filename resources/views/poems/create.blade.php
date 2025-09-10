@extends('layout.master')

@section('title', __('poems.create.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb start -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ __('poems.create.title') }}</h4>
            
        </div>
    </div>
    <!-- Breadcrumb end -->

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="ph ph-pen-nib text-primary me-2"></i>
                        {{ __('poems.create.subtitle') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('poems.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Titolo -->
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">{{ __('poems.fields.title') }}</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}"
                                       placeholder="{{ __('poems.create.title_placeholder') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Testo (Contenuto) - OBBLIGATORIO -->
                            <div class="col-12 mb-3">
                                <label for="content" class="form-label">{{ __('poems.fields.content') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="12"
                                          placeholder="{{ __('poems.create.content_placeholder') }}" required>{{ old('content') }}</textarea>
                                <small class="form-text text-muted">{{ __('poems.create.content_help') }}</small>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Immagine (Thumbnail) -->
                            <div class="col-12 mb-3">
                                <label for="thumbnail" class="form-label">{{ __('poems.fields.thumbnail') }}</label>
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                       id="thumbnail" name="thumbnail" accept="image/*">
                                <small class="form-text text-muted">{{ __('poems.create.thumbnail_help') }}</small>
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Categoria e Tipologia -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">{{ __('poems.fields.category') }}</label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="poem_type" class="form-label">{{ __('poems.fields.poem_type') }}</label>
                                <select class="form-select @error('poem_type') is-invalid @enderror" id="poem_type" name="poem_type">
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($poemTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('poem_type') == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('poem_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lingua e Tags -->
                            <div class="col-md-6 mb-3">
                                <label for="language" class="form-label">{{ __('poems.fields.language') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($languages as $key => $language)
                                        <option value="{{ $key }}" {{ old('language') == $key ? 'selected' : '' }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tags" class="form-label">{{ __('poems.fields.tags') }}</label>
                                <input type="text" class="form-control @error('tags') is-invalid @enderror"
                                       id="tags" name="tags" value="{{ old('tags') }}"
                                       placeholder="{{ __('poems.create.tags_placeholder') }}">
                                <small class="form-text text-muted">{{ __('poems.create.tags_help') }}</small>
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descrizione -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">{{ __('poems.fields.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3"
                                          placeholder="{{ __('poems.create.description_placeholder') }}">{{ old('description') }}</textarea>
                                <small class="form-text text-muted">{{ __('poems.create.description_help') }}</small>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Opzioni di pubblicazione -->
                            <div class="col-12">
                                <div class="card card-light-success">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ph ph-gear text-info me-2"></i>
                                            {{ __('poems.create.publication_options') }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1"
                                                           {{ old('is_public', true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_public">
                                                        {{ __('poems.fields.is_public') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('poems.create.public_help') }}</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_draft" name="is_draft" value="1"
                                                           {{ old('is_draft') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_draft">
                                                        {{ __('poems.fields.is_draft') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('poems.create.draft_help') }}</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="translation_job_available" name="translation_job_available" value="1"
                                                           {{ old('translation_job_available') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="translation_job_available">
                                                        {{ __('poems.fields.translation_job_available') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('poems.create.translation_job_help') }}</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="translation_base_price" class="form-label">{{ __('poems.fields.translation_base_price') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">€</span>
                                                    <input type="number" class="form-control @error('translation_base_price') is-invalid @enderror"
                                                           id="translation_base_price" name="translation_base_price"
                                                           value="{{ old('translation_base_price') }}" min="0" step="0.01">
                                                </div>
                                                @error('translation_base_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="translation_negotiable" name="translation_negotiable" value="1"
                                                           {{ old('translation_negotiable', true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="translation_negotiable">
                                                        {{ __('poems.fields.translation_negotiable') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('poems.create.translation_negotiable_help') }}</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="translation_instructions" class="form-label">{{ __('poems.fields.translation_instructions') }}</label>
                                                <textarea class="form-control @error('translation_instructions') is-invalid @enderror"
                                                          id="translation_instructions" name="translation_instructions" rows="3"
                                                          placeholder="{{ __('poems.create.translation_instructions_placeholder') }}">{{ old('translation_instructions') }}</textarea>
                                                @error('translation_instructions')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Traduzioni Ufficiali -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ph ph-translate text-primary me-2"></i>
                                            {{ __('poems.create.official_translations') }}
                                        </h5>
                                        <small class="text-muted">{{ __('poems.create.official_translations_help') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div id="translations-container">
                                            <!-- Le traduzioni verranno aggiunte dinamicamente qui -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <button type="button" class="btn btn-outline-primary" id="add-translation-btn">
                                                <i class="ph ph-plus me-2"></i>
                                                {{ __('poems.create.add_translation') }}
                                            </button>

                                            <small class="text-muted">
                                                <i class="ph ph-info me-1"></i>
                                                {{ __('poems.create.translation_note') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pulsanti -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('poems.index') }}" class="btn btn-secondary">
                                        <i class="ph ph-arrow-left me-2"></i>
                                        {{ __('common.cancel') }}
                                    </a>

                                    <div>
                                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                            <i class="ph ph-floppy-disk me-2"></i>
                                            {{ __('poems.create.save_draft') }}
                                        </button>

                                        <button type="submit" name="action" value="publish" class="btn btn-primary">
                                            <i class="ph ph-paper-plane me-2"></i>
                                            {{ __('poems.create.publish') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione del draft
    const draftCheckbox = document.getElementById('is_draft');
    const publicCheckbox = document.getElementById('is_public');

    if (draftCheckbox) {
        draftCheckbox.addEventListener('change', function() {
            if (this.checked) {
                publicCheckbox.checked = false;
            }
        });
    }

    if (publicCheckbox) {
        publicCheckbox.addEventListener('change', function() {
            if (this.checked) {
                draftCheckbox.checked = false;
            }
        });
    }

    // Preview del thumbnail
    const thumbnailInput = document.getElementById('thumbnail');
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Qui puoi aggiungere una preview del thumbnail se necessario
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Gestione traduzioni dinamiche
    let translationCount = 0;
    const translationsContainer = document.getElementById('translations-container');
    const addTranslationBtn = document.getElementById('add-translation-btn');
    // Array delle lingue supportate - versione semplificata per evitare errori di parsing
    const supportedLanguages = {
        // Lingue Europee
        'en': 'English',
        'es': 'Español',
        'fr': 'Français',
        'de': 'Deutsch',
        'it': 'Italiano',
        'pt': 'Português',
        'ru': 'Русский',
        'nl': 'Nederlands',
        'sv': 'Svenska',
        'no': 'Norsk',
        'da': 'Dansk',
        'fi': 'Suomi',
        'pl': 'Polski',
        'cs': 'Čeština',
        'sk': 'Slovenčina',
        'hu': 'Magyar',
        'ro': 'Română',
        'bg': 'Български',
        'hr': 'Hrvatski',
        'sr': 'Српски',
        'sl': 'Slovenščina',
        'et': 'Eesti',
        'lv': 'Latviešu',
        'lt': 'Lietuvių',
        'el': 'Ελληνικά',
        'tr': 'Türkçe',
        'uk': 'Українська',
        'be': 'Беларуская',
        'mk': 'Македонски',
        'sq': 'Shqip',
        'mt': 'Malti',
        'ga': 'Gaeilge',
        'cy': 'Cymraeg',
        'is': 'Íslenska',
        'fo': 'Føroyskt',
        'eu': 'Euskera',
        'ca': 'Català',
        'gl': 'Galego',

        // Lingue Asiatiche
        'zh': '中文',
        'ja': '日本語',
        'ko': '한국어',
        'th': 'ไทย',
        'vi': 'Tiếng Việt',
        'id': 'Bahasa Indonesia',
        'ms': 'Bahasa Melayu',
        'tl': 'Filipino',
        'hi': 'हिन्दी',
        'bn': 'বাংলা',
        'ur': 'اردو',
        'fa': 'فارسی',
        'he': 'עברית',
        'ar': 'العربية',
        'ku': 'Kurdî',
        'az': 'Azərbaycan',
        'ka': 'ქართული',
        'hy': 'Հայերեն',
        'uz': 'Oʻzbek',
        'kk': 'Қазақ',
        'ky': 'Кыргыз',
        'tg': 'Тоҷикӣ',
        'mn': 'Монгол',
        'my': 'မြန်မာ',
        'km': 'ខ្មែរ',
        'lo': 'ລາວ',
        'si': 'සිංහල',
        'ne': 'नेपाली',
        'ta': 'தமிழ்',
        'te': 'తెలుగు',
        'ml': 'മലയാളം',
        'kn': 'ಕನ್ನಡ',
        'gu': 'ગુજરાતી',
        'pa': 'ਪੰਜਾਬੀ',
        'or': 'ଓଡ଼ିଆ',
        'as': 'অসমীয়া',
        'mr': 'मराठी',
        'bo': 'བོད་ཡིག',
        'dz': 'རྫོང་ཁ',

        // Lingue Africane
        'sw': 'Kiswahili',
        'am': 'አማርኛ',
        'ha': 'Hausa',
        'yo': 'Yorùbá',
        'ig': 'Igbo',
        'zu': 'IsiZulu',
        'xh': 'IsiXhosa',
        'af': 'Afrikaans',
        'so': 'Soomaali',
        'om': 'Oromoo',
        'ti': 'ትግርኛ',
        'rw': 'Kinyarwanda',
        'rn': 'Kirundi',
        'lg': 'Luganda',
        'ny': 'Chichewa',
        'sn': 'ChiShona',
        'nd': 'IsiNdebele',
        'ss': 'SiSwati',
        'st': 'Sesotho',
        'tn': 'Setswana',
        've': 'Tshivenḓa',
        'ts': 'Xitsonga',
        'nr': 'IsiNdebele',
        'nso': 'Sesotho sa Leboa',

        // Lingue Americane
        'qu': 'Runa Simi',
        'ay': 'Aymar aru',
        'gn': 'Avañe\'ẽ',
        'nah': 'Nāhuatl',
        'yua': 'Maaya T\'aan',
        'iu': 'ᐃᓄᒃᑎᑐᑦ',
        'kl': 'Kalaallisut',

        // Lingue del Pacifico
        'haw': 'ʻŌlelo Hawaiʻi',
        'mi': 'Te Reo Māori',
        'sm': 'Gagana Samoa',
        'to': 'Lea fakatonga',
        'fj': 'Na Vosa Vakaviti',
        'ty': 'Reo Tahiti',
        'bi': 'Bislama',
        'tvl': 'Te \'gana Tuvalu',
        'gil': 'Taetae ni Kiribati',
        'na': 'Dorerin Naoero',
        'mh': 'Kajin M̧ajeļ',
        'ch': 'Chamoru',
        'pau': 'Tekoi er a Belau',
        'pon': 'Pohnpeian',
        'kos': 'Kosraean',
        'yap': 'Wa\'ab',
        'chk': 'Chuukese',
        'wls': 'Faka\'uvea',
        'niu': 'Ko e vagahau Niuē',
        'rap': 'Vananga Rapa Nui',
        'rar': 'Māori Kūki \'Āirani'
    };

    function addTranslationForm() {
        translationCount++;
        const translationId = `translation_${translationCount}`;

        const translationHtml = `
            <div class="card mb-3" id="${translationId}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ph ph-translate me-2"></i>
                        {{ __('poems.create.translation') }} #${translationCount}
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTranslation('${translationId}')">
                        <i class="ph ph-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="${translationId}_language" class="form-label">{{ __('poems.fields.language') }}</label>
                            <select class="form-select" id="${translationId}_language" name="translations[${translationCount}][language]" required>
                                <option value="">{{ __('common.select') }}</option>
                                ${Object.entries(supportedLanguages).map(([code, name]) =>
                                    `<option value="${code}">${name}</option>`
                                ).join('')}
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="${translationId}_title" class="form-label">{{ __('poems.fields.title') }}</label>
                            <input type="text" class="form-control" id="${translationId}_title"
                                   name="translations[${translationCount}][title]" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="${translationId}_content" class="form-label">{{ __('poems.fields.content') }}</label>
                        <textarea class="form-control" id="${translationId}_content"
                                  name="translations[${translationCount}][content]" rows="8" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="${translationId}_description" class="form-label">{{ __('poems.fields.description') }}</label>
                        <textarea class="form-control" id="${translationId}_description"
                                  name="translations[${translationCount}][description]" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="${translationId}_notes" class="form-label">{{ __('poems.fields.translation_notes') }}</label>
                        <textarea class="form-control" id="${translationId}_notes"
                                  name="translations[${translationCount}][notes]" rows="2"
                                  placeholder="{{ __('poems.create.translation_notes_placeholder') }}"></textarea>
                    </div>
                </div>
            </div>
        `;

        translationsContainer.insertAdjacentHTML('beforeend', translationHtml);
    }

    function removeTranslation(translationId) {
        const element = document.getElementById(translationId);
        if (element) {
            element.remove();
        }
    }

    // Event listener per il pulsante aggiungi traduzione
    if (addTranslationBtn) {
        addTranslationBtn.addEventListener('click', addTranslationForm);
    }

    // Aggiungi una traduzione di default se ci sono errori di validazione
    @if(old('translations'))
        @foreach(old('translations') as $index => $translation)
            addTranslationForm();
            document.querySelector(`#translation_${translationCount}_language`).value = '{{ $translation["language"] ?? "" }}';
            document.querySelector(`#translation_${translationCount}_title`).value = '{{ $translation["title"] ?? "" }}';
            document.querySelector(`#translation_${translationCount}_content`).value = '{{ $translation["content"] ?? "" }}';
            document.querySelector(`#translation_${translationCount}_description`).value = '{{ $translation["description"] ?? "" }}';
            document.querySelector(`#translation_${translationCount}_notes`).value = '{{ $translation["notes"] ?? "" }}';
        @endforeach
    @endif
});
</script>
@endpush
@endsection
