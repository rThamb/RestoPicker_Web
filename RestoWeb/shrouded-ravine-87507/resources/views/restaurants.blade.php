@extends('layouts.app')

@section('js')
    <script src="/js/geo.js"></script>
@endsection


@section('content')
<div class="container">
    @if (count($restaurants) > 0 && count($ratings) > 0)
    <div class="row">
        @foreach ($restaurants as $index => $restaurant)
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $restaurant->name }}
                    @if(isset($distances[$index]))
                        <p class="inline"> | {{ number_format($distances[$index], 2) }} km </p>
                    @endif
                </div>

                <div class="panel-body">
                    <table class="table table task-table">
                        <tbody>
                            <tr>
                                <td>
                                    <!-- Reviews -->
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $ratings[$index]->rating)
                                            <img src="{{ URL::to('images/fullStar.png') }}" class="ratingImg">
                                        @elseif($i - 1 + 0.75 <= $ratings[$index]->rating)
                                            <img src="{{ URL::to('images/fullStar.png') }}" class="ratingImg">
                                        @elseif($i - 1 + 0.25 <= $ratings[$index]->rating)
                                            <img src="{{ URL::to('images/halfStar.png') }}" class="ratingImg">
                                        @else
                                            <img src="{{ URL::to('images/emptyStar.png') }}" class="ratingImg">
                                        @endif
                                    @endfor
                                    <p>@if(!empty($ratings[$index]->rating))
                                        {{ number_format($ratings[$index]->rating, 1) }} rating | @endif
                                        {{ $ratings[$index]->num }} reviews</p>
                                </td>
                                <td>
                                    @for($i = 1; $i <= 4; $i++)
                                        @if($restaurant->priceRange >= $i)
                                            <h5 class="priceRng">&#36;</h5>
                                        @endif                            
                                    @endfor
                                </td>
                            </tr>
                            @if(!empty($restaurant->image))
                            <tr>
                                <td colspan="2">
                                    <div class="restoImg">
                                        <img src="{{ $restaurant->image }}">
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <!-- <tr>
                                <td colspan="2">
                                    <p>{{ $restaurant->notes }}</p>
                                </td>
                            </tr> -->
                            <tr>
                                <td>
                                    <p class="genre">{{ strtoupper($restaurant->genre) }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="panel-footer">
                    <table>
                        <tbody>
                                <tr>                                 
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
                                            @if (!empty($restaurant->favourites->first(
                                                function($value, $key){
                                                    return $value->user_id === Auth::user()->user_id;
                                                })
                                            ))
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
        @endforeach
    </div>
    @endif
    
    <!-- Pagination -->
    <div class="row">
        <div class="col-md-12">{!! $restaurants->render() !!}</div>
    </div>
    
</div>
@endsection
