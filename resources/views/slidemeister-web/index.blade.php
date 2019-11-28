@extends('partymeister-slides::layouts.slidemeister-web')

@section('view_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof(Storage) == undefined) {
                console.log('No web storage support :/');
            }

            let sm = VueApp.$refs.slidemeisterweb;

            Echo.channel('{{$channelPrefix}}:slidemeister-web.{{$slideClientId}}')
                .listen('.Partymeister\\Slides\\Events\\PlayNowRequest', (e) => {
                    console.log('PlayNowRequest incoming');
                    if (sm.playlist.id != undefined) {
                        console.log('Playlist is running - saving position and playlist');
                        sm.playlistSaved = sm.playlist;
                        sm.currentItemSaved = sm.currentItem;
                    }

                    e.item.duration = 20;
                    e.item.is_advanced_manually = true;
                    e.item.transition_identifier = 0;
                    e.item.transition_duration = 2000;
                    e.item.callback_hash = '';
                    e.item.callback_delay = 20;

                    sm.playnowItem = e.item;
                    sm.playnow = true;
                    sm.insertPlayNow();
                })
                .listen('.Partymeister\\Slides\\Events\\PlaylistNextRequest', (e) => {
                    console.log('PlaylistNextRequest incoming');
                    if (sm.playlist.id == undefined) {
                        console.log('No playlist is running, aborting');
                    } else {
                        sm.playnow = false;
                        sm.seekToNextItem(e.hard);
                    }
                    sm.updateStatus();
                })
                .listen('.Partymeister\\Slides\\Events\\PlaylistPreviousRequest', (e) => {
                    console.log('PlaylistPreviousRequest incoming');
                    if (sm.playlist.id == undefined) {
                        console.log('No playlist is running, aborting');
                    } else {
                        sm.playnow = false;
                        sm.seekToPreviousItem(e.hard);
                    }
                    sm.updateStatus();
                })
                .listen('.Partymeister\\Slides\\Events\\PlaylistRequest', (e) => {
                    console.log('PlaylistRequest incoming');

                    let found = false;
                    for (const [index, p] of sm.cachedPlaylists.entries()) {
                        if (p.id == e.playlist.id) {
                            console.log('Playlist exists, checking if it needs to be updated');
                            // Update callback status
                            sm.cachedPlaylists[index].callbacks = e.playlist.callbacks;
                            if (p.updated_at != e.playlist.updated_at.date) {
                                console.log('Playlist outdated, checking if it is currently playing');
                                sm.cachedPlaylists[index] = e.playlist;
                                if (sm.playlist.id == p.id) {
                                    console.log('Playlist is currently playing. Updating');
                                    if (sm.currentItem > (e.playlist.items.length - 1)) {
                                        console.log('Updated playlist has fewer items, resetting index of currently playing item');
                                        sm.currentItem = e.items.length - 1;
                                    }

                                    sm.playlist = e.playlist;
                                    sm.items = e.playlist.items;

                                    localStorage.setItem('cachedPlaylists', JSON.stringify(sm.cachedPlaylists));
                                    localStorage.setItem('currentItem', sm.currentItem);
                                    localStorage.setItem('playlist', JSON.stringify(sm.playlist));
                                }
                            }
                            found = true;
                        }
                    }
                    if (!found) {
                        console.log('Playlist does not exist yet. Caching it');
                        sm.cachedPlaylists.push(e.playlist);
                        localStorage.setItem('cachedPlaylists', JSON.stringify(sm.cachedPlaylists));
                    }
                    sm.updateStatus();
                })
                .listen('.Partymeister\\Slides\\Events\\PlaylistSeekRequest', (e) => {
                    console.log('PlaylistSeekRequest incoming');

                    let found = false;
                    for (const [index, p] of sm.cachedPlaylists.entries()) {
                        if (p.id == e.playlist_id) {
                            console.log('Playlist exists, seeking to item ' + e.index);
                            if (sm.playlist.id == e.playlist_id) {
                                console.log('Playlist is running, seeking to item ' + e.index);
                                sm.seekToIndex(parseInt(e.index));
                            } else {
                                console.log('Playlist is not running yet. Setting it and seeking to item ' + e.index);
                                sm.playlist = p;
                                sm.items = p.items;
                                sm.playnow = false;
                                localStorage.setItem('playlist', JSON.stringify(sm.playlist));
                                setTimeout(() => {
                                    sm.seekToIndex(parseInt(e.index));
                                }, 200);
                            }

                            found = true;
                        }
                    }
                    if (!found) {
                        console.log('Playlist not found, cannot seek to index ' + e.index);
                    }
                    sm.updateStatus();
                });
        });
    </script>
@append
