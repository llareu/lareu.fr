<?php

declare(strict_types=1);

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class BookmarkManagerSeeder extends AbstractSeed
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
            $nameFaker = $faker->domainWord();

            $data[] = [
                'title' => $nameFaker,
                'user_uuid' => $faker->uuid(),
                'created_at' => date('Y-m-d H:i:s', $date),
                'updated_at' => date('Y-m-d H:i:s', $date)
            ];
        }
        $this->table('bm_category')->insert($data)->save();

        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $date = $faker->unixTime('now');
            $nameFaker = $faker->domainWord();

            $data[] = [
                'title' => $nameFaker,
                'link' => $faker->url(),
                'category_id' => rand(1, 5),
                'picture_title' => $nameFaker.'.png',
                'click_counter' => rand(5, 1000),
                'user_uuid' => $faker->uuid(),
                'created_at' => date('Y-m-d H:i:s', $date),
                'updated_at' => date('Y-m-d H:i:s', $date)
            ];
        }
        $this->table('bm_bookmark')->insert($data)->save();
    }
}
