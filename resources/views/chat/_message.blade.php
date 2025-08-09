@if($message['is_own'])
<div class="position-relative">
    <div class="chat-box-right">
        <div>
            <p class="chat-text">{{ $message['content'] }}</p>
            <p class="text-muted"><i class="ti ti-checks text-primary"></i> {{ $message['time'] }}</p>
        </div>
    </div>
    <div class="chatdp h-45 w-45 b-r-50 position-absolute end-0 top-0 bg-danger">
        @php $user = \App\Models\User::find($message['sender_id']); @endphp
        <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
              data-user-id="{{ $user->id }}">
            <img alt="avatar" class="img-fluid b-r-10"
                 src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
            <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                  data-presence-dot></span>
        </span>
    </div>
</div>
    @else
<div class="position-relative">
    <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
        @php $user = \App\Models\User::find($message['sender_id']); @endphp
        <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
              data-user-id="{{ $user->id }}">
            <img alt="avatar" class="img-fluid b-r-10"
                 src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
            <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                  data-presence-dot></span>
</span>
        </div>
    <div class="chat-box">
        <div>
            <p class="chat-text">{{ $message['content'] }}</p>
            <p class="text-muted"><i class="ti ti-checks text-primary"></i> {{ $message['time'] }}</p>
        </div>
    </div>
</div>
    @endif
