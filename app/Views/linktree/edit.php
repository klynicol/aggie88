<?= view('header', ['title' => 'Edit My LinkTree']); ?>

<nav class="navbar navbar-light bg-light">
   <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Welcome <?= $user['name']; ?></span>
      <a href="/logout" class="btn btn-outline-danger">Logout</a>
   </div>
</nav>

<div style="max-width: 900px;" class="mx-auto">
   <h2 class="my-3 text-center">Edit Your Link Tree</h2>

   <form action="/linktree/save" method="POST">
      <input type="hidden" name="linktree_id" value="<?= $linktreeId; ?>">

      <div class="text-center">
         <div style="width: 350px;" class="btn-group" role="group" aria-label="Basic mixed styles example">
            <button type="submit" name="save" class="btn btn-success">Save</button>
            <button type="submit" name="preview" class="btn btn-warning">Preview</button>
         </div>
      </div>

      <!-- Avatar Upload -->
      <div class="mb-3">
         <label for="avatar" class="form-label font-b">Avatar</label>
         <input class="form-control form-control-sm" name="avatar" id="avatar" type="file">
      </div>

      <!-- Tagline -->
      <div class="mb-3">
         <label for="tagline" class="form-label font-b">Tagline</label>
         <input type="text" class="form-control" id="tagline" name="tageline" value="<?= $linktree['tagline']; ?>">
      </div>

      <!-- Social Links -->
      <div class="mb-3 row">
         <label class="form-label">Social Links</label>
         <?php foreach ($socialKeys as $socialKey) : ?>
            <label for="<?= $socialKey; ?>" class="col-sm-2 col-form-label"><?= ucfirst($socialKey); ?></label>
            <div class="col-sm-10">
               <?php $value = $linktree['social'][$socialKey] ?? null; ?>
               <input type="text" class="form-control" name="social[<?= $socialKey; ?>]" 
                  id="<?= $socialKey; ?>" value="<?= $value; ?>">
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
   console.log(links);

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
      let linkType = $('.link_option_select').val();
      let link = {
         type: linkType,
         url: '',
         clicks: 0,
      };
      links.push(link);
      $('.link_wrapper').append(renderLink(link, links.length - 1));
      addListeners();
      $('.modal').modal('hide');
   });


   function addListeners() {
      $('.delete').on('click', (e) => {
         e.preventDefault();
         let linkIndex = $(e.target).closest('.link_container').data('link-index');
         links.splice(linkIndex, 1);
         $(e.target).closest('.link_container').remove();
      });
   }

   function renderLink(link, index)
   {
      if (link.type === 'video') {
         return renderVideoLink(link, index);
      }
      return renderStandardLink(link, index);
   }


   function renderStandardLink(link, index) {
      return `
         <div class="mb-3 p-4 border border-1 link_container" data-link-index="${index}">
            <input type="hidden" name="links[${index}][type]" value="${link.type}">
            <input type="hidden" name="links[${index}][clicks]" value="${link.clicks}">
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label">Standard Link URL</label>
               <div class="col-sm-9">
                  <input type="text" class="form-control" name="links[${index}][url]" value="${link.url}">
               </div>
            </div>
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label">Icon</label>
               <div class="col-sm-9">
                  <input class="form-control form-control-sm" name="links[${index}][icon]" type="file">
               </div>
            </div>
            ${renderLinkButtons()}
         </div>
      `;
   }

   function renderVideoLink(link, index) {
      return `
         <div class="mb-3 p-4 border border-1 link_container" data-link-index="${index}">
            <input type="hidden" name="links[${index}][type]" value="${link.type}">
            <input type="hidden" name="links[${index}][clicks]" value="${link.clicks}">
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label">Video Link URL</label>
               <div class="col">
                  <input type="text" class="form-control" name="links[${index}][url]" value="${link.url}">
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