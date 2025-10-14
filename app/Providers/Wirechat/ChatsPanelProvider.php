<?php

namespace App\Providers\Wirechat;

use Wirechat\Wirechat\Panel;
use Wirechat\Wirechat\PanelProvider;
use Wirechat\Wirechat\Http\Resources\WireChatUserResource;
use Wirechat\Wirechat\Support\Color;
use App\Models\User;

class ChatsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
             ->id('chats')
             ->path('chats')
             ->middleware(['web','auth'])
             ->default()
             // Specifica il modello User PRIMA di tutto il resto
             ->authProvider(User::class)
             // Enable chat actions
             ->createChatAction()
             ->createGroupAction()
             ->deleteChatAction()
             ->clearChatAction()
             ->webPushNotifications()
             ->attachments()
             ->maxUploads(5)
             ->mediaMaxUploadSize(10240) // 10 MB
             ->mediaMimes(['png', 'jpg', 'jpeg', 'gif', 'mov', 'mp4', 'mp3', 'webp'])
             ->fileMimes(['zip', 'rar', 'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])
             ->fileMaxUploadSize(5000) // 5 MB
             ->emojiPicker()
             ->colors([
                 'primary' => Color::Cyan,
                 'danger' => Color::Rose,
                 'gray' => Color::Zinc,
                 'info' => Color::Blue,
                 'success' => Color::Emerald,
                 'warning' => Color::Orange,
             ]);
      }
}
