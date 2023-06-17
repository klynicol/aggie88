<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ProfileController extends Controller
{
   public function index()
   {
      // @todo: view user profile page
   }

   public function logout()
   {
      session()->destroy();
      return redirect()->to('/signin');
   }

   public function verifyEmail()
   {
      $scramble = $this->request->getVar('hbt');
      //@todo: verify email
   }
}
