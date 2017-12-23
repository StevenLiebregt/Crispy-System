<?php

namespace App\Models;

use StevenLiebregt\CrispySystem\Database\Model;

class ProductsModel extends Model
{
    protected $table = 'products';
    protected $fields = [
        'id',
        'active',
        'number',
        'name',
        'price',
        'stock',
    ];
}