<?php

namespace App\Livewire\Pages\Profile;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;
    
    public function mount($userId)
    {
        $this->user = User::findOrFail($userId);
    }
    
    public function render()
    {
        return view('livewire.pages.profile.show');
    }
}
