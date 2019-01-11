<?php

namespace Mihkullorg\Translatable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mihkullorg\Translatable\Translatable;

class TestModel extends Model {

    use Translatable;

    protected $dates = ['updated_at'];

    protected $table = 'test_models';

    protected $fillable = [
        'body',
    ];
}