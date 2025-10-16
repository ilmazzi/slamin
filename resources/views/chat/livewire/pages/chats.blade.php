<div class="chat-container">
    <div class="chat-sidebar">
      <livewire:chat.chats/> 
    </div>
    <main class="chat-main">
        <div class="chat-welcome">
             <h4>@lang('chat::pages.chat.messages.welcome')</h4>
        </div>
    </main>
    
    {{-- Modal component for new chat/group --}}
    <livewire:chat.modal />
</div>