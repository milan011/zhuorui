<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        $this->call('TclUserTableSeeder');
        $this->call('IndustriesTableSeeder');
        $this->call('DepartmentsTableSeeder');
        $this->call('SettingsTableSeeder');
        $this->call('RoleUserTableSeeder');
        $this->call('RolesTablesSeeder');
        $this->call('PermissionsTableSeeder');
        $this->call('UserRoleTableSeeder');
        $this->call('NotificationCategoriesTableSeeder');
        /*\DB::table('tcl_user')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'wcg',
                'nick_name' => 'wxm',
                'password' => bcrypt('111111'),
                'telephone' => '13731080174',
                'phone' => '13731080174',
                'qq_number' => '316470314',
                'wx_number' => 'cg316470314',
                'email' => 'milan011@sina.com',               
                'user_img' => 'wxm.png',
                'address' => '石家庄',
                'creater_id' => 1,
                'shop_id' => 1,
                'status' => '1',
                'remember_token' => null,
                'created_at' => '2016-09-20 23:21:19',
                'updated_at' => '2016-09-20 23:21:19',
            ),
        ));*/
    }
}

//class TclUserTableSeeder extends Seeder
//{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /*public function run()
        {
        \DB::table('tcl_user')->delete();
        
        \DB::table('tcl_user')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'wcg',
                'nick_name' => 'wxm',
                'password' => bcrypt('111111'),
                'telephone' => '13731080174',
                'phone' => '13731080174',
                'qq_number' => '316470314',
                'wx_number' => 'cg316470314',
                'email' => 'milan011@sina.com',               
                'user_img' => 'wxm.png',
                'address' => '石家庄',
                'creater_id' => 1,
                'shop_id' => 1,
                'status' => '1',
                'remember_token' => null,
                'created_at' => '2016-09-20 23:21:19',
                'updated_at' => '2016-09-20 23:21:19',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'wm',
                'nick_name' => 'wxm',
                'password' => bcrypt('111111'),
                'telephone' => '13731080174',
                'phone' => '13731080174',
                'qq_number' => '316470314',
                'wx_number' => 'cg316470314',
                'email' => 'milan011@sohu.com',               
                'user_img' => 'wxm.png',
                'address' => '石家庄',
                'creater_id' => 1,
                'shop_id' => 1,
                'status' => '1',
                'remember_token' => null,
                'created_at' => '2016-09-20 23:21:19',
                'updated_at' => '2016-09-20 23:21:19',
            ),
        ));
    }*/
//}
