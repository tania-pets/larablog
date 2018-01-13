<?php

use Illuminate\Database\Seeder;
use App\User;
use App\PostCategory;
use App\Post;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints(); //in order to execute truncate before seeding

        $this->call(UsersTableSeeder::class);
        $this->call(PostCategoriesSeeder::class);
        $this->call(PostsSeeder::class);

        Schema::enableForeignKeyConstraints();


    }
}


class UsersTableSeeder extends Seeder
{
    /**
     * Run the user seeds.
     * @return void
     */
    public function run()
    {
        User::truncate();
        factory(User::class, 1)->create(); //create  user with random data
    }

}


class PostCategoriesSeeder extends Seeder
{
    /**
     * Run the post categories seeds.
     * @return void
     */
    public function run()
    {
        PostCategory::truncate();
        $categories = ['Tech', 'Politics', 'Sports', 'Travel'];
        foreach($categories as $name) {
            factory(PostCategory::class, 1)->create(['name' => $name]);
        }
    }
}


class PostsSeeder extends Seeder
{
    /**
     * Run the post categories seeds.
     * @return void
     */
    public function run()
    {
        Post::truncate();
        factory(Post::class, 10)->create(); //create  user with random data
    }
}
