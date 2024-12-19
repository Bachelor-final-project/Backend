<x-modal></x-modal>

<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='dashboard'></x-navbars.sidebar>
    
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row mb-4">
            <x-table-card :model-class="Beneficiary::class" :headers="$headers"/>
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
            modal.find('#modalLongTitle').text("{{__('Create New Beneficiary')}}")
            $('#saveBtn').attr('disabled', true);
            $.get("{{ route('beneficiary.create') }}", function (data) {
                
                $('#saveBtn').attr('disabled', false);
                console.log('data fetched');
                modal.find('.modal-body').html(data);
            });
        });
        $(document).on('click', '.show-update-modal-btn', function(){
            console.log('show-update-modal-btn clicked');
            const modal = $('#addModal');
            modal.find('#modalLongTitle').text("{{__('Update Beneficiary')}}")
            $('#saveBtn').attr('disabled', true);
            const id = $(this).data('id');
            let base_route = "{{ route('beneficiary.index') }}";
            $.get(base_route + "/" +id+"/edit", function (data) {
                
                $('#saveBtn').attr('disabled', false);
                modal.find('.modal-body').html(data);
            });
        });
       });
        

    </script>
    @endpush
</x-layout>
