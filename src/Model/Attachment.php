<?php

namespace Clip\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'file_name', 'file_size', 'file_content_type', 'file_path'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->forceDeleting = !Config::get('clip.soft_deletes', false);
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachables()
    {
        return $this->morphTo('attachable');
    }
}
