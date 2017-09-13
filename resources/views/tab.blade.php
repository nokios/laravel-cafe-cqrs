@extends('layouts.layout')

@section('title')
    Manage Tab for Table {{ $tab->getTableNumber() }}
@endsection

@section('content')
    <h2>Add Item to Tab</h2>
    <h4>{{ $tab->getWaiter() }} ( Table {{ $tab->getTableNumber() }} )</h4>
    <hr/>
    <form action="/tab/{{$tab->getId()}}/add-items" method="POST">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <h3>Food</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%"><span class="glyphicon glyphicon-check"></span></th>
                                <th width="10%">#</th>
                                <th>Description</th>
                                <th width="15%" class="text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($foodItems as $foodItem)
                            <tr>
                                <td><input type="checkbox" name="menuItems" value="{{ $foodItem['id'] }}"/></td>
                                <td>{{ $foodItem['id'] }}</td>
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

                            <th width="5%"><span class="glyphicon glyphicon-check"></span></th>
                            <th width="10%">#</th>
                            <th>Description</th>
                            <th width="15%" class="text-right">Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($drinkItems as $drinkItem)
                            <tr>
                                <td><input type="checkbox"  name="menuItems" value="{{ $drinkItem['id'] }}"/></td>
                                <td>{{ $drinkItem['id'] }}</td>
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
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-plus-sign"></span> Place Order
            </button>
        </div>
    </form>
    <br/>
    <hr/>
    <item-menu></item-menu>
    <hr/>
    <hr/>
    <h3>Add New Menu Item</h3>
    <div class="form-inline">
        <form action="/menu/add-item" method="POST">
            <input type="hidden" id="tabId" value="{{ $tab->getId() }}">
            <div class="form-group">
                <label for="description">Description: </label>
                <input type="text" class="form-control" id="description" placeholder="Delicious Cake..."/>
            </div>
            <div class="form-group">
                <label for="is_drink">Drink?: </label>
                <input type="checkbox" value="1" class="form-control" id="is_drink"/>
            </div>
            <div class="form-group">
                <label for="price">$: </label>
                <input type="text" class="form-control" id="price" placeholder="2.50"/>
            </div>
            <button type="submit" class="btn btn-primary">Add Menu Item</button>
        </form>
    </div>
@endsection