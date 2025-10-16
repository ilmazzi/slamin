
@use('App\Helpers\Chat\ChatHelper')

<header class="chat-list-header" dusk="header" style="background: var(--chat-bg-primary);">

    {{-- Title/name and Icon --}}
    <section class="d-flex justify-content-between align-items-center pb-2">

        @if (isset($title))
            <div class="d-flex align-items-center gap-2 chat-truncate" wire:ignore>
                <h3 class="fw-bold mb-0" style="color: var(--chat-text);" dusk="title">{{$title}}</h3> 
            </div>
        @endif

        <div class="d-flex gap-3 align-items-center">

            @if ($showNewChatModalButton)
            <x-chat::actions.new-chat widget="{{$this->isWidget()}}">
                <button id="open-new-chat-modal-button" class="chat-new-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12.875 5C9.225 5 7.4 5 6.242 6.103a4 4 0 0 0-.139.139C5 7.4 5 9.225 5 12.875V17c0 .943 0 1.414.293 1.707S6.057 19 7 19h4.125c3.65 0 5.475 0 6.633-1.103a4 4 0 0 0 .139-.139C19 16.6 19 14.775 19 11.125" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 10h6m-6 4h3m7-6V2m-3 3h6" />
                    </svg>
                </button>
            </x-chat::actions.new-chat>
            @endif

            @if ($showHomeRouteButton)
            <a id="redirect-button" href="{{ config('chat.home_route', '/') }}" class="d-flex align-items-center">
                <svg style="color: var(--chat-text-secondary); width: 2rem; height: 2rem;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M5 12.76c0-1.358 0-2.037.274-2.634c.275-.597.79-1.038 1.821-1.922l1-.857C9.96 5.75 10.89 4.95 12 4.95s2.041.799 3.905 2.396l1 .857c1.03.884 1.546 1.325 1.82 1.922c.275.597.275 1.276.275 2.634V17c0 1.886 0 2.828-.586 3.414S16.886 21 15 21H9c-1.886 0-2.828 0-3.414-.586S5 18.886 5 17z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.5 21v-5a1 1 0 0 0-1-1h-3a1 1 0 0 0-1 1v5" />
                </svg>
            </a>
            @endif

        </div>

    </section>

    {{-- Search input --}}
    @if ($allowChatsSearch)
        <section class="mt-3">
            <div class="input-group" style="background: var(--chat-bg-secondary); border-radius: 1.5rem; padding: 0.25rem 0.5rem;">
                <span class="input-group-text bg-transparent border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" style="width: 1.25rem; height: 1.25rem; color: var(--chat-text-secondary);">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </span>

                <input id="chats-search-field" name="chats_search" maxlength="100" type="search" wire:model.live.debounce='search'
                    placeholder="{{ __('chat::chats.inputs.search.placeholder')  }}" autocomplete="off"
                    class="form-control border-0 bg-transparent" style="color: var(--chat-text); box-shadow: none;">
            </div>
        </section>
    @endif

</header>
