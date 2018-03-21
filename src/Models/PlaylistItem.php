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
    protected $blameable = array('created', 'updated', 'deleted');

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
    ];

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
    ];

    function transition() {
        return $this->belongsTo(Transition::class);
    }

    function file_association() {
        return $this->morphOne(FileAssociation::class, 'model');
    }

    function slide() {
        return $this->belongsTo(Slide::class);
    }
}
