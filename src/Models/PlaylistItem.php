<?php

namespace Partymeister\Slides\Models;

use Illuminate\Database\Eloquent\Model;
use Motor\Core\Traits\Searchable;
use Motor\Core\Traits\Filterable;
use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;
use Motor\Media\Models\File;
use Motor\Media\Models\FileAssociation;

/**
 * Partymeister\Slides\Models\PlaylistItem
 *
 * @property int $id
 * @property int $playlist_id
 * @property string $type
 * @property int $sort_position
 * @property string $slide_type
 * @property int|null $slide_id
 * @property int $duration
 * @property int|null $transition_id
 * @property int $transition_duration
 * @property int $is_advanced_manually
 * @property int $is_muted
 * @property int $midi_note
 * @property mixed $metadata
 * @property string $callback_hash
 * @property int $callback_delay
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read \Motor\Media\Models\FileAssociation $file_association
 * @property-read \Partymeister\Slides\Models\Slide|null $slide
 * @property-read \Partymeister\Slides\Models\Transition|null $transition
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereCallbackDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereCallbackHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereIsAdvancedManually($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereIsMuted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereMidiNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereSlideId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereSlideType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereSortPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereTransitionDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereTransitionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\PlaylistItem whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PlaylistItem extends Model
{

    use Searchable;
    use Filterable;
    use Blameable, CreatedBy, UpdatedBy, DeletedBy;

    /**
     * Columns for the Blameable trait
     *
     * @var array
     */
    protected $blameable = [ 'created', 'updated', 'deleted' ];

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'playlist_id',
        'type',
        'slide_type',
        'slide_id',
        'duration',
        'transition_id',
        'transition_duration',
        'is_advanced_manually',
        'is_muted',
        'midi_note',
        'metadata',
        'callback_hash',
        'callback_delay',
        'sort_position',
    ];


    function transition()
    {
        return $this->belongsTo(Transition::class);
    }


    function file_association()
    {
        return $this->morphOne(FileAssociation::class, 'model');
    }


    function slide()
    {
        return $this->belongsTo(Slide::class);
    }
}
