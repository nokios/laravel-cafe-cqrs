@extends('layouts.layout')

@section('title')
    Open Tabs
@endsection

@section('content')
    <div>
        <h2>Open Tabs <span class="badge badge-info">{{ count($tabs) }}</span></h2>
        <hr/>
        <ul class="list-group">
        @foreach ($tabs as $tab)
                <li class="list-group-item" data-tabid="{{ $tab['id'] }}"><a href="/tab/{{$tab['id']}}">{{ $tab['waiter'] }} ( Table: {{ $tab['table_number'] }} )</a></li>
        @endforeach
        </ul>
        <hr/>
        <form action="/open-tab" method="POST">
            {!! csrf_field() !!}
            <input name="waiter" placeholder="Waiter name"/>
            <input name="tableNumber" placeholder="Table Number"/>
            <button type="submit">Open New Tab</button>
        </form>
    </div>
@endsection