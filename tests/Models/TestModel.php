<?php

namespace Mihkullorg\Tests\Translatable\Models;

use Illuminate\Database\Eloquent\Model;
use Mihkullorg\Translatable\Traits\Translatable;

class TestModel extends Model
{
    use Translatable;

    protected $dates = ['updated_at'];

    protected $table = 'test_models';

    protected $fillable = [
        'body',
    ];
}
