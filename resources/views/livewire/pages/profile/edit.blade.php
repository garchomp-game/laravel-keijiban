<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Volt\Component;

new class extends Component {
    use WithFileUploads;
    
    public $name;
    public $bio;
    public $profileImage;
    public $currentProfileImage;
    
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->bio = $user->bio;
        $this->currentProfileImage = $user->profile_image;
    }
    
    public function updateProfile(): void
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
    
    public function deleteProfileImage(): void
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
}; ?>

<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('プロフィール編集') }}</h2>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <form wire:submit="updateProfile" class="space-y-6">
                    <!-- プロフィール画像 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('プロフィール画像') }}
                        </label>
                        
                        <div class="flex items-center space-x-6">
                            <div class="relative flex h-24 w-24 shrink-0 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                @if ($profileImage)
                                    <img src="{{ $profileImage->temporaryUrl() }}" alt="{{ $name }}" class="h-full w-full object-cover">
                                @elseif ($currentProfileImage)
                                    <img src="{{ Storage::url($currentProfileImage) }}" alt="{{ $name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-3xl font-bold">
                                        {{ auth()->user()->initials() }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex flex-col space-y-2">
                                <input type="file" wire:model="profileImage" id="profileImage" class="hidden">
                                <label for="profileImage" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
                                    {{ __('画像をアップロード') }}
                                </label>
                                
                                @if ($profileImage || $currentProfileImage)
                                    <button type="button" wire:click="deleteProfileImage" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('画像を削除') }}
                                    </button>
                                @endif
                                
                                @error('profileImage')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- 名前 -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('ユーザー名') }}
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            wire:model="name" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                        >
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- 自己紹介 -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('自己紹介') }}
                        </label>
                        <textarea 
                            id="bio" 
                            wire:model="bio" 
                            rows="5" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                            placeholder="{{ __('自己紹介文を入力してください。') }}"
                        ></textarea>
                        @error('bio')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-colors duration-200"
                        >
                            {{ __('更新する') }}
                        </button>
                    </div>
                    
                    <x-action-message class="mr-3" on="profile-updated">
                        {{ __('保存しました。') }}
                    </x-action-message>
                    
                    <x-action-message class="mr-3" on="profile-image-deleted">
                        {{ __('プロフィール画像を削除しました。') }}
                    </x-action-message>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
