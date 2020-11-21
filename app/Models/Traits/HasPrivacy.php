<?php

namespace App\Models\Traits;

trait HasPrivacy {

    public function setPrivacyAttribute($condition)
    {
        if (is_int($condition) && array_key_exists($condition, PRIVACY)) {
            $c = PRIVACY[$condition];
        } else {
            $c = strtolower($condition);
        }

        if (array_search($c, PRIVACY) === FALSE) {
            throw new \InvalidArgumentException("$condition is not a privacy condition");
        } else if (array_search($c, $this->supportedLevels()) === FALSE) {
            throw new \InvalidArgumentException(get_class($this) . ' cannot be ' . $condition);
        } else {
            $this->attributes['privacy'] = $c;
        }
    }

    public function getUserCanViewAttribute()
    {
        return $this->checkPrivacy();
    }

    public function isPrivate()
    {
        return $this->privacyLevel() === PRIVACY_PRIVATE;
    }

    public function isPublic()
    {
        return $this->privacyLevel() === PRIVACY_PUBLIC;
    }

    public function isPrivacy($condition)
    {
        if (array_search($condition, PRIVACY)) {
            return $this->privacy === $condition;
        } else {
            return false;
        }
    }

    public function getPrivacyConditions()
    {
        return PRIVACY;
    }

    public function privacyLevel($condition = null)
    {
        //Return array key or FALSE == 0 == public
        return array_search($condition ?: $this->privacy, PRIVACY);
    }

    public abstract function checkPrivacy();

    public abstract function supportedLevels();
}
