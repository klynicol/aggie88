<?php 
namespace App\Models;
  
class UserModel extends BaseModel{
    protected $table = 'users';
    
    protected $allowedFields = [
        'name',
        'email',
        'email_hash',
        'password',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}