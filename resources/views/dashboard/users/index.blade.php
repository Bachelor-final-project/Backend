<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="d-flex justify-content-center m-3">
                <div class="spinner-border" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
        </div>
      </div>
    </div>
  </div>


<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='dashboard'></x-navbars.sidebar>
    
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row mb-4">
            <x-table-card :model-class="User::class" :headers="$headers"/>
            </div>
            <x-footers.auth></x-footers.auth>
        </div>
    </main>
    <x-plugins></x-plugins>
    </div>
    @push('js')
    <script>
        $('#addModal').on('show.bs.modal', function(){
            const modal = $(this);
            $('#saveBtn').attr('disabled', true);
            $.get("{{ route('user.create') }}", function (data) {
                
                $('#saveBtn').attr('disabled', false);
                console.log('data fetched');
                modal.find('.modal-body').html(data);
            });
        });
        $('#addModal').on('hide.bs.modal', function(){
            const modal = $(this);
            
                modal.find('.modal-body').html(`
                <div class="d-flex justify-content-center m-3">
                <div class="spinner-border" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
                `);
            
        });
        $('#addModal').find("#saveBtn").on('click', function(){
            $('#saveBtn').attr('disabled', true)
            const form = $('#form');
            console.log(form.attr('method'))
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success:function(data){
                    console.log('from success');
                    if(!data){
                        Toast.fire({
                            icon: "error",
                            title: "there is a problem"
                        });
                        return false;
                    }

                    Toast.fire({
                        icon: "success",
                        title: data.message
                    });
                },
                error:function (errors) {
                    console.log('from error');
                    console.log(errors);

                    Toast.fire({
                        icon: "error",
                        title: errors.responseJSON.message
                    });
                },
                complete: function(){
                    $('#saveBtn').attr('disabled', false)

                }

            });
        });
    
    </script>
    @endpush
</x-layout>
