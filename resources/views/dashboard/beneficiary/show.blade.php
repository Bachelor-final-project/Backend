
@use('App\Models\Beneficiary')

@php
  $fields_rows = [
    [
      ['type' => 'text', 'name' => 'name', 'label' => 'Name'],
      ['type' => 'number', 'name' => 'national_id', 'label' => 'National ID'],
      ['type' => 'number', 'name' => 'phone', 'label' => 'Phone'],
    ],
    [
      ['type' => 'email', 'name' => 'email', 'label' => 'Email'],
      ['type' => 'date', 'name' => 'dob', 'label' => 'Date of Birth'],
      ['type' => 'select-model', 'name' => 'father_id', 'label' => 'Select the father'],
    ],
  ]
@endphp

  <table class="table">
    <tbody>
      @foreach ($fields_rows as $row)
        <tr>
          @foreach ($row as $field)
            <th>{{$field['label']}}</th>
            <td>
            @php
                $n = $field['name'];
                echo $beneficiary->$n;
            @endphp
            </td>
          @endforeach
      </tr>
      @endforeach
    </tbody>
</table>
   
