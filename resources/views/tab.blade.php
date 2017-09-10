@extends('layouts.layout')

@section('content')
    <div>
        <h2>Open Tabs</h2>
        <hr/>
        <ul class="list-group">
            @foreach ($tabs as $tab)
                <li class="list-group-item">{{ $tab->getWaiter() }} ( Table: {{ $tab->getTableNumber() }} )</li>
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