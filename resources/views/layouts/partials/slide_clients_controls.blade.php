<div class="float-right btn-group" style="margin-right: 25px;">
    @foreach (Partymeister\Slides\Models\SlideClient::all() as $client)
    <a href="{{route('backend.slide_clients.activate', ['slide_client' => $client->id])}}"
       class="btn btn-sm @if (session('screens.active') == $client->id) btn-success @else btn-outline-primary @endif">{{$client->name}} ({{trans('partymeister-slides::backend/slide_clients.types.'.$client->type)}}) @if (session('screens.active') == $client->id)
            ACTIVE  @endif </a>
@endforeach
</div>
@if (session('screens.active', false))
    <div class="float-right btn-group" style="margin-right: 25px;">
        <button type="button" data-direction="previous" data-hard="0"
                class="slide-clients-control btn btn-sm btn-outline-primary"><i class="fas fa-caret-square-left"></i>
        </button>
        <button type="button" data-direction="previous" data-hard="1"
                class="slide-clients-control btn btn-sm btn-outline-primary"><i class="far fa-caret-square-left"></i>
        </button>
        <button type="button" data-direction="next" data-hard="1"
                class="slide-clients-control btn btn-sm btn-outline-primary"><i class="far fa-caret-square-right"></i>
        </button>
        <button type="button" data-direction="next" data-hard="0"
                class="slide-clients-control btn btn-sm btn-outline-primary"><i class="fas fa-caret-square-right"></i>
        </button>
    </div>
@endif

