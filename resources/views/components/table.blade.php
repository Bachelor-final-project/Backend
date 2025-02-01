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
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-gray-100 text-white">
                {{ __($header['value']) }}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
            @foreach ($headers as $header)
                @if ($header['key'] != 'actions')
                    <td>{{ $item[$header['key']] ?? 'N/A' }}</td>
                @endif
            @endforeach
            <td>
                @foreach ($headers as $header)
                    @if ($header['key'] == 'actions')
                        @if (in_array("show", $header['actions']))
                            <button data-id="{{ $item['id'] }}" class="btn btn-info p-2 show-show-modal-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i class="material-icons opacity-10">visibility</i></button>
                        @endif
                        @if (in_array("update", $header['actions']))
                            <button data-id="{{ $item['id'] }}" class="btn btn-warning p-2 show-update-modal-btn" data-bs-toggle="modal" data-bs-target="#mainModal"><i class="material-icons opacity-10">edit</i></button>
                        @endif
                        @if (in_array("delete", $header['actions']))
                            <button data-id="{{ $item['id'] }}" class="btn btn-danger p-2 show-delete-modal-btn"><i class="material-icons opacity-10">delete</i></button>
                        @endif
                    @endif
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
