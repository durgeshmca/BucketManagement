@include('../templates.header')
@include('../templates.navbar')
@php
$url = "/balls";
$name =  "";
$volume = "";
if(isset($edit) && $edit == 1){
    $url = "/balls/$ball->id";
    $name =  $ball->name;
    $volume = $ball->volume;
}

@endphp
<h2>Ball Form</h2>
<form method="POST" action="{{$url}}">
    @csrf
    @if (isset($edit) && $edit == 1)
    @method('PUT')
    @endif
    <div class="form-group">
        <label for="name">Ball Name:</label>
        <input type="text" class="form-control @error('name') alert alert-danger @enderror" id="name" name='name' value="{{$name}}" placeholder="Ball name" minlength="1">
    </div>
    <div class="form-group">
        <label for="vol">Ball Volume:</label>
        <input type="number" step="0.01" class="form-control @error('volume') alert alert-danger @enderror" id="vol" name="volume" placeholder="Volume" min="0" value="{{$volume}}">
    </div>
    <button type="submit" class="btn btn-default">
    @if (isset($edit) && $edit == 1)
    Update
    @else 
    Save
    @endif
        
    </button>
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
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
@if (!empty($message))
<div class="alert alert-danger">
    {{$message}}
</div>
@endif
@if (!isset($edit) || $edit == 0)
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Ball Name</th>
            <th scope="col">Volume</th>
            <th scope="col">Created</th>
            <th scope="col">Updated</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($balls))
        @foreach ($balls as $ball)
        <tr>
            <th scope="row"><a href="/balls/{{$ball->id}}/edit">{{$ball->id}}</a></th>
            <td>{{$ball->name}}</td>
            <td>{{$ball->volume}}</td>
            <td>{{$ball->created_at}}</td>
            <td>{{$ball->updated_at}}</td>
            <td style="color: red;">
                <form method='POST' action="/balls/{{$ball->id}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">X</button>
                </form>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="6" scope="row">No balls available...</td>
        </tr>
        @endif
     </tbody>
</table>
@endif
@include('../templates.footer')