@include('../templates.header')
@include('../templates.navbar')
<h2>Bucket Form</h2>
@php
$url = "/buckets";
$name = "";
$volume = "";
if(isset($edit) && $edit == 1){
$url = "/buckets/$bucket->id";
$name = $bucket->name;
$volume = $bucket->volume;
}

@endphp
<form method="POST" action="{{$url}}">
    @csrf
    @if (isset($edit) && $edit == 1)
    @method('PUT')
    <input type="hidden" name="id" value="{{$bucket->id}}">
    @endif
    <div class="form-group">
        <label for="name">Bucket Name:</label>
        <input type="text" class="form-control @error('name') alert alert-danger @enderror" id="name" name='name' placeholder="Bucket name" minlength="1" value="{{$name}}">
    </div>
    <div class="form-group">
        <label for="vol">Bucket Volume:</label>
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
            <th scope="col">Bucket Name</th>
            <th scope="col">Volume</th>
            <th scope="col">Created</th>
            <th scope="col">Updated</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($buckets))
        @foreach ($buckets as $bucket)
        <tr>
            <th scope="row"><a href="/buckets/{{$bucket->id}}/edit">{{$bucket->id}}</a></th>
            <td>{{$bucket->name}}</td>
            <td>{{$bucket->volume}}</td>
            <td>{{$bucket->created_at}}</td>
            <td>{{$bucket->updated_at}}</td>
            <td style="color: red;">
                <form method='POST' action="/buckets/{{$bucket->id}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">X</button>
                </form>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="6">No bucket available...</td>
        </tr >
        @endif
     </tbody>
</table>
@endif
@include('../templates.footer')