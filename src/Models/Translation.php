<?php

namespace Mihkullorg\Translatable\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model {

    protected $table = 'mt_translations';

    protected $fillable = [
        'translatable_type',
        'translatable_id',
        'field',
        'value',
        'language',
    ];

    protected $dates = ['updated_at'];

    public function scopeField($query, $field)
    {
        return $query->where('field', '=', $field);
    }

    public function scopeLanguage($query, $language)
    {
        return $query->where('language', '=', $language);
    }

    public function scopeFresherThan($query, $date)
    {
        $date = $date instanceof Carbon ? $date->toDateTimeString() : $date;
        return $query->where('updated_at', '>=', $date);
    }

    public function translatable()
    {
        return $this->morphTo();
    }

}