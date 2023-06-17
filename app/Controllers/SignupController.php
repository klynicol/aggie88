<?php

namespace App\Controllers;

use App\Models\LinkTreeModel;
use CodeIgniter\Controller;
use App\Models\UserModel;

class SignupController extends Controller
{
   public function index()
   {
      helper(['form']);
      $data = [];
      echo view('signup', $data);
   }

   public function store()
   {
      helper(['form']);
      $rules = [
         'name'          => 'required|min_length[2]|max_length[50]',
         'email'         => 'required|min_length[4]|max_length[100]|valid_email|is_unique[users.email]',
         'password'      => 'required|min_length[4]|max_length[50]',
         'confirmpassword'  => 'matches[password]'
      ];

      if ($this->validate($rules)) {
         $userModel = new UserModel();
         $data = [
            'name'     => $this->request->getVar('name'),
            'email'    => $this->request->getVar('email'),
            'email_hash' => md5($this->request->getVar('email')),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
         ];
         $userModel->save($data);

         // Create the first link tree for this user.
         // There are no other ways linktrees are created so each user will only have one for now
         (new LinkTreeModel())->save([
            'user_id' => $userModel->getInsertID(),
            'title' => 'My Linktree',
            // added ability to make linktree url's unique later if we want
            'url' => $userModel->getInsertID(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
         ]);

         // @todo: send email verification
         
         return redirect()->to('/signin');
      } else {
         $data['validation'] = $this->validator;
         echo view('signup', $data);
      }
   }
}
