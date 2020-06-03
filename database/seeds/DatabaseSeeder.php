<?php

use App\User;
use App\Account;
use App\Contact;
use App\Organization;
use Illuminate\Database\Seeder;
use Mockery\Exception;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->roleSeeder();

        $account = Account::create(['name' => 'Centria']);

        $user = User::create([
            'account_id' => $account->id,
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'owner' => true,
        ]);

        $user->assignRole('owner');
        factory(User::class, 5)->create(['account_id' => $account->id]);
    }

    public function roleSeeder(){
        try {
            $roles = [
                'owner' => ['permissions' => [
                    'categories.*',
                ]],
                'user' => ['permissions' => []]
            ];
            foreach ($roles as $key => $item){

                $role = Role::where('name',$key)->first();
                if(is_null($role)){
                    $role = Role::create(['name' => $key]);
                }
                foreach ($item['permissions'] as $permission){
                    if(is_null(Permission::where('name',$permission)->first())) {
                        Permission::create(['name' => $permission]);
                    }
                    $role->givePermissionTo($permission);
                }
            }
        } catch (Exception $e){}
    }
}
