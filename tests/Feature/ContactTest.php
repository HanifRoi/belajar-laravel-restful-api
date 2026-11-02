<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->post('api/contacts', [
            'first_name'=>'Hanif',
            'last_name'=>'Roihan',
            'email'=>'hanif@gans',
            'phone'=>'230482084'
        ],[
            'Authorization'=>'test'
        ])->assertStatus(201)->assertJson([
            'data'=>[
                'first_name'=>'Hanif',
                'last_name'=>'Roihan',
                'email'=>'hanif@gans',
                'phone'=>'230482084'
            ]
        ]);
    }

    public function testCreateFailed()
    {
        $this->seed(UserSeeder::class);

        $this->post('api/contacts', [
            'first_name'=>'',
            'last_name'=>'Roihan',
            'email'=>'hanifgans',
            'phone'=>'230482084'
        ],[
            'Authorization'=>'test'
        ])->assertStatus(400)->assertJson([
            'errors'=>[
                'first_name'=>['The first name field is required.'],
                'email'=>['The email field must be a valid email address.']
                
            ]
        ]);
    }

    public function testCreateFailedUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $this->post('api/contacts', [
            'first_name'=>'',
            'last_name'=>'Roihan',
            'email'=>'hanifgans',
            'phone'=>'230482084'
        ],[
            'Authorization'=>'salah'
        ])->assertStatus(401)->assertJson([
            'errors'=>[
                'message'=>['unauthorized'],
            ]
        ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::limit(1)->first();

        $this->withoutExceptionHandling()->get('/api/contacts/'. $contact->id, [
            'Authorization'=>'test'
        ])->assertStatus(200)
        ->assertJson([
            'data'=>[
                'first_name'=> 'test',
                'last_name'=> 'test',
                'email'=>'test@email',
                'phone'=>'123456'
            ]
        ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::limit(1)->first();

        $this->withoutExceptionHandling()->get('/api/contacts/'. ($contact->id + 1), [
            'Authorization'=>'test'
        ])->assertStatus(404)
        ->assertJson([
            'errors'=>[
                'message'=> ['not found'],
            ]
        ]);
    }
    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::limit(1)->first();

        $this->withoutExceptionHandling()->get('/api/contacts/'. $contact->id, [
            'Authorization'=>'test2'
        ])->assertStatus(404)
        ->assertJson([
            'errors'=>[
                'message'=> ['not found'],
            ]
        ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::limit(1)->first();
        $this->withoutExceptionHandling()->put('/api/contacts/'. $contact->id , [
            'first_name'=>'test2',
            'last_name'=>'test2',
            'email'=>'test2@mail',
            'phone'=>'22222222'
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(200)->assertJson([
            'data'=> [
                'first_name'=>'test2',
            'last_name'=>'test2',
            'email'=>'test2@mail',
            'phone'=>'22222222'
            ]
        ]);
    }

    public function testUpdateValidtaionError()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::limit(1)->first();
        $this->withoutExceptionHandling()->put('/api/contacts/'. $contact->id , [
            'first_name'=>'',
            'last_name'=>'test2',
            'email'=>'test2@mail',
            'phone'=>'22222222'
        ],
        [
            'Authorization' => 'test'
        ])->assertStatus(400)->assertJson([
            'errors'=> [
                'first_name'=>['The first name field is required.']
            ]
        ]);
    }
}
