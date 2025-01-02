<div class="modal fade" id="{{$attributes['id']}}" tabindex="-1" role="dialog" aria-labelledby="mainModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLongTitle">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
          @if ($saveBtn)
            <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
          @endif
        </div>
      </div>
    </div>
  </div>

  @push('js')
  <script>
  $('#mainModal').on('hide.bs.modal', function(){
    const modal = $(this);
    modal.find('#modalLongTitle').text('')
        modal.find('.modal-body').html(`
                <div class="d-flex justify-content-center m-3">
                <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
            </div>
                `); 

        });
</script>
  @endpush