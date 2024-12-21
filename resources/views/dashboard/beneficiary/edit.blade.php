

<form action="{{ route('beneficiary.update', ['beneficiary' => $beneficiary]) }}" method="PUT" id="form">
    @include('dashboard.beneficiary.form')
</form>

<script>
    $('#form').submit(function(event){
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        return false;
    });
    $("#saveBtn").off('click');
    $("#saveBtn").on('click', function(){
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
                        title: "Beneficiary has been updated successfully"
                    });
                    $('#myTable').DataTable().ajax.reload()
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