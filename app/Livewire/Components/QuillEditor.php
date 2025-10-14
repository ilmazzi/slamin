<?php

namespace App\Livewire\Components;

use Livewire\Component;

class QuillEditor extends Component
{
    public $content = '';
    public $placeholder = 'Scrivi qui...';
    public $height = '200px';
    public $toolbar = 'basic';
    public $wireModel = '';
    public $editorId;

    protected $listeners = [
        'setContent' => 'setContent',
        'getContent' => 'getContent',
        'clearContent' => 'clearContent'
    ];

    public function mount($wireModel = '', $content = '', $placeholder = 'Scrivi qui...', $height = '200px', $toolbar = 'basic')
    {
        $this->wireModel = $wireModel;
        $this->content = $content;
        $this->placeholder = $placeholder;
        $this->height = $height;
        $this->toolbar = $toolbar;
        $this->editorId = 'quill-editor-' . uniqid();
    }

    public function updatedContent($value)
    {
        if ($this->wireModel) {
            $this->dispatch('quill-content-updated', $this->wireModel, $value);
        }
    }

    public function setContent($content)
    {
        $this->content = $content;
        $this->dispatch('quill-set-content', $this->editorId, $content);
    }

    public function getContent()
    {
        $this->dispatch('quill-get-content', $this->editorId);
    }

    public function clearContent()
    {
        $this->content = '';
        $this->dispatch('quill-clear-content', $this->editorId);
    }

    public function getToolbarConfig()
    {
        $toolbars = [
            'basic' => [
                ['bold', 'italic', 'underline'],
                ['link'],
                ['clean']
            ],
            'full' => [
                [['header' => [1, 2, 3, false]]],
                ['bold', 'italic', 'underline', 'strike'],
                [['list' => 'ordered'], ['list' => 'bullet']],
                [['align' => ['', 'center', 'right']]],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                [['indent' => '-1'], ['indent' => '+1']],
                ['clean']
            ],
            'minimal' => [
                ['bold', 'italic'],
                ['clean']
            ],
            'poetry' => [
                ['bold', 'italic'],
                [['align' => ['', 'center', 'right']]],
                ['blockquote'],
                ['clean']
            ]
        ];

        return $toolbars[$this->toolbar] ?? $toolbars['basic'];
    }

    public function render()
    {
        return view('livewire.components.quill-editor');
    }
}
