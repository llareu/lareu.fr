<?php

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class NewsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [];
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $date = $faker->unixTime('now');

            $data[] = [
                'title' => $faker->catchPhrase,
                'slug' => $faker->slug
            ];
        }
        $this->table('bg_category')->insert($data)->save();

        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $date = $faker->unixTime('now');

            $data[] = [
                'title' => $faker->catchPhrase,
                'slug' => $faker->slug,
                'content' => $faker->text(3000),
                'category_id' => rand(1, 5),
                'created_at' => date('Y-m-d H:i:s', $date),
                'updated_at' => date('Y-m-d H:i:s', $date)
            ];
        }
        $this->table('bg_news')->insert($data)->save();
    }
}
