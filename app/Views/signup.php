<?= view('header', ['title' => 'Signup']); ?>

<body>
   <div class="container mt-5">
      <div class="row justify-content-md-center">
         <div class="col-md-6">
            <h2 class="text-center mb-4">Register User</h2>
            <?php if (isset($validation)) : ?>
               <div class="alert alert-warning">
                  <?= $validation->listErrors() ?>
               </div>
            <?php endif; ?>
            <form action="<?php echo base_url(); ?>/SignupController/store" method="post">
               <div class="mb-3">
                  <input type="text" name="name" placeholder="Name" value="<?= set_value('name') ?>" class="form-control">
               </div>
               <div class="mb-3">
                  <input type="email" name="email" placeholder="Email" value="<?= set_value('email') ?>" class="form-control">
               </div>
               <div class="mb-3">
                  <input type="password" name="password" placeholder="Password" class="form-control">
               </div>
               <div class="mb-3">
                  <input type="password" name="confirmpassword" placeholder="Confirm Password" class="form-control">
               </div>
               <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-success">Signup</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</body>

</html>