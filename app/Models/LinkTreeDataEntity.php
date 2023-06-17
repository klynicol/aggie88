<?php

namespace App\Models;

use CodeIgniter\Files\File;
use Config\Services;
use App\Helpers\CommonHelper;
use App\Helpers\LinkTreeHelper;

class LinkTreeDataEntity
{
   private $dataDir = WRITEPATH . 'linktreedata/';
   private $imageDir = ROOTPATH . 'public/images/lt/';
   private $userId;
   public $linkTreeId;
   public $data;

   public function __construct()
   {
      helper(['filesystem']);
      $this->userId = session()->get('id');
      // Get the user's linktree ID from the database
      // Currently there's only one per user, if that changes we'll need to pass
      // the linktree ID in the URL
      $this->linkTreeId = (new LinkTreeModel())->where('user_id', $this->userId)->first()['id'];
      $this->data = $this->getData($this->linkTreeId);
   }

   /**
    * Get the linktree data from the file system
    * @return array
    */
   public function getData()
   {
      $file = new File($this->dataDir . $this->linkTreeId . '.json');

      if (!$file->isFile()) {
         $this->initData();
         $this->save();
      } else {
         $rawData = $file->openFile();
         $this->data = json_decode($rawData->fread($file->getSize()), true);
      }

      return $this->data;
   }

   /**
    * Initialize the linktree data
    * @return void
    */
   private function initData()
   {
      $data = [
         'avatar' => '',
         'tagline' => '',
         'social' => null,
         'links' => [],
      ];

      foreach (LinkTreeHelper::$social as $social) {
         $data['social'][$social] = '';
      }

      $this->data = $data;
   }

   /**
    * Initialize a new link
    * @param string $type
    * @return array
    */
   public function initLink($type)
   {
      $data = [
         'id' => CommonHelper::guidv4(),
         'type' => $type,
         'icon' => '',
         'url' => '',
         'text' => '',
         'clicks' => 0,
      ];
      $this->data['links'][$data['id']] = $data;
      $this->save();
      return $data;
   }

   /**
    * Update the linktree data
    * @param array $data
    * @return void
    */
   public function updateData($data)
   {
      if (isset($data['links']) && is_array($data['links'])) {
         foreach ($data['links'] as $linkId => &$link) {
            $this->updateLink($linkId, $link);
         }
      }

      unset($data['links']);
      $this->data = array_merge($this->data, $data);
   }

   /**
    * Update a link
    * @param string $linkId
    * @param array $linkData
    * @return void
    */
   public function updateLink($linkId, $linkData)
   {
      log_message('debug', json_encode($linkData));

      $newIcon = $this->saveLinkImage($linkId);
      if($newIcon !== null) {
         $this->deleteLinkImage($linkId);
         $linkData['icon'] = $newIcon;
      }

      if (isset($this->data['links'][$linkId])) {
         $this->data['links'][$linkId] = array_merge($this->data['links'][$linkId], $linkData);
      } else {
         $this->data['links'][$linkId] = $linkData;
      }
   }

   /**
    * Delete a link
    * @param string $linkId
    * @return void
    */
   public function deleteLink($linkId)
   {
      if (isset($this->data['links'][$linkId])) {
         $this->deleteLinkImage($linkId);
         unset($this->data['links'][$linkId]);
      }
   }

   /**
    * Save the link image
    * @param string $linkId
    * @return string|null
    */
   private function saveLinkImage($linkId)
   {
      $path = $this->imageDir . $this->linkTreeId;

      $request = Services::request();
      $files = $request->getFiles();
      if(isset($files['links'][$linkId]['icon'])){
         $file = $files['links'][$linkId]['icon'];
      } else {
         return null;
      }

      if (!$file->isValid()
         || (!$file->isValid() && $file->getError() !== UPLOAD_ERR_NO_FILE)) {
         log_message('error', $file->getErrorString());
         return null;
      }

      // Create the image directory if it doesn't exist
      if (!is_dir($path)) {
         mkdir($path);
      }

      $randomName = random_string('nozero', 15);
      $fileName = $randomName . '.' . $file->getExtension();
      $file->move($path, $fileName, true);

      return 'images/lt/' . $this->linkTreeId . '/' . $fileName;
   }

   /**
    * Delete the link image
    * @param string $linkId
    * @return void
    */
   private function deleteLinkImage($linkId)
   {
      if(!empty($this->data['links'][$linkId]['icon'])) {
         @unlink(ROOTPATH . 'public/' . $this->data['links'][$linkId]['icon']);
      }
   }

   /**
    * Save the linktree data to the file system
    * @return void
    */
   public function save()
   {
      $data = json_encode($this->data, JSON_PRETTY_PRINT);
      write_file($this->dataDir . $this->linkTreeId . '.json', $data);
   }
}
