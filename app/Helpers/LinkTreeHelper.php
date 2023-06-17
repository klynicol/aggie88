<?php

namespace App\Helpers;

use App\Models\LinkTreeModel;
use App\Models\UserModel;

class LinkTreeHelper
{

   public static $social = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'pinterest'];
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

   public static function getUserFromLinkTreeId($linkTreeId)
   {
      $linkTreeRecord = (new LinkTreeModel())->where('id', $linkTreeId)->first();
      if($linkTreeRecord === null) {
         return null;
      }
      return (new UserModel())->where('id', $linkTreeRecord['user_id'])->first();
   }
}
