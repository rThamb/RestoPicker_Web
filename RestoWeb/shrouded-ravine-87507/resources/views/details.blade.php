@extends('layouts.app')

@section('content')    
<div class="container">
    <div class="row">
        <!-- Restaurant Details -->
        <div class="col-md-7">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3>{{$restaurant->name}}</h3></div>
                
                <div class="panel-body">
                    
                    <!-- Reviews -->
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $restoRating->rating)
                            <img src="{{ URL::to('images/fullStar.png') }}" class="ratingImgLarge">
                        @elseif($i - 1 + 0.75 <= $restoRating->rating)
                            <img src="{{ URL::to('images/fullStar.png') }}" class="ratingImgLarge">
                        @elseif($i - 1 + 0.25 <= $restoRating->rating)
                            <img src="{{ URL::to('images/halfStar.png') }}" class="ratingImgLarge">
                        @else
                            <img src="{{ URL::to('images/emptyStar.png') }}" class="ratingImgLarge">
                        @endif
                    @endfor
                    
                    <br/><br/>
                    
                    @for($i = 1; $i <= 4; $i++)
                        @if($restaurant->priceRange >= $i)
                            <h5 class="priceRng">&#36;</h5>
                        @endif                            
                    @endfor
                    
                    <p class="inline"> |
                        @if(!empty($restoRating->rating))
                        {{ number_format($restoRating->rating, 1) }} rating | 
                        @endif
                        {{ $restoRating->num }} reviews
                    </p>
                    
                    <hr/>
                    
                    @if(!empty($restaurant->image))
                    <div class="restoImgDetail">
                        <img src="{{ $restaurant->image }}">
                    </div>
                    @endif
                    
                    <br/><p><strong>{{$restaurant->notes}}</strong></p>
                    
                    <hr/>
                    
                    <p>Location: {{$restaurant->address.", ".$restaurant->city.", "
                                .$restaurant->postalCode}}</p>
                    
                    <hr/>
                    
                    <p class="genre">{{ strtoupper($restaurant->genre) }}</p>
                    
                    <hr/>
                    
                    <table>
                        <tr>
                            <td>
                            @if (Auth::user() == $restaurant->user)
                            <form action="{{url('/edit/' . $restaurant->resto_id)}}" method="GET">

                                <button type="submit" id="edit-{{ $restaurant->resto_id }}" class="btn btn-warning">
                                    Edit
                                </button>
                            </form>
                            @endif
                            </td>

                            <td>
                            <!-- Favourite button -->
                            @if (Auth::check())
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
                            @endif
                            </td>

                            <td>
                            <!-- Delete button -->
                            @if (Auth::user() == $restaurant->user)
                            <form action="{{url('/delete/' . $restaurant->resto_id)}}" method="POST">

                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button type="submit" id="delete-{{ $restaurant->resto_id }}" class="btn btn-danger">
                                    Delete
                                </button>
                            </form>
                            @endif
                            </td>
                        </tr>
                    </table>
                    
                    
                </div>
            </div>
        </div>
        
        <!-- Restaurant Write a Review -->
        <div class="col-md-5">
            <div class="panel panel-info"> 
                
                <div class="panel-heading"><h3>Write a Review</h3></div>
                
                <div class="panel-body">
                    @if (Auth::check())
                    
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/review/'.$restaurant->resto_id) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}                       

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                            <label for="comment" class="col-md-4 control-label">Comment</label>

                            <div class="col-md-6">
                                <textarea id="comment" type="text" class="form-control" name="comment" value="{{ old('comment') }}" required></textarea>

                                @if ($errors->has('comment'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('comment') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
                            <label for="rating" class="col-md-4 control-label">Rating (1-5)</label>

                            <div class="col-md-6">
                                <input id="rating" type="number" class="form-control" name="rating" value="{{ old('rating') }}" required>

                                @if ($errors->has('rating'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rating') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                  

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Add Review
                                </button>
                            </div>
                        </div>
                    </form>                       
                    @endif              
                </div>
            <!-- Restaurant Reviews -->
                <div class="panel-heading"><h3>Reviews</h3></div>
                @foreach($reviews as $review)
                    <div class="panel-body">
                        <div class="reviewBorder">
                            <div class="review">
                                <h4><b>"{{ $review->title }}"</b> by {{ $review->user->username }}</h4>
                                
                                <!-- Review stars -->
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <img src="{{ URL::to('images/fullStar.png') }}" class="ratingImg">
                                    @elseif($i - 1 + 0.75 <= $review->rating)
                                        <img src="{{ URL::to('images/fullStar.png') }}" class="ratingImg">
                                    @elseif($i - 1 + 0.25 <= $review->rating)
                                        <img src="{{ URL::to('images/halfStar.png') }}" class="ratingImg">
                                    @else
                                        <img src="{{ URL::to('images/emptyStar.png') }}" class="ratingImg">
                                    @endif
                                @endfor
                                
                                <h4><i>{{ $review->updated_at }}</i></h4>

                                <hr/>
                                
                                <h3 style="display:inline;">{{ $review->comment }}</h4>

                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- Reviews Pagination -->
                <div class="panel-footer">
                    <div>{!! $reviews->render() !!}</div>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection