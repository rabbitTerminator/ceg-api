<?php
    /**
     * Created by PhpStorm.
     * User: valentindaitkhe
     * Date: 25/10/2019
     * Time: 23:58
     */

    class UsersTableSeeder extends \Illuminate\Database\Seeder
    {
           public function  run(){
               factory(App\User::class,10)->connection('mysql')->create();
           }
    }
