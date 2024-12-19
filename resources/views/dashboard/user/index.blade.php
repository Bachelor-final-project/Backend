<x-modal></x-modal>

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

       $(document).ready(function(){
        $('#showCreateModalBtn').on('click', function(){
            const modal = $('#addModal');
            modal.find('#modalLongTitle').text("{{__('Create New User')}}")
            $('#saveBtn').attr('disabled', true);
            // $.get("", function (data) {
                
               
            // });
            $.ajax({
                url: "{{ route('user.create') }}",
                type: 'GET',
                success: function(data){ 
                    $('#saveBtn').attr('disabled', false);
                    console.log('data fetched');
                    modal.find('.modal-body').html(data);
                },
                error: function(data) {
                    Toast.fire({
                        icon: "error",
                        title: data.responseJSON.message
                    });
                    modal.modal('hide');
                }
            });
        });
        $(document).on('click', '.show-update-modal-btn', function(){
            console.log('show-update-modal-btn clicked');
            const modal = $('#addModal');
            modal.find('#modalLongTitle').text("{{__('Update User')}}")
            $('#saveBtn').attr('disabled', true);
            const id = $(this).data('id');
            let route = "{{ route('user.index') }}/" + id + "/edit",

            $.ajax({
                url: route 
                type: 'GET',
                success: function(data){ 
                    $('#saveBtn').attr('disabled', false);
                    modal.find('.modal-body').html(data);
                },
                error: function(data) {
                    Toast.fire({
                        icon: "error",
                        title: data.responseJSON.message
                    });
                    modal.modal('hide');
                }
            });
        });
       });
        

    </script>
    @endpush
</x-layout>
