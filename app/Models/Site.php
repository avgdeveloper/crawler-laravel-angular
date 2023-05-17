<?php


namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Site extends Model
{
    public $connection = 'mongodb';
    public $collection = 'sites';
    public $fillable = ['url', 'content', 'inner_sites','inner_urls'];

    public $casts = [
        'inner_sites' => 'array',
        'inner_urls' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->attributes['inner_sites'] = [];
        $this->attributes['inner_urls'] = [];
    }
}