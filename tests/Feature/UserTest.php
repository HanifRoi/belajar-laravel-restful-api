<?php

namespace Tests\Feature;


use Tests\TestCase;

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
}
