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
 * Partymeister\Slides\Models\SlideClient
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $ip_address
 * @property string $port
 * @property int $sort_position
 * @property int|null $playlist_id
 * @property int|null $playlist_item_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient wherePlaylistItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereSortPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideClient whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class SlideClient extends Model
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
    protected $searchableColumns = [
        'name',
        'ip_address'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'ip_address',
        'port',
        'sort_position',
        'playlist_id',
        'playlist_item_id',
    ];
}
