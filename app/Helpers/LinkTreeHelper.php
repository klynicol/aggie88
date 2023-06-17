<?php

namespace App\Helpers;

use App\Models\LinkTreeModel;
use CodeIgniter\Files\File;
use Config\Services;

class LinkTreeHelper
{
   private static $dataDir = WRITEPATH . 'linktreedata/';
   private static $imageDir = ROOTPATH . 'public/linktreeimages/';
   public static $social = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube'];

   /**
    * Get an array of all linktree data for a single user
    * @param int $userId
    * @return array
    */
   public static function getAllUserLinkTreeData($userId)
   {
      $linkTreeData = [];
      $linkTreeRecords = (new LinkTreeModel())->where('user_id', $userId)->findAll();
      foreach ($linkTreeRecords as $linkTreeRecord) {
         $linkTreeData[$linkTreeRecord['id']] = self::getLinkTreeData($linkTreeRecord['id']);
      }

      return $linkTreeData;
   }

   public static function saveLinkTreeImage($linkTreeId, $requestImageName)
   {
      helper(['filesystem']);
      // Create the image directory if it doesn't exist
      if (!is_dir(self::$imageDir . $linkTreeId)) {
         mkdir(self::$imageDir . $linkTreeId);
      }

      log_message('error', 'requestImageName: ' . $requestImageName);
      
      $request = Services::request();
      foreach($request->getFiles() as $file){
         if($file->isValid()){
            log_message('error', $file->getName() . ' is valid');
         }
      }

      // $request->getFile($requestImageName)
      //    ->move(self::$imageDir . $linkTreeId, null, true);
   }

   /**
    * Save linktree data to a json file
    * @param int $linkTreeId
    * @param array $linkTreeData
    * @return static
    */
   public static function saveLinkTreeData($linkTreeId, $linkTreeData)
   {
      helper(['filesystem']);
      
      $index = 0;
      foreach($linkTreeData['links'] as &$link){
         $link['clicks'] = (int)$link['clicks'];
         if($link['type'] == 'video'){
            self::saveLinkTreeImage($linkTreeId, "links.{$index}.{$link['icon']}");
            $link['icon'] = "nothing.jpg";
         }
      }

      $data = json_encode($linkTreeData);
      write_file(self::$dataDir . $linkTreeId . '.json', $data);

      return new static();
   }

   /**
    * Delete a linktree and all associated data
    * @param int $linkTreeId
    * @return static
    */
   public static function deleteLinkTree($linkTreeId)
   {
      helper(['filesystem']);
      // Delete the data file
      unlink(self::$dataDir . $linkTreeId . '.json');
      // Delete the image directory and all files in it
      delete_files(self::$imageDir . $linkTreeId, true);
      // Delete the linktree record from the database
      (new LinkTreeModel())->delete($linkTreeId);

      return new static();
   }

   /**
    * Get linktree data from a json file
    * @param int $linkTreeId
    * @return array
    */
   public static function getLinkTreeData($linkTreeId)
   {
      log_message('error', 'getLinkTreeData');
      log_message('error', 'linkTreeId: ' . $linkTreeId);
      $file = new File(self::$dataDir . $linkTreeId . '.json');
      if (!$file->isFile()) {
         return self::initLinkTreeData();
      }
      $data = $file->openFile();
      $data = $data->fread($file->getSize());
      log_message('error', 'data: ' . $data);
      return json_decode($data, true);
   }

   private static function initLinkTreeData()
   {
      $data = [
         'social' => null,
         'links' => [],
         'tagline' => null,
      ];

      foreach(self::$social as $social) {
         $data[$social] = null;
      }

      return $data;
   }
}