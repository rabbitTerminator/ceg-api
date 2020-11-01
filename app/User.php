<?php

    namespace App;

    use Illuminate\Auth\Authenticatable;
    use Laravel\Lumen\Auth\Authorizable;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
    use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
    use Tymon\JWTAuth\Contracts\JWTSubject;

    class User extends Model implements AuthenticatableContract, AuthorizableContract,JWTSubject
    {
        use Authenticatable, Authorizable;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $table = 'users';

        protected $fillable = [
            'name', 'email','user_image','user_profile'
        ];
        // auto deserialize
        protected $casts = [
            'user_profile' => 'array',
        ];

        /**
         * The attributes excluded from the model's JSON form.
         *
         * @var array
         */
        protected $hidden = [
            'password',
        ];


        public function getJWTIdentifier()
        {
            return $this->getKey();
        }

        public function getJWTCustomClaims()
        {
            return [];
        }

        public function exportedCharts(){
            return $this->hasMany('App\ExportedCharts','user_id','id');
        }
        public function trackingSensors(){
            return $this->hasMany('App\TrackingSensors','user_id','id');
        }
    }
