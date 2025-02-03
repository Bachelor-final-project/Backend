@extends('layouts.exportPdf')


@section('title', __('View Proposal'))

@section('content')

<div class="">
    <header>
        <h2 class="capitalize text-3xl text-center font-medium text-gray-900 ">
            {{ __('proposal details') }}
            {{app()->getLocale()}}
        </h2>
        {{-- <h2>
            {{ asset('assets/img/ReportBackground.jpg') }}
        </h2> --}}
    </header>
    
    <div class="mt-6 space-y-6">
        <div class="grid grid-cols-4 gap-4 ">
            @foreach ([
                'title' => $item->title,
                'proposal type' => $item->proposal_type_type_ar,
                'area' => $item->area_name,
                'entity' => $item->entity_name,
                'cost' => $item->cost,
                'share cost' => $item->share_cost,
                'min documenting amount' => $item->min_documenting_amount,
                'currency' => $item->currency_name,
                'expected benificiaries count' => $item->expected_benificiaries_count,
                'publishing date' => $item->publishing_date,
                'execution date' => $item->execution_date
            ] as $label => $value)
                <div class="break-inside-avoid">
                    <p class="font-medium">{{ __( $label ) }}</p>
                    <p class="text-gray-700">{{ $value }}</p>
                </div>
            @endforeach
            
            @if($item->currency_id == 1)
                <div>
                    <p class="font-medium">{{ __('can be paid online') }}</p>
                    <p class="text-gray-700">{{ $item->isPayableOnline ? __('yes') : __('no') }}</p>
                </div>
            @endif
        </div>

        @foreach ([
            'proposal body' => $item->body,
            'proposal effects' => $item->proposal_effects,
            'notes' => $item->notes
        ] as $label => $value)
            <div class="break-inside-avoid">
                <p class="font-medium ">{{ __( $label ) }}</p>
                <p class="text-gray-700">{{ $value }}</p>
            </div>
        @endforeach
    </div>

</div>

@if($item->files->count())
<div class="bg-white  my-4 pt-4">
    <header>
        <h2 class="py-2 bg-white  text-center capitalize text-lg font-medium text-gray-900 ">
            {{ __('Proposal Files') }}
        </h2>
    </header>
    
    <table class="table-auto w-full text-sm text-start text-gray-500 ">
        <thead class="border-b-2 text-xs text-gray-700 uppercase bg-gray-100  ">
            <tr>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">{{ __('File Type') }}</th>
                <th class="px-6 py-3">{{ __('File Name') }}</th>
                <th class="px-6 py-3">{{ __('Link') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($item->files as $index => $file)
            <tr class="bg-white border-b  ">
                <td class="px-6 py-2 font-semibold text-black ">{{ $index + 1 }}</td>
                <td class="px-6 py-2 font-semibold text-black ">{{ $file->attachment_type_name }}</td>
                <td class="px-6 py-2 font-semibold text-black ">{{ $file->filename }}</td>
                <td class="px-4 py-2 text-blue-600 ">
                    <a href="{{ $file->url }}" target="_blank" rel="noopener noreferrer" class="underline">{{ __('Download') }}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($item->documents->count())
    @include('components.table', ['title' => 'Documents', 'items' => $item->documents, 'headers' => $documents_headers, 'model' => 'document', 'add_item_route' => 'document.create'])
@endif

@if($item->donations->count())
    @include('components.table', ['title' => 'Donations', 'items' => $item->donations, 'headers' => $donations_headers, 'model' => 'donation', 'add_item_route' => 'donation.create'])
@endif

@if($item->beneficiaries->count())
    @include('components.table', ['title' => 'Beneficiaries', 'items' => $item->beneficiaries, 'headers' => $beneficiaries_headers, 'model' => 'beneficiary', 'add_item_route' => 'beneficiary.create'])
@endif
@endsection
