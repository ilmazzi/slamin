<?php

namespace App\Livewire\Meeting;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MeetingCall extends Component
{
    public $meeting;
    public $isJoined = false;
    public $participants = [];
    public $meetingStatus = 'active'; // active, ended, scheduled

    public function mount($meeting = null)
    {
        if ($meeting) {
            $this->meeting = $meeting;
        } else {
            // Meeting di esempio per demo
            $this->meeting = (object) [
                'id' => 1,
                'title' => 'Team Meeting',
                'description' => 'Weekly team sync',
                'start_time' => now(),
                'end_time' => now()->addHour(),
                'host' => (object) [
                    'name' => 'Bette Hagenes',
                    'role' => 'Web Developer',
                    'avatar' => asset('assets/images/avatar/2.png'),
                ],
                'participants_count' => 5,
                'meeting_url' => '#',
            ];
        }
    }

    public function joinMeeting()
    {
        if ($this->meetingStatus === 'active') {
            $this->isJoined = true;
            
            // Qui potresti integrare con servizi come:
            // - Zoom API
            // - Google Meet API
            // - Microsoft Teams API
            // - Jitsi Meet
            
            session()->flash('success', 'Joining meeting...');
            
            // Redirect to meeting URL or open in new tab
            return redirect($this->meeting->meeting_url);
        }
        
        session()->flash('error', 'Meeting is not active');
    }

    public function leaveMeeting()
    {
        $this->isJoined = false;
        session()->flash('success', 'Left the meeting');
    }

    public function getMeetingStatusProperty()
    {
        if (!$this->meeting) return 'ended';
        
        $now = now();
        $start = $this->meeting->start_time;
        $end = $this->meeting->end_time;
        
        if ($now < $start) {
            return 'scheduled';
        } elseif ($now >= $start && $now <= $end) {
            return 'active';
        } else {
            return 'ended';
        }
    }

    public function getStatusColorProperty()
    {
        return match($this->meetingStatus) {
            'active' => 'success',
            'scheduled' => 'warning',
            'ended' => 'secondary',
            default => 'secondary'
        };
    }

    public function getStatusTextProperty()
    {
        return match($this->meetingStatus) {
            'active' => 'Live Now',
            'scheduled' => 'Scheduled',
            'ended' => 'Ended',
            default => 'Unknown'
        };
    }

    public function render()
    {
        return view('livewire.meeting.meeting-call');
    }
}


