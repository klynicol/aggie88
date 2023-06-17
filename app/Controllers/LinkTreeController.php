<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Helpers\LinkTreeHelper;
use App\Models\LinkTreeModel;

class LinkTreeController extends Controller
{
   public function index()
   {
      log_message('error', 'getLinkTreeData');
      helper(['form']);
      // Get the user's linktree ID from the database
      // Currently there's only one per user, if that changes we'll need to pass
      // the linktree ID in the URL
      $userLinkTreeId = (new LinkTreeModel())->where('user_id', session()->get('id'))->first()['id'];

      log_message('error', 'getLinkTreeData');
      $data = [
         'user' => session()->get(),
         'linktree' => LinkTreeHelper::getLinkTreeData($userLinkTreeId),
         'linktreeId' => $userLinkTreeId,
         'socialKeys' => LinkTreeHelper::$social,
      ];
      log_message('error', 'getLinkTreeData');
      echo view('linktree/edit', $data);
   }

   public function save()
   {
      $request = $this->request->getPost();

      foreach($this->request->getFiles() as $file){
         if($file->isValid()){
            log_message('error', $file->getName() . ' is valid');
         }
      }
      if(isset($request['save'])){
         $linkTreeId = $request['linktree_id'];
         unset($request['save']);
         LinkTreeHelper::saveLinkTreeData($linkTreeId, $request);
         // return redirect()->to('/profile');
      }

      header('Content-Type: application/json');
      echo json_encode($request);
   }
}