<?php

namespace Partymeister\Slides\Models;

use Illuminate\Database\Eloquent\Model;
use Motor\Core\Traits\Searchable;
use Motor\Core\Traits\Filterable;
use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Partymeister\Slides\Models\SlideTemplate
 *
 * @property int $id
 * @property string $name
 * @property string $template_for
 * @property mixed $definitions
 * @property string $cached_html_final
 * @property string $cached_html_preview
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\Partymeister\Slides\Models\Slide[] $slides
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereCachedHtmlFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereCachedHtmlPreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereDefinitions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereTemplateFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\SlideTemplate whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class SlideTemplate extends Model implements HasMedia
{

    use Searchable;
    use Filterable;
    use Blameable, CreatedBy, UpdatedBy, DeletedBy;
    use HasMediaTrait;


    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(400)->height(400)->nonQueued();
        $this->addMediaConversion('preview')->width(400)->height(400)->format('png')->nonQueued();
    }


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
        'name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'template_for',
        'definitions',
        'cached_html_preview',
        'cached_html_final',
    ];

    ///**
    // * The attributes that should be cast to native types.
    // *
    // * @var array
    // */
    //protected $casts = [
    //    'definitions' => 'array',
    //];

    public function slides()
    {
        return $this->hasMany(Slide::class);
    }

}
