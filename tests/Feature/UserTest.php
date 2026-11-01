<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('api/users',[
            'username'=> 'roihan',
            'password'=>'12345678',
            'name'=>'Hanif Roihan'
        ])->assertStatus(201)
        ->assertJson([
            "data"=>[
                'username'=>'roihan',
                'name'=>'Hanif Roihan'
            ]
        ]);
    }

    public function testRegisterFailed()
    {
        $this->post('api/users',[
            'username'=> '',
            'password'=>'',
            'name'=>''
        ])->assertStatus(400)
        ->assertJson([
            "errors"=>[
                'username'=>[
                    'The username field is required.'
                ],
                'password'=>[
                    'The password field is required.'
                ],
                'name'=>[
                    'The name field is required.'
                ]
            ]
        ]);
    }

    public function testRegisterUsernameAlready()
    {
        $this->testRegisterSuccess();

        $this->post('api/users',[
            'username'=> 'roihan',
            'password'=>'12345678',
            'name'=>'Hanif Roihan'
        ])->assertStatus(400)
        ->assertJson([
            "errors"=>[
                'username'=>
                    ['Username already registered']
                
            ]
        ]);
    }

    public function testLoginSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/users/login', [
            'username'=>'test',
            'password'=>'testtesttest'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                'username'=>'test',
            ]
        ]);

        $user = User::where('username', 'test')->first();
        assertNotNull($user->token);
    }

    public function testLoginFailedNotFound()
    {
        $this->post('/api/users/login', [
            'username'=>'test',
            'password'=>'testtesttest'
        ])
        ->assertStatus(401)
        ->assertJson([
            'errors'=>[
                'message'=>['Username or password wrong'],
            ]
        ]);
    }

    public function testLoginFailedPasswordWrong()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/users/login', [
            'username'=>'test',
            'password'=>'beda'
        ])
        ->assertStatus(401)
        ->assertJson([
            'errors'=>[
                'message'=>['Username or password wrong'],
            ]
        ]);
    }
}
