<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use DateTime;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Créer 3 cat fakées
        for($i =1; $i <=3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Créer entre 4 et 6 articles
            for($j = 1; $j <= mt_rand(4, 6); $j++) {
                $article = new Article();

                // entre 2 et 6 paragraphe / article
                $content = '<p>' . join($faker->paragraphs(mt_rand(2, 6))) . '</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl($width = 350, $height= 150))
                        ->setCreateAt($faker->datetimeBetween('-6 months'))
                        ->setCategory($category);
            
                $manager->persist($article);

                // On donne des commentaires à l'article
                for($k = 1; $k<= mt_rand(4, 10); $k++) {
                    $comment = new Comment;

                    // entre 1 et 3 commentaires/ article
                    $content = '<p>' . join($faker->paragraphs(mt_rand(1, 3))) . '</p>';

                    $days = (new DateTime())->diff($article->getCreateAt())->days;

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreateAt($faker->dateTimeBetween('-' . $days . 'days'))
                            ->setArticle($article);
                        
                        $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
