<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ProfileController extends Controller
{
   public function index()
   {
      $session = session();
      echo "Hello : " . $session->get('name');
   }

   public function logout()
   {
      $session = session();
      $session->destroy();
      return redirect()->to('/signin');
   }

   public function verifyEmail()
   {
      $scramble = $this->request->getVar('hbt');
      //@todo: verify email
   }
}
