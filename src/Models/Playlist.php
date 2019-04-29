<?php

namespace Partymeister\Slides\Models;

use Illuminate\Database\Eloquent\Model;
use Motor\Core\Traits\Searchable;
use Motor\Core\Traits\Filterable;
use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;

/**
 * Partymeister\Slides\Models\Playlist
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $is_competition
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read mixed $item_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Partymeister\Slides\Models\PlaylistItem[] $items
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereIsCompetition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Playlist whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Playlist extends Model
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
        'name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'is_competition',
    ];

    public function getItemCountAttribute()
    {
        return $this->items()->count();
    }

    public function items()
    {
        return $this->hasMany(PlaylistItem::class);
    }
}
