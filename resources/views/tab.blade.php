@extends('layouts.layout')

@section('content')
    <div>
        <h2>Add Item to Tab</h2>
        <h4>{{ $tab->getWaiter() }} ( Table {{ $tab->getTableNumber() }} )</h4>
        <hr/>
        <form action="/open-tab" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="menu-item">
                    Item
                </label>
                <select name="menu-item" class="form-control">
                @foreach ($menuItems as $item)
                    <option value="{{ $item['id'] }}"></option>
                @endforeach
                </select>
                <label for="quantity">
                    Qty
                </label>
                <input name="quantity" placeholder="Qty" value="1" width="1em" class="form-control"/>
                <button type="submit" class="btn btn-primary">Add Item</button>
            </div>
        </form>
    </div>
@endsection