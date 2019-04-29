<?php

namespace Partymeister\Slides\Models;

use Illuminate\Database\Eloquent\Model;
use Motor\Backend\Models\Category;
use Motor\Core\Traits\Searchable;
use Motor\Core\Traits\Filterable;
use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;
use Spatie\Image\Exceptions\InvalidManipulation;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Partymeister\Slides\Models\Slide
 *
 * @property int $id
 * @property int|null $slide_template_id
 * @property string $name
 * @property string $slide_type
 * @property int|null $category_id
 * @property mixed $definitions
 * @property string $cached_html_final
 * @property string $cached_html_preview
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property-read \Motor\Backend\Models\Category|null $category
 * @property-read \Motor\Backend\Models\User $creator
 * @property-read \Motor\Backend\Models\User|null $eraser
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \Partymeister\Slides\Models\SlideTemplate $template
 * @property-read \Motor\Backend\Models\User $updater
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide filteredBy(\Motor\Core\Filter\Filter $filter, $column)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide filteredByMultiple(\Motor\Core\Filter\Filter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide search($q, $full_text = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereCachedHtmlFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereCachedHtmlPreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereDefinitions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereSlideTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereSlideType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Partymeister\Slides\Models\Slide whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Slide extends Model implements HasMedia
{

    use Searchable;
    use Filterable;
    use Blameable, CreatedBy, UpdatedBy, DeletedBy;
    use HasMediaTrait;

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
        'slides.name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slide_template_id',
        'slide_type',
        'category_id',
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

    public function registerMediaConversions(Media $media = null)
    {
        try {
            $this->addMediaConversion('thumb')->width(400)->height(400)->nonQueued();
            $this->addMediaConversion('preview')->width(400)->height(400)->format('png')->nonQueued();
        } catch (InvalidManipulation $e) {
            Log::error($e->getMessage());
        }
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function template()
    {
        return $this->belongsTo(SlideTemplate::class);
    }
}
