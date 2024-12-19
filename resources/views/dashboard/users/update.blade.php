

<form action="{{ route('user.update', ['user' => $user]) }}" method="PUT" id="form">
    @include('dashboard.users.form')
</form>

<script>
    $('#form').submit(function(event){
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        return false;
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