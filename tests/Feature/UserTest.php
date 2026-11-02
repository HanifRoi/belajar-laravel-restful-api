<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;
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

    public function testGetSuccess()
    {
        $this->seed(UserSeeder::class);
        $this->get('api/users/current', [
            'Authorization'=> 'test'
        ])->assertStatus(200)
        ->assertJson([
            "data"=>[
                "username"=>'test',
                "name"=>'test'
            ]
        ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed(UserSeeder::class);
        $this->get('api/users/current', [
            'Authorization'=> ''
        ])->assertStatus(401)
        ->assertJson([
            "errors"=>[
                "message"=> [
                    'unauthorized'
                ]
            ]
        ]);
    }

    public function testGetInvalidToken()
    {
        $this->seed(UserSeeder::class);
        $this->get('api/users/current', [
            'Authorization'=> 'salah'
        ])->assertStatus(401)
        ->assertJson([
            "errors"=>[
                "message"=> [
                    'unauthorized'
                ]
            ]
        ]);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed(UserSeeder::class);
        $oldUser = User::where('username', 'test')->first();
        $this->withoutExceptionHandling()->patch('api/users/current', [
            'name'=> 'Hanif'
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(200)
        ->assertJson([
            "data"=>[
                "username"=>'test',
                "name"=>'Hanif'
            ]
        ]);
        $newUser = User::where('username', 'test')->first();

        assertNotEquals($oldUser->name, $newUser->name);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed(UserSeeder::class);
        $oldUser = User::where('username', 'test')->first();
        $this->withoutExceptionHandling()->patch('api/users/current', [
            'password'=> 'baru'
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(200)
        ->assertJson([
            "data"=>[
                "username"=>'test',
                "name"=>'test'
            ]
        ]);
        $newUser = User::where('username', 'test')->first();

        assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateFailed()
    {
        $this->seed(UserSeeder::class);
       
        $this->withoutExceptionHandling()->patch('api/users/current', [
            'name'=> 'HanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanifHanif'
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(400)
        ->assertJson([
            "errors"=>[
                "name"=>[
                    "The name field must not be greater than 100 characters."
                ]
            ]
        ]);
        
    }
}
