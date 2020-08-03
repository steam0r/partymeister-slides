<?php

namespace Partymeister\Slides\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;
use League\Fractal\Manager;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Services\VoteService;
use Partymeister\Competitions\Transformers\CompetitionTransformer;
use Partymeister\Competitions\Transformers\Entry\JsonTransformer;
use Partymeister\Slides\Models\Playlist;

/**
 * Class PlaylistTransformer
 *
 * @package Partymeister\Slides\Transformers
 */
class PlaylistTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    //protected $availableIncludes = ['items'];
    //
    //protected $defaultIncludes = [ 'items' ];

    /**
     * Transform record to array
     *
     * @param Playlist $record
     *
     * @return array
     */
    public function transform(Playlist $record)
    {
        $data = [
            'id' => (int)$record->id,
            'type' => $record->type,
            'name' => $record->name,
            'is_competition' => $record->is_competition,
            'competition_id' => $record->competition_id,
            'playlist_role' => $this->getPlaylistRole($record),
            'created_at' => $record->created_at,
            'updated_at' => $record->updated_at
        ];
        if ($record->is_competition && $record->competition_id) {
            $competition = Competition::where('id', $record->competition_id)
                ->first();
            if ($competition) {
                $manager = new Manager();
                $resource = $this->item($competition,
                    new CompetitionTransformer());
                $entry = $manager->createData($resource)->toArray();
                $entry = Arr::get($entry, 'data');
                $data['competition'] = $entry;
                $data['entries'] = [];
                foreach (
                    $competition->entries()->where('status', 1)
                        ->orderBy('sort_position', 'ASC')->get() as $entry
                ) {
                    $resource = $this->item($entry, new JsonTransformer());
                    $entry = $manager->createData($resource)->toArray();
                    $entry = Arr::get($entry, 'data');
                    $data['entries'][] = $entry;
                }
            }
        }else if($this->playlistIsPrizegiving($record)) {
            $results      = VoteService::getAllVotesByRank('ASC');
            $specialVotes = VoteService::getAllSpecialVotesByRank();

            foreach ($specialVotes as $entryKey => $entry) {
                if ($entryKey > 6) {
                    unset($specialVotes[$entryKey]);
                }
            }
            shuffle($specialVotes);

            $comments = [];
            foreach ($results as $competition) {
                $comments[$competition['id']] = [];
                foreach ($competition['entries'] as $entry) {
                    foreach ($entry['comments'] as $comment) {
                        if (strlen($comment) < 30) {
                            $comments[$competition['id']][] = $comment;
                            $comments[$competition['id']]   = array_unique($comments[$competition['id']]);
                        }
                    }
                }
                shuffle($comments[$competition['id']]);
                $chunks = array_chunk($comments[$competition['id']], 8);
                if (count($chunks) > 0) {
                    $comments[$competition['id']] = $chunks[0];
                } else {
                    $comments[$competition['id']] = [];
                }
                $comments[$competition['id']] = implode('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                    $comments[$competition['id']]);
            }
            $fullResults = [];
            foreach($results as $key => $competition) {
                foreach($competition['entries'] as $entryKey => $entryData) {
                    $entry = Entry::where('id', $entryData['id'])->first();
                    if($entry) {
                        $entryData['playable_files'] = JsonTransformer::getPlayableFileInfo($entry);
                    }
                    $competition['entries'][$entryKey] = $entryData;
                }
                $results[$key] = $competition;
            }
            $data['results'] = array_values($results);
        }
        return $data;
    }

    /**
     * @param Playlist $record
     *
     * @return Fractal\Resource\Collection
     */
    public function includeItems(Playlist $record)
    {
        if (!is_null($record->items)) {
            return $this->collection($record->items,
                new PlaylistItemTransformer());
        }
    }

    protected function getPlaylistRole(Playlist $playlist)
    {
        $role = "rotation";
        if($playlist->is_competition && $playlist->competition_id) {
            $role = "competition";
        }else if($this->playlistIsPrizegiving($playlist)) {
            $role = "prizegiving";
        }
        return $role;
    }

    protected function playlistIsPrizegiving(Playlist $playlist) {
        return $playlist->is_prizegiving && $playlist->is_prizegiving !== "null";
    }
}
