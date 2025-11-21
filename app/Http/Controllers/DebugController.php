<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DebugController extends Controller
{
    public function checkStorage()
    {
        $storagePath = storage_path('app/public');
        $publicPath = public_path('storage');
        $defaultAvatar = public_path('images/default-avatar.png');
        
        $storageExists = is_dir($storagePath);
        $publicLinkExists = is_link($publicPath);
        $defaultAvatarExists = file_exists($defaultAvatar);
        
        $storageFiles = [];
        if ($storageExists) {
            $storageFiles = array_slice(scandir($storagePath), 2); // Remove . and ..
        }
        
        return [
            'storage_path' => $storagePath,
            'public_storage_link' => $publicPath,
            'storage_exists' => $storageExists,
            'public_link_exists' => $publicLinkExists,
            'default_avatar_path' => $defaultAvatar,
            'default_avatar_exists' => $defaultAvatarExists,
            'storage_files' => $storageFiles,
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
        ];
    }
}
