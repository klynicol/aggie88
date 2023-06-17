<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Helpers\LinkTreeHelper;
use App\Models\LinkTreeDataEntity;

class LinkTreeController extends Controller
{
   public function __construct()
   {
      helper(['text', 'form']);
   }

   public function index()
   {
      $linkTree = new LinkTreeDataEntity();  

      $data = [
         'user' => session()->get(),
         'linktree' => $linkTree->data,
         'linktreeId' => $linkTree->linkTreeId,
         'socialKeys' => $linkTree->social,
      ];

      echo view('linktree/edit', $data);
   }

   public function store()
   {
      $linkTree = new LinkTreeDataEntity();
      $request = $this->request->getPost();
      // header('Content-Type: application/json');
      // echo json_encode($request);
      // die();
      $userId = session()->get('id');
      $linkTreeId = $request['linktree_id'];

      if (!LinkTreeHelper::isValidUserLinkTree($userId, $linkTreeId)) {
         session()->setFlashdata('msg', 'Invalid LinkTree ID');
         return redirect()->back();
      }

      // Save the avatar
      // This could be done at the user level if needed
      $avatar = $this->request->getFile('avatar_upload');
      if ($avatar->isValid()) {
         $randomName = random_string('nozero', 15);
         $path = ROOTPATH . 'public/images/user/' . $userId;
         $fileName = $randomName . '.' . $avatar->getExtension();
         $avatar->move($path, $fileName, true);
         $request['avatar'] = 'images/' . $userId . '/' . $fileName;
      } else if (!$avatar->isValid() && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
         session()->setFlashdata('msg', $avatar->getErrorString());
         return redirect()->back();
      }

      $function = isset($request['preview']) ? 'preview' : 'save';
      unset($request['preview']);
      unset($request['save']);

      $linkTree->updateData($request);
      $linkTree->save();

      if ($function === 'preview') {
         return redirect()->to('/linktree/' . $linkTreeId);
      } else {
         session()->setFlashdata('msg', 'LinkTree Saved');
         return redirect()->back();
      }
   }

   public function update()
   {
      // @todo
   }

   public function destroy()
   {
      // @todo
   }
}
