@section('view_scripts')
    <script type="text/javascript">
        $('.slide-clients-control').on('click', function (e) {
            e.preventDefault();

            data = {
                direction: $(this).data('direction'),
                hard: $(this).data('hard')
            };

            axios.post('{{route('ajax.slide_clients.communication.skip')}}', data).then(function (response) {
                updatePlaylists();
            }).catch(function (error) {
                console.log(error);
            });
        });

        // Get playlists
        var updatePlaylists = function () {

            axios.get('{{route('ajax.slide_clients.communication.playlists')}}').then(function (playlistsResponse) {
                var xmlResponse = $.parseXML(playlistsResponse.data.result);

                $('.playlist-cached').addClass('d-none');
                $('.playlist-playing').addClass('d-none');
                $('.playlist-outdated').addClass('d-none');
                $(xmlResponse).find('data playlist').each(function (index, element) {
                    $('.playlist-' + $(element).find('name').text() + '-cached').removeClass('d-none');

                    var remotePlaylistTimestamp = parseInt($(element).find('timestamp').text());
                    var playlistTimestamp = parseInt($('.playlist-' + $(element).find('name').text() + '-outdated').data('timestamp'));

                    if (remotePlaylistTimestamp < playlistTimestamp) {
                        $('.playlist-' + $(element).find('name').text() + '-outdated').removeClass('d-none');
                    }
                });

                var currentItemCombined = $(xmlResponse).find('data item_current').text();
                var split = currentItemCombined.split('_');
                var playlistId = split[0];
                var playlistItemId = split[1];

                $('.playlist-' + playlistId + '-playing').removeClass('d-none');

                axios.get('/ajax/playlists/items/' + playlistItemId).then(function (response) {
                    $('.playlist-preview').addClass('d-none');
                    $('.playlist-' + playlistId + '-preview').removeClass('d-none');
                    $('.playlist-' + playlistId + '-preview').find('img').prop('src', response.data.data.file.preview);
                }).catch(function (error) {
                    console.log(error);
                });


            }).catch(function (error) {
                console.log(error);
            });
        };

        var seek = function (data) {
            axios.post('{{route('ajax.slide_clients.communication.seek')}}', data).then(function (response) {
                updatePlaylists();
            }).catch(function (error) {
                console.log(error);
            });
        };

        $('.slide-clients-play').on('click', function (e) {
            e.preventDefault();

            data = {
                playlist_id: $(this).data('playlist'),
                callbacks: $(this).data('callbacks')
            };

            var action = $(this).data('action');

            if (data.callbacks == 1) {
                if (!confirm('{{ trans('partymeister-slides::backend/slide_clients.callback_question') }}')) {
                    return false;
                }
            }

            axios.post('{{route('ajax.slide_clients.communication.playlist')}}', data).then(function (response) {

                if (action == 'seek') {
                    var seekData = {
                        playlist_id: data.playlist_id,
                        hard: false,
                    };

                    seek(seekData);
                    return;
                }
                updatePlaylists();
            })
                .catch(function (error) {
                    console.log(error);
                });
        });

        $('.slide-clients-playnow').on('click', function (e) {
            e.preventDefault();

            if ($(this).data('slide')) {
                data = {
                    slide_id: $(this).data('slide')
                };
                playlistId = 'playnow_slide_' + data.slide_id;
            } else if ($(this).data('file')) {
                data = {
                    file_id: $(this).data('file')
                };
                playlistId = 'playnow_file_' + data.slide_id;
            }

            axios.post('{{route('ajax.slide_clients.communication.playnow')}}', data).then(function (response) {
                // be happy, nothing broke!
            })
                .catch(function (error) {
                    console.log(error);
                });
        });
    </script>

@append