@if ($photo==null)
    <img src="/storage/profiles/defaultpic.png" class="img-circle" width="{{$size}}" height="auto" style="max-width: 100%;">
@else
    <img src="/storage/profiles/{{$photo}}" class="img-circle" width="{{$size}}" height="{{$size}}" style="max-width: 100%; object-fit: cover">
@endif


