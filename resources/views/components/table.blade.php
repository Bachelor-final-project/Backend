@use('App\Models\User', 'User')
@php

    $headers = User::$datatableHeaders;
@endphp

<table class="table align-items-center mb-0">
    <thead>
        <tr>
            @foreach ($headers as $header)
            <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                {{__($header)}}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>