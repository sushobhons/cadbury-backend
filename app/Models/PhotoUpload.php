<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'theme_id',
        'image_path',
        'image_url',
    ];

    protected $appends = [
        'theme_name',
        'theme_name_bn',
        'theme_name_en',
    ];

    public function getThemeNameAttribute(): string
    {
        $meta = $this->getThemeMeta();

        return $meta['name_bn'].' ('.$meta['name_en'].')';
    }

    public function getThemeNameBnAttribute(): string
    {
        return $this->getThemeMeta()['name_bn'];
    }

    public function getThemeNameEnAttribute(): string
    {
        return $this->getThemeMeta()['name_en'];
    }

    private function getThemeMeta(): array
    {
        $themes = config('themes.map', []);
        $themeId = (int) $this->theme_id;

        return $themes[$themeId] ?? [
            'name_bn' => 'অজানা থিম',
            'name_en' => 'Theme '.$themeId,
            'overlay' => null,
        ];
    }
}

