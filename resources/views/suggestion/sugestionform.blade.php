@include('../templates.header')
@include('../templates.navbar')
<h2>Bucket Suggestion</h2>
<div class="row">
    <div class="col-sm-6" style="background-color:lavender;">
        <form method="POST" action="/bucket_suggestions" style="margin: top 10px;margin-bottom:10px;">
            @csrf
            @foreach ($balls as $ball )
            <div class="form-group">
                <label for="{{$ball->name}}">{{$ball->name}}:</label>
                <input type="number" class="form-control @error('{{$ball->name}}') alert alert-danger @enderror" id="{{$ball->name}}" name='{{$ball->name}}' value="{{$ball->bucket_suggestion->no_of_balls ?? 0}}" placeholder="Number of balls" min="0">
            </div>
            @endforeach
            <button type="submit" class="btn btn-default">Place Balls in Bucket</button>
        </form>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (!empty($message))
        <div class="alert alert-danger">
            {{$message}}
        </div>
        @endif
    </div>
    <div class="col-sm-6" style="background-color:lavenderblush;">
        <div style="margin: top 10px;margin-bottom:10px;">
            <h2>Result</h2>
            <h3>Followings are the sugested buckets</h3>
        </div>
        
        @if (!empty($result))
        <h3>Followings is the current status of buckets after placing all posible balls</h3>
        <ul>
            @foreach ($result as $bucketName => $value)
            <li>Bucket {{$bucketName}}: <b>Place {{substr($value,0,-4)}}</b> </li>
            @endforeach
        </ul>
        @else
        No balls in any buckets
        @endif
        <div>

            @if (!empty($suggestions['current_suggestion']))
            <h3>Followings is the ball placement as per current suggestion</h3>
            <ul>
                @foreach ($suggestions['current_suggestion'] as $bktName=>$ballArray)
                @php
                $str = "Bucket $bktName";
                foreach ($ballArray as $blName => $val){
                $str .= ' added '.$val.' ' .$blName .' balls';
                }
                @endphp
                <li>{{$str}} </li>

                @endforeach
            </ul>
            @endif

            @if (!empty($suggestions['missed']))
            {{-- print balls details which are not placed --}}
            <ul>
                @foreach ($suggestions['missed'] as $ballName => $value)
                @if ($value > 0)
                <li>No of {{$value}} {{$ballName}} Ball/s could not be placed into any bucket </li>
                @endif
                @endforeach
            </ul>
            @endif
        </div>
    </div>


</div>


@include('../templates.footer')