

<form action="{{ route('users.store') }}" method="POST" id="form">
    @include('dashboard.users.form')
</form>

<script>
    $('#form').submit(function(event){
        $('input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        event.preventDefault(); // avoid to execute the actual submit of the form.
        return false;
    });
</script>