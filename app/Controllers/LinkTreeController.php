<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Helpers\LinkTreeHelper;
use App\Models\LinkTreeDataEntity;
use CodeIgniter\Files\File;

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
         'socialKeys' => LinkTreeHelper::$social,
      ];

      echo view('linktree/edit', $data);
   }

   public function show($id)
   {  
      $file = new File(WRITEPATH . 'linktreedata/' . $id . '.json');
      if(!$file->isFile()) {
         // respond with 404
         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
            'LinkTree Not Found'
         );
      }

      $data = [
         'user' => LinkTreeHelper::getUserFromLinkTreeId($id),
         'linktree' => json_decode($file->openFile()->fread($file->getSize()), true),
         'socialKeys' => LinkTreeHelper::$social,
      ];

      // return $this->response->setJSON($data);

      echo view('linktree/show', $data); 
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
         $request['avatar'] = 'images/user/' . $userId . '/' . $fileName;
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
