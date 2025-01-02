

@push('css')
    <style>
        #myTable_wrapper{
            margin: 1rem !important;
        }
    </style>
@endpush
<table id="myTable" class="table align-items-center">
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
            ajax: {
                url: "{{route(strtolower($modelClass) . '.index.api')}}",
                dataFilter: function(response){
                    var json = jQuery.parseJSON( response );
                    json.recordsTotal = json.meta.total;
                    json.recordsFiltered = json.meta.total;
                    json.data = json.data;
                    console.log(json)
                    return JSON.stringify( json ); // return JSON string
                },
            },
            processing: true,
            serverSide: true,
            columns: [
                
                @foreach ($headers as $header)
                    @if ($header['key'] != 'actions')
                        {"data": "{{$header['key']}}"},    
                    @endif
                @endforeach
                {
                    "data":"id",
                    "render": function (id) {
                        console.log(id);
                        return `
                        @foreach ($headers as $header)
                            @if ($header['key'] == 'actions')
                                
                                @if (in_array("show", $header['actions']))
                                    <button data-id="${id}" class="btn btn-info p-2 show-show-modal-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i class="material-icons opacity-10">visibility</i></button>
                                @endif
                                @if (in_array("update", $header['actions']))
                                    <button data-id="${id}" class="btn btn-warning p-2 show-update-modal-btn" data-bs-toggle="modal" data-bs-target="#mainModal"><i class="material-icons opacity-10">edit</i></button>
                                @endif
                                @if (in_array("delete", $header['actions']))
                                    <button data-id="${id}" class="btn btn-danger p-2 show-delete-modal-btn"><i class="material-icons opacity-10">delete</i></button>
                                @endif
                            @endif
                        @endforeach
                        `;
                    }
                }
                
            ]
        });
       
    });
</script>
@endpush('js')
