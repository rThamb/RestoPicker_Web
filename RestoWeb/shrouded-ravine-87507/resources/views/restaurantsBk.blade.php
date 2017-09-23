@extends('layouts.app')

@section('js')
    <script src="/js/geo.js"></script>
@endsection


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Results</div>

                <div class="panel-body">
                    @if (count($restaurants) > 0)
                    <table class="table table-striped task-table">
                        <thead>
                            <th>Restaurants</th>
                            <th>&nbsp;</th>
                        </thead>
                        <tbody>
                            @foreach ($restaurants as $index => $restaurant)
                                <tr>
                                    <td class="table-text"><div>{{ $restaurant->name}}</div></td>
                                    <td class="table-text"><div>Rating: {{ $ratings[$index]->rating }} Number of reviews: {{ $ratings[$index]->num }}</div></td>
                                    
                                    <!-- Details button -->
                                    <td>
                                        <form action="{{url('/details/' . $restaurant->resto_id)}}" method="GET">

                                            <button type="submit" id="details-{{ $restaurant->resto_id }}" class="btn btn-info">
                                                Details
                                            </button>
                                        </form>
                                    </td>
                                    
                                    <!-- Favourite button -->
                                    @if (Auth::check())
                                        <td>
                                            <!-- The restaurant is not favourited -->
                                            @if (!empty($restaurant->favourites->first(function($value, $key){
                                                    return $value->user_id === Auth::user()->user_id;
                                            })))
                                            <form action="{{url('/unfavourite_restaurant/' . $restaurant->resto_id)}}" method="POST">    
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" id="unfavourite_restaurant-{{ $restaurant->resto_id }}" class="btn btn-success">
                                                    Unfavourite
                                                </button>
                                            </form>
                                            <!-- The restaurant is favourited -->
                                            @else
                                            <form action="{{url('/favourite_restaurant/' . $restaurant->resto_id)}}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('PUT') }}
                                                <button type="submit" id="favourite_restaurant-{{ $restaurant->resto_id }}" class="btn btn-success">
                                                    Favourite
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    @endif
                                    
                                    <!-- Delete button -->
                                    <!-- Could not get the delete button to apear for just the owner, says there is no $restaurant-user var -->
                                    @if (Auth::user() == $restaurant->user)
                                    <td>
                                        <form action="{{url('/delete/' . $restaurant->resto_id)}}" method="POST">
                                            
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button type="submit" id="delete-{{ $restaurant->resto_id }}" class="btn btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-offset-2">{!! $restaurants->render() !!}</div>
    </div>
</div>

@endsection