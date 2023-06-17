<?= view('header', ['title' => 'Edit My LinkTree']); ?>

<nav class="navbar navbar-light bg-light">
   <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Welcome <?= $user['name']; ?></span>
      <a href="/logout" class="btn btn-outline-danger">Logout</a>
   </div>
</nav>

<div style="max-width: 900px;" class="mx-auto">
   <h2 class="my-3 text-center">Edit Your Link Tree</h2>

   <?php if (session()->getFlashdata('msg')) : ?>
      <div class="alert alert-warning">
         <?= session()->getFlashdata('msg') ?>
      </div>
   <?php endif; ?>

   <form action="/linktree/save" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="linktree_id" value="<?= $linktreeId; ?>">
      <input type="hidden" name="avatar" value="<?= $linktree['avatar']; ?>">

      <div class="text-center">
         <div style="width: 350px;" class="btn-group" role="group" aria-label="Basic mixed styles example">
            <button type="submit" name="save" class="btn btn-success">Save</button>
            <button type="submit" name="preview" class="btn btn-warning">Preview</button>
         </div>
      </div>

      <!-- Avatar Upload -->
      <div class="mb-3">
         <label for="avatar" class="form-label font-b">Avatar</label>
         <input class="form-control form-control-sm" name="avatar_upload" id="avatar" type="file">
      </div>

      <!-- Tagline -->
      <div class="mb-3">
         <label for="tagline" class="form-label font-b">Tagline</label>
         <input type="text" class="form-control" id="tagline" name="tagline" value="<?= $linktree['tagline']; ?>">
      </div>

      <!-- Social Links -->
      <div class="mb-3 row">
         <label class="form-label">Social Links</label>
         <?php foreach ($socialKeys as $socialKey) : ?>
            <label for="<?= $socialKey; ?>" class="col-sm-2 col-form-label"><?= ucfirst($socialKey); ?></label>
            <div class="col-sm-10">
               <?php $value = $linktree['social'][$socialKey] ?? null; ?>
               <input type="text" class="form-control" name="social[<?= $socialKey; ?>]" id="<?= $socialKey; ?>" value="<?= $value; ?>">
            </div>
         <?php endforeach; ?>
      </div>

      <!-- Links -->
      <div class="mb-3 link_wrapper">
         <?php if (count($linktree['links']) > 0) : ?>
            <label class="form-label">Links</label>
         <?php endif; ?>
      </div>

   </form>

   <button id="show_add_link_modal" class="btn btn-outline-success">Add New Link</button>

</div>

<!-- Modal -->
<div class="modal" tabindex="-1">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Link Type Select</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="p-4">
            <select class="form-select link_option_select">
               <option value="standard">Standard</option>
               <option value="video">Video</option>
            </select>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="add_link_button">Add</button>
         </div>
      </div>
   </div>
</div>

<script>
   let links = <?= json_encode($linktree['links']); ?>;
   // Convert object to array
   links = Object.keys(links).map((key) => {
      const link = links[key];
      return link;
   });
   
   function findLinkIndex(linkId) {
      return links.findIndex((link) => {
         return link.id === linkId;
      });
   }

   $(document).ready(() => {
      links.forEach((link, index) => {
         $('.link_wrapper').append(renderLink(link, index));
      });
   });

   $('#show_add_link_modal').on('click', (e) => {
      e.preventDefault();
      // Default to standard link
      $('.link_option_select').val('standard');
      // Open the modal and let user select type
      $('.modal').modal('show');
   });

   $('#add_link_button').on('click', (e) => {
      e.preventDefault();
      const linkType = $('.link_option_select').val();
      $.get(`/linktree/link/new?type=${linkType}`, (response) => {
         console.log(response);
         links.push(response.data);
         $('.link_wrapper').append(renderLink(response.data, links.length - 1));
         addListeners();
         $('.modal').modal('hide');
      });
   });


   function addListeners() {
      $('.delete').on('click', (e) => {
         e.preventDefault();
         const linkId = $(e.target).closest('.link_container').data('link-id');
         const linkIndex = findLinkIndex(linkId);
         $.delete(`/linktree/link/${linkId}`, (response) => {
            console.log(response);
            $(e.target).closest('.link_container').remove();
            links.splice(linkIndex, 1);
         });

      });
   }

   function renderLink(link, index) {
      if (link.type === 'video') {
         return renderVideoLink(link, index);
      }
      return renderStandardLink(link, index);
   }


   function renderStandardLink(link, index) {
      return `
         <div class="mb-3 p-4 border border-1 link_container" data-link-id="${link.id}">
            <input type="hidden" name="links[${link.id}][id]" value="${link.id}">
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label">Standard Link URL</label>
               <div class="col-sm-9">
                  <input type="text" class="form-control" name="links[${link.id}][url]" value="${link.url}">
               </div>
            </div>
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label">Icon</label>
               <div class="col-sm-9">
                  <input class="form-control form-control-sm" name="links[${link.id}][icon]" type="file">
               </div>
            </div>
            ${renderLinkButtons()}
         </div>
      `;
   }

   function renderVideoLink(link, index) {
      return `
         <div class="mb-3 p-4 border border-1 link_container" data-link-id="${link.id}">
            <input type="hidden" name="links[${link.id}][id]" value="${link.id}">
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label">Video Link URL</label>
               <div class="col">
                  <input type="text" class="form-control" name="links[${link.id}][url]" value="${link.url}">
               </div>
            </div>
            ${renderLinkButtons()}
         </div>
      `;
   }

   function renderLinkButtons() {
      return `
      <div class="">
         <button class="btn btn-outline-danger delete">Delete</button>
      </div>
      `;
   }
</script>