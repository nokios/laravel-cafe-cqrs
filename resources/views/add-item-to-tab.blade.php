@extends('layouts.layout')

@section('content')
    <div>
        <h2>Add Item to Tab</h2>
        <h4>{{ $tab['waiter'] }} ( Table {{ $tab['table_number'] }} )</h4>
        <hr/>
        <form action="/open-tab" method="POST">
            {!! csrf_field() !!}
            <select name="item">
            @foreach ($menuItems as $item)
                <option value="{{ $item['id'] }}"></option>
            @endforeach
            </select>
            <input name="quantity" placeholder="Qty" value="1"/>
            <button type="submit">Open New Tab</button>
        </form>
    </div>
@endsection