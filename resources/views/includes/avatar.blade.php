@if(Auth::user()->image)
<div class="container-avatar">
    <img src="{{ route('user.avatar',['filename' =>Auth::user()->image]) }}" class="avatar">
    <!--<img src="{{ url('/user/avatar/'.Auth::user()->image) }}">-->
</div>
    
@endif
