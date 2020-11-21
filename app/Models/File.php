<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model {

    use SoftDeletes;

    const RECEIPTS_DIR = 'public' . DS . 'receipts' . DS;
    const IMAGE_DIR = 'public' . DS . 'photos' . DS;
    const IDENTITY_IMAGE_DIR = 'public' . DS . 'ids' . DS;
    const QR_IMAGE_DIR = 'public' . DS . 'qr' . DS;

    protected $fillable = ['path', 'request_id', 'request_type'];

    /**
     * @return MorphTo
     */
    public function fileable()
    {
        return $this->morphTo('fileable');
    }

    public function forceDelete()
    {
        Storage::delete($this->path);
        parent::forceDelete();
    }

    public function getURL()
    {
        if (empty($this->path)) {
            return asset('images/default/notfound.jpg');
        } else if (starts_with($this->path, 'http')) {
            return $this->path;
        } else {
//            return Storage::url($this->path);
            return normalizeUrl(asset(str_replace('public', 'storage', $this->path)));
        }
    }

}
