@extends('layouts.layout')

@section('title')
    Manage Tab for Table {{ $tab->getTableNumber() }}
@endsection

@section('content')
    <div>
        <h2>Add Item to Tab</h2>
        <h4>{{ $tab->getWaiter() }} ( Table {{ $tab->getTableNumber() }} )</h4>
        <hr/>
        <form action="/open-tab" method="POST">
            {!! csrf_field() !!}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <h3>Food</h3>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">#</th>
                                    <th>Description</th>
                                    <th width="15%" class="text-right">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($foodItems as $foodItem)
                                <tr>
                                    <td>{{ $foodItem['number'] }}</td>
                                    <td>{{ $foodItem['description'] }}</td>
                                    <td class="text-right">{{ $foodItem['price'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <h3>Drinks</h3>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th>Description</th>
                                <th width="15%" class="text-right">Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($drinkItems as $drinkItem)
                                <tr>
                                    <td>{{ $drinkItem['number'] }}</td>
                                    <td>{{ $drinkItem['description'] }}</td>
                                    <td class="text-right">{{ $drinkItem['price'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-group">

                <button type="submit" class="btn btn-primary">Add Item</button>
            </div>
        </form>
    </div>
@endsection