

<table id="myTable" class="table align-items-center mb-0">
    <thead>
        <tr>
            @foreach ($headers as $header)
            <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                {{__($header['value'])}}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>
@push('js')
<script>
    $(document).ready(function(){
        let table = new DataTable('#myTable',{
            ajax: "{{route(strtolower($modelClass) . '.index.api')}}",
            processing: true,
            serverSide: true
        });

    });
</script>
@endpush('js')
