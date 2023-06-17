<?php

namespace App\Controllers;

use App\Helpers\CommonHelper;
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Helpers\LinkTreeHelper;
use App\Models\LinkTreeDataEntity;
use App\Models\LinkTreeModel;

class LinkTreeLinkController extends Controller
{
   public function __construct()
   {
      helper(['text', 'form']);
   }

   // get
   public function new()
   {
      $type = $this->request->getVar('type');
      $linkTree = new LinkTreeDataEntity();
      return $this->response->setJSON([
         'status' => 'success',
         'data' => $linkTree->initLink($type),
      ]);
   }

   // get
   public function index()
   {
      
   }

   // post
   public function create()
   {

   }

   // get
   public function show($id)
   {

   }

   // put, patch
   public function update($id)
   {
      // @todo
   }

   // delete
   public function delete($id)
   {
      $linkTree = new LinkTreeDataEntity();
      $linkTree->deleteLink($id);
      $linkTree->save();
      return $this->response->setJSON([
         'status' => 'success',
         'message' => 'Link deleted',
      ]);
   }
}
