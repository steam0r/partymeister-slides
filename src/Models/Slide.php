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
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Slide extends Model implements HasMediaConversions
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
