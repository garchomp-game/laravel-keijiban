<?php

namespace App\Livewire\Pages\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    
    public $name;
    public $bio;
    public $profileImage;
    public $currentProfileImage;
    
    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->bio = $user->bio;
        $this->currentProfileImage = $user->profile_image;
    }
    
    public function updateProfile()
    {
        $user = Auth::user();
        
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'profileImage' => ['nullable', 'image', 'max:1024'], // 1MB max
        ]);
        
        if ($this->profileImage) {
            // 古いプロフィール画像を削除
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            // 新しい画像をアップロード
            $path = $this->profileImage->store('profile-images', 'public');
            $user->profile_image = $path;
        }
        
        $user->name = $this->name;
        $user->bio = $this->bio;
        $user->save();
        
        $this->dispatch('profile-updated');
    }
    
    public function deleteProfileImage()
    {
        $user = Auth::user();
        
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->profile_image = null;
            $user->save();
            $this->currentProfileImage = null;
            
            $this->dispatch('profile-image-deleted');
        }
    }
    
    public function render()
    {
        return view('livewire.pages.profile.edit');
    }
}
