@if (session('screens.active', null) == null)
    {{trans('partymeister-slides::backend/slide_clients.no_active_client')}}
@else
    <a href="#" class="slide-clients-playnow"
       data-file="{{$record->id}}">{{trans('partymeister-slides::backend/slide_clients.playnow')}}</a>
@endif