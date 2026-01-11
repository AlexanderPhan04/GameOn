<?php

if (! function_exists('get_avatar_url')) {
    /**
     * Get avatar URL - handles both local paths and external URLs
     *
     * @param  string|null  $avatar
     * @param  string|null  $default
     * @return string
     */
    function get_avatar_url($avatar = null, $default = null)
    {
        if (! $avatar) {
            return $default ?? asset('images/default-avatar.png');
        }

        // Check if it's already a full URL (http:// or https://)
        if (filter_var($avatar, FILTER_VALIDATE_URL)) {
            return $avatar;
        }

        // Check if it starts with http or https (for edge cases)
        if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
            return $avatar;
        }

        // System avatar path (e.g., "system/avatar_1.png")
        if (str_starts_with($avatar, 'system/')) {
            return asset('images/system-avatars/' . str_replace('system/', '', $avatar));
        }

        // Otherwise, it's a local path - use storage
        return asset('storage/' . $avatar);
    }
}
