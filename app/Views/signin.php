<?= view('header', ['title' => 'Signin']); ?>
<body>
   <div class="container mt-5">
      <div class="row justify-content-md-center">
         <div class="col-5">

            <h2>Login</h2>

            <?php if (session()->getFlashdata('msg')) : ?>
               <div class="alert alert-warning">
                  <?= session()->getFlashdata('msg') ?>
               </div>
            <?php endif; ?>
            <form action="<?php echo base_url(); ?>/SigninController/loginAuth" method="post">
               <div class="form-group mb-3">
                  <input type="email" name="email" placeholder="Email" value="<?= set_value('email') ?>" class="form-control">
               </div>
               <div class="form-group mb-3">
                  <input type="password" name="password" placeholder="Password" class="form-control">
               </div>

               <div class="d-grid">
                  <button type="submit" class="btn btn-success">Signin</button>
               </div>
            </form>
         </div>

      </div>
   </div>
</body>

</html>