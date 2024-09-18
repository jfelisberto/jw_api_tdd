@extends('pdf.layout', ['pdf_title' => $data->filetile])

@section('content')
    @include('pdf.table_header', ['th_title' => 'Cadastro de Cliente', 'type'=>'societe'])

    <div class="items">
        <table class="table">
            <thead>
                <tr>
                @if(isset($data->tr_titles) && !empty($data->tr_titles))
                    @foreach ($data->tr_titles as $title)
                    <th>
                        {{ $title }}
                    </th>
                    @endforeach
                @endif
                </tr>
            </thead>
            <tbody>
            @if(isset($data->tr_titles) && !empty($data->tr_titles))
                @foreach ($data->itens as $item)
                <tr>
                    <td>
                        {{ $item->id }}
                    </td>
                    <td>
                        {{ $item->title }}
                    </td>
                    <td class="tdcol40">
                        {{ $item->description }}
                    </td>
                    <td>
                        {{ $item->owner }}
                    </td>
                    <td>
                        {{ $item->status }}
                    </td>
                    <td class="tdcol9">
                        {{ \Carbon\Carbon::parse($item->duedate_at)->format('d/m/Y') }}
                    </td>
                    <td class="tdcol9">
                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                    </td>
                    <td>
                        @foreach($item->relationship as $key => $relation)
                            @if ($key == 0)
                            {{ $relation->user_relation }}
                            @else
                            , {{ $relation->user_relation }}
                            @endif
                        @endforeach
                    </td>
                </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

@endsection
