@csrf

<div class="input-group input-group-outline mt-3">
    <label class="form-label">Name</label>
    <input required type="text" class="form-control" name="name"
        value="{{ old('name', $user->name) }}">
</div>
    <script>
       $('.input-group input').each(function(e){
        const text_val = $(this).val();
        console.log(text_val);
        if (text_val === "") {
          $(this).parent().removeClass('is-filled');
        } else {
          $(this).parent().addClass('is-filled');
        }
    });
    $('.input-group input').keyup(function(){
        const text_val = $(this).val();
        console.log(text_val);
        if (text_val === "") {
          $(this).parent().removeClass('is-filled');
        } else {
          $(this).parent().addClass('is-filled');
        }
    });
        
    </script>