<?php

namespace LaraMod\Admin\Pages\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraMod\Admin\Core\Traits\HelpersTrait;

class Pages extends Model
{
    use HelpersTrait;

    public $timestamps = true;
    protected $table = 'pages';

    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [

    ];

    protected $dates = ['deleted_at'];

    protected $appends = [];


    protected $fillable = [
        'viewable',
        'slug'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
            $this->fillable = array_merge($this->fillable, [
                'title_'.$locale,
                'content_'.$locale,
                'meta_title_'.$locale,
                'meta_description_'.$locale,
                'meta_keywords_'.$locale
            ]);
        }
    }

    public function scopeVisible($q)
    {
        return $q->whereViewable(true);
    }

    public function getTitleAttribute()
    {
        return $this->{'title_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaTitleAttribute()
    {
        return $this->{'meta_title_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->{'meta_description_' . config('app.fallback_locale', 'en')};
    }

    public function getMetaKeywordsAttribute()
    {
        return $this->{'meta_keywords_' . config('app.fallback_locale', 'en')};
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }


}