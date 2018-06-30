<?php

namespace App\Models;

use App\Providers\LegacyUserProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected const ACCESS_GRANTED = 1;
    protected const ACTIVE_ACCOUNT = 1;
    protected const VERIFIED_EMAIL = 1;

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $connection = 'users';

    /** @var string */
    protected $primaryKey = 'UserID';

    /** @var string */
    protected $table = 'Users';

    /** @var array */
    protected $hidden = ['UserHash', 'UserPassword'];

    /** @var LegacyUserProvider */
    protected $provider;

    protected static function boot()
    {
        parent::boot();
        parent::addGlobalScope('havingAccess', function (Builder $builder) {
            $builder->where('Status', self::ACTIVE_ACCOUNT)
                ->where('IsVerified', self::VERIFIED_EMAIL)
                ->where('PersonalDataAccess', self::ACCESS_GRANTED);
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->provider = app(LegacyUserProvider::class);
    }

    /**
     * @return string|null
     */
    public function getAuthPassword()
    {
        return $this->UserHash;
    }

    /**
     * @param  string  $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        return $this->provider->retrieveByCredentials(['UserLogin' => $username]);
    }

    /**
     * @param  string  $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return $this->provider->validateCredentials($this, compact('password'));
    }
    
    public function sites(): Collection
    {
        return $this->userSites->isNotEmpty() ? $this->userSites : $this->clientSites;
    }
    
    public function isAdmin(): bool
    {
        return $this->ClientID === config('auth.legacy.super_user_id');
    }
    
    public function availableSites(): array
    {
        $minutes = config('auth.passport.token_expiration.access')->diffInMinutes();

        return Cache::remember('user.sites.' . $this->getKey(), $minutes, function () {
            $sites = ! $this->isAdmin() ? $this->sites() : new Site;

            return $sites->pluck('SiteID')->toArray();
        });
    }
    
    protected function clientSites(): HasManyThrough
    {
        return $this->hasManyThrough(Site::class, ClientSite::class, 'ClientID', 'SiteID', 'ClientID', 'SiteID');
    }
    
    protected function userSites(): HasManyThrough
    {
        return $this->hasManyThrough(Site::class, UserSite::class, 'UserID', 'SiteID', 'UserID');
    }
}
