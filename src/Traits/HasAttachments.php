<?php

namespace Clip\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasAttachments
{
    /**
     * @return MorphMany
     */
    public function hasManyAttachments()
    {
        // code...
    }

    /**
     * @return MorphOne
     */
    public function hasOneAttachment()
    {
        # code...
    }
}
