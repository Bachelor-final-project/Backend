
@use('App\Models\Beneficiary')

@php
  $fathers = Beneficiary::all();
  $fields_rows = [
    [
      ['type' => 'text', 'name' => 'name', 'label' => 'Name'],
      ['type' => 'number', 'name' => 'national_id', 'label' => 'National ID'],
      ['type' => 'number', 'name' => 'phone', 'label' => 'Phone'],
    ],
    [
      ['type' => 'email', 'name' => 'email', 'label' => 'Email'],
      ['type' => 'date', 'name' => 'dob', 'label' => 'Date of Birth'],
      ['type' => 'select-model', 'name' => 'father_id', 'label' => 'Select the father', 'options' => $fathers, "extra_options" => ['' => 'None'], 'selected'=> $beneficiary->father_id],
    ],
  ]
@endphp

<x-larastrap::form :obj="$beneficiary" :buttons="[]">
  <table class="table">
    <tbody>
      @foreach ($fields_rows as $row)
        <tr>
          @foreach ($row as $field)
            <td>
              @if ($field['type'] == 'select-model')
              <x-dynamic-component :component="'larastrap::' . $field['type']" name="{{$field['name']}}" label="{{$field['label']}}" :options="$field['options']" :extra_options="$field['extra_options']" :selected="$field['selected']" />
              @else
              <x-dynamic-component :component="'larastrap::' . $field['type']" name="{{$field['name']}}" label="{{$field['label']}}" />
              @endif
            </td>
          @endforeach
      </tr>
      @endforeach
    </tbody>
</table>
   
</x-larastrap::form>
