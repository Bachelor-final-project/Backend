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
            <x-table-card :model-class="User::class" />
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
            $.get("{{ route('users.create') }}", function (data) {
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
            const form = $('#form');
            $('#saveBtn').attr('disabled', true)

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
                                icon: data.status,
                                title: data.title
                            });
                        },
                        error:function (errors) {
                            console.log('from error');
                            console.log(errors);

                            Toast.fire({
                                icon: "error",
                                title: errors.statusText
                            });
                        },
                        complete: function(){
                            $('#saveBtn').attr('disabled', false)

                        }

                    });
        });
    $('#createModalBtn').on('click', function () {
        
            Swal.fire({
                title: "{{__('Add New User')}}",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonText: "{{__('Add')}}",
                didOpen: () => {
                    Swal.showLoading();
                    $.get("{{ route('users.create') }}", function (data) {
                        Swal.hideLoading();
                        Swal.update({
                            html: data
                        });
                    });

                },
                showCloseButton: true,
                
            }).then((result) => {
                if(result.isConfirmed){
                    console.log("it is confired");
                    const form = $('#form');
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: form.serialize(),
                        success:function(data){
                            Toast.fire({
                                icon: data.status,
                                title: data.title
                            });
                        },
                        error:function (errors) {
                            Toast.fire({
                                icon: "error",
                                title: data.title
                            });
                            // const entries = Object.entries(errors.responseJSON.errors);
                            // console.log(entries);
                            // var errors_message = document.createElement('div');
                            // for(let x of entries){
                            //     if(x[0].includes('.')){
                            //         var key = x[0].split('.');
                            //         errors_message = document.createElement('div');
                            //         errors_message.classList.add('invalid-feedback');
                            //         errors_message.classList.add('show');
                            //         document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].classList.add('is-invalid');
                            //         errors_message.innerHTML = x[1][0];
                            //         document.querySelectorAll('input[name="' + key[0] + '[]"]')[key[1]].parentElement.appendChild(errors_message);
                            //     }else {
                            //         if (document.querySelector('input[name="' + x[0] + '"]')) {
                            //             errors_message = document.createElement('div');
                            //             errors_message.classList.add('invalid-feedback');
                            //             errors_message.classList.add('show');
                            //             document.querySelector('input[name="' + x[0] + '"]').classList.add('is-invalid');
                            //             errors_message.innerHTML = x[1][0];
                            //             document.querySelector('input[name="' + x[0] + '"]').parentElement.appendChild(errors_message);
                            //         } else {
                            //             errors_message = document.createElement('div');
                            //             errors_message.classList.add('invalid-feedback');
                            //             errors_message.classList.add('show');
                            //             document.querySelector('input[name="area_name"]').classList.add('is-invalid');
                            //             errors_message.innerHTML = x[1][0];
                            //             document.querySelector('input[name="area_name"]').parentElement.appendChild(errors_message);
                            //         }
                            //     }
                            // }
                        }

                    });
                }
            });
    });
    </script>
    @endpush
</x-layout>
