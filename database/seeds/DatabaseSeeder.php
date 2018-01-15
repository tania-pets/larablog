<?php

use Illuminate\Database\Seeder;
use App\User;
use App\PostCategory;
use App\Post;
use App\Tag;
use App\PostTag;

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
        $this->call(TagsSeeder::class);
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
        $categories = ['Tech', 'Politics', 'Sports', 'Travel', 'Entertainment'];
        foreach($categories as $name) {
            factory(PostCategory::class, 1)->create(['name' => $name]);
        }
    }
}


class TagsSeeder extends Seeder
{
    /**
     * Run the tags seeds.
     * @return void
     */
    public function run()
    {
        PostTag::truncate();
        Tag::truncate();
        $tags = ['europe', 'money', 'music', 'events', 'media', 'news'];
        foreach($tags as $tag) {
            factory(Tag::class, 1)->create(['tag' => $tag]);
        }
    }
}

class PostsSeeder extends Seeder
{
    /**
     * Run the posts seeds.
     * @return void
     */
    public function run()
    {
        Post::truncate();
        factory(Post::class, 10)->create(); //create random posts

        //attach Tags to posts
        $posts = Post::all();
        foreach ($posts as $post) {
            $tags = Tag::where('id', '>', rand(0, 6))->take(rand(0,3))->get();
            $post->tags()->saveMany($tags);
        }
    }
}
