<?php

namespace App\Helpers;

use App\Models\LinkTreeModel;
use CodeIgniter\Files\File;
use Config\Services;

class LinkTreeHelper
{
   /**
    * Check if the linktree ID belongs to the user
    * @param int $userId
    * @param int $linkTreeId
    * @return bool
    */
   public static function isValidUserLinkTree($userId, $linkTreeId)
   {
      $linkTreeRecord = (new LinkTreeModel())->where('user_id', $userId)->where('id', $linkTreeId)->first();
      return $linkTreeRecord !== null;
   }
}
