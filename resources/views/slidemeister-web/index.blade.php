@extends('partymeister-slides::layouts.slidemeister-web')

@section('view_scripts')
    <script>
        console.log("Slidemeister Web loaded");

        Echo.channel('slidemeister.{{$slideClient->id}}')
            .listen('.Partymeister\\Slides\\Events\\PlayNowRequest', (e) => {
                console.log('PlayNowRequest incoming');
                if (slidemeisterVue.playlist.id != undefined) {
                    console.log('Playlist is running - saving position and playlist');
                    slidemeisterVue.playlistSaved = slidemeisterVue.playlist;
                    slidemeisterVue.currentItemSaved = slidemeisterVue.currentItem;
                }

                e.item.duration = 20;
                e.item.is_advanced_manually = true;
                e.item.transition_identifier = 0;
                e.item.transition_duration = 2000;
                e.item.callback_hash = '';
                e.item.callback_delay = 20;

                console.log(e.item);

                slidemeisterVue.playnow = true;
                slidemeisterVue.playlist = {id: 'playnow', name: 'playnow', items: [e.item]};
                slidemeisterVue.items = [e.item];
                slidemeisterVue.seekToIndex(0, false);
            })
            .listen('.Partymeister\\Slides\\Events\\PlaylistNextRequest', (e) => {
                console.log('PlaylistNextRequest incoming');
                if (slidemeisterVue.playlist.id == undefined) {
                    console.log('No playlist is running, aborting');
                } else {
                    slidemeisterVue.playnow = false;
                    slidemeisterVue.seekToNextItem(e.hard);
                }

            })
            .listen('.Partymeister\\Slides\\Events\\PlaylistPreviousRequest', (e) => {
                console.log('PlaylistPreviousRequest incoming');
                if (slidemeisterVue.playlist.id == undefined) {
                    console.log('No playlist is running, aborting');
                } else {
                    slidemeisterVue.playnow = false;
                    slidemeisterVue.seekToPreviousItem(e.hard);
                }

            })
            .listen('.Partymeister\\Slides\\Events\\PlaylistRequest', (e) => {
                console.log('PlaylistRequest incoming');
                console.log(e);

                let found = false;
                for (const [index, p] of slidemeisterVue.cachedPlaylists.entries()) {
                    if (p.id == e.playlist.id) {
                        console.log('Playlist exists, checking if it needs to be updated');
                        // Update callback status
                        slidemeisterVue.cachedPlaylists[index].callbacks = e.playlist.callbacks;
                        if (p.updated_at.date != e.playlist.updated_at.date) {
                            console.log('Playlist outdated, checking if it is currently playing');
                            slidemeisterVue.cachedPlaylists[index] = e.playlist;
                            if (slidemeisterVue.playlist.id == p.id) {
                                console.log('Playlist is currently playing. Updating');
                                if (slidemeisterVue.currentItem > (e.playlist.items.length - 1)) {
                                    console.log('Updated playlist has fewer items, resetting index of currently playing item');
                                    slidemeisterVue.currentItem = e.items.length - 1;
                                }

                                slidemeisterVue.playlist = e.playlist;
                                slidemeisterVue.items = e.playlist.items;
                            }
                        }
                        found = true;
                    }
                }
                if (!found) {
                    console.log('Playlist does not exist yet. Caching it');
                    slidemeisterVue.cachedPlaylists.push(e.playlist);
                }
            })
            .listen('.Partymeister\\Slides\\Events\\PlaylistSeekRequest', (e) => {
                console.log('PlaylistSeekRequest incoming');

                let found = false;
                for (const [index, p] of slidemeisterVue.cachedPlaylists.entries()) {
                    if (p.id == e.playlist_id) {
                        console.log('Playlist exists, seeking to item ' + e.index);
                        if (slidemeisterVue.playlist.id == e.playlist_id) {
                            console.log('Playlist is running, seeking to item ' + e.index);
                            slidemeisterVue.seekToIndex(parseInt(e.index));
                        } else {
                            console.log('Playlist is not running yet. Setting it and seeking to item ' + e.index);
                            slidemeisterVue.playlist = p;
                            slidemeisterVue.items = p.items;
                            slidemeisterVue.playnow = false;
                            setTimeout(() => {
                                slidemeisterVue.seekToIndex(parseInt(e.index));
                            }, 200);
                        }

                        found = true;
                    }
                }
                if (!found) {
                    console.log('Playlist not found, cannot seek to index ' + e.index);
                }
            });
    </script>
@append