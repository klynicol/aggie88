<?php

namespace App\Models;

class LinkTreeModel extends BaseModel
{
    protected $table = 'linktrees';

    protected $allowedFields = [
        'user_id',
        'title',
        'url',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}