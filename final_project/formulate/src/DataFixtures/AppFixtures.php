<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Comment;
use App\Entity\FormSubmit;
use App\Entity\Like;
use App\Entity\Option;
use App\Entity\Question;
use App\Entity\Template;
use App\Entity\TemplateTag;
use App\Entity\User;

use Faker\Factory;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // 1) Create users
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email);
            // In a real app encode this; for fixtures we use plain
            $user->setPassword('password');
            // First user is admin
            $user->setRole($i === 0 ? 'ROLE_ADMIN' : 'ROLE_USER');
            $user->setCreatedAt($faker->dateTimeBetween('-1 year', 'now'));
            $manager->persist($user);
            $users[] = $user;
        }

        // 2) Create templates, tags, questions, options, submissions, comments, likes
        $possibleTopics = ['Survey','Quiz','Poll','Feedback','Registration'];
        foreach ($users as $user) {
            for ($t = 0; $t < 3; $t++) {
                // Template
                $template = new Template();
                $template->setUser($user);
                // *** SET A NON-NULL TOPIC HERE ***
                $template->setTopic(
                    $faker->randomElement($possibleTopics)
                );
                $template->setTitle($faker->sentence(3));
                $template->setDescription($faker->paragraph());
                $template->setImage($faker->imageUrl(600, 400));
                $template->setIsPublic($faker->boolean(80));
                $template->setVersion(1);
                $template->setCreatedAt($faker->dateTimeBetween('-6 months', 'now'));
                $template->setLastUpdatedAt($faker->dateTimeBetween('-6 months', 'now'));
                $manager->persist($template);

                // Tags
                for ($j = 0; $j < 3; $j++) {
                    $tag = new TemplateTag();
                    $tag->setTemplate($template);
                    $tag->setTag($faker->word);
                    $manager->persist($tag);
                }

                // Questions
                $questions = [];
                $types = ['string', 'text', 'integer', 'checkbox', 'radio'];
                for ($q = 1; $q <= 5; $q++) {
                    $question = new Question();
                    $question->setTemplate($template);
                    $type = $faker->randomElement($types);
                    $question->setType($type);
                    $question->setTitle($faker->sentence(4));
                    $question->setDescription($faker->sentence());
                    $question->setShowInResults($faker->boolean(50));
                    $question->setPosition($q);
                    $manager->persist($question);
                    $questions[] = $question;

                    // Options for choice questions
                    if (in_array($type, ['checkbox', 'radio'])) {
                        for ($o = 1; $o <= 3; $o++) {
                            $opt = new Option();
                            $opt->setQuestion($question);
                            $opt->setText(ucfirst($faker->word));
                            $opt->setPosition($o);
                            $manager->persist($opt);
                        }
                    }
                }

                // Form submissions & Answers
                $submitCount = $faker->numberBetween(1, 5);
                for ($s = 0; $s < $submitCount; $s++) {
                    $submit = new FormSubmit();
                    $submit->setTemplate($template);
                    $submit->setUser($faker->randomElement($users));
                    $submit->setCreatedAt($faker->dateTimeBetween(
                        $template->getCreatedAt(), 'now'
                    ));
                    $manager->persist($submit);

                    foreach ($questions as $question) {
                        // Handle each question by type
                        if (in_array($question->getType(), ['string', 'text'])) {
                            $ans = new Answer();
                            $ans->setFormSubmit($submit);
                            $ans->setQuestion($question);
                            $ans->setAnswerText($faker->sentence());
                            $manager->persist($ans);

                        } elseif ($question->getType() === 'integer') {
                            $ans = new Answer();
                            $ans->setFormSubmit($submit);
                            $ans->setQuestion($question);
                            $ans->setAnswerText((string)$faker->numberBetween(0, 10));
                            $manager->persist($ans);

                        } else {
                            // Fetch options
                            $opts = $question->getOptions()->toArray();
                            if (count($opts) === 0) {
                                continue;
                            }

                            if ($question->getType() === 'radio') {
                                // Single choice
                                $chosen = $faker->randomElement($opts);
                                $ans = new Answer();
                                $ans->setFormSubmit($submit);
                                $ans->setQuestion($question);
                                $ans->setChoosenOption($chosen);
                                $manager->persist($ans);

                            } else {
                                // Checkbox: multiple selected
                                $pickCount = $faker->numberBetween(1, count($opts));
                                $chosenOpts = $faker->randomElements($opts, $pickCount);
                                foreach ($chosenOpts as $opt) {
                                    $ans = new Answer();
                                    $ans->setFormSubmit($submit);
                                    $ans->setQuestion($question);
                                    $ans->setChoosenOption($opt);
                                    $manager->persist($ans);
                                }
                            }
                        }
                    }
                }

                // Comments
                for ($c = 0; $c < 2; $c++) {
                    $comment = new Comment();
                    $comment->setTemplate($template);
                    $comment->setUser($faker->randomElement($users));
                    $comment->setContent($faker->sentence());
                    $comment->setCreatedAt($faker->dateTimeBetween(
                        $template->getCreatedAt(), 'now'
                    ));
                    $manager->persist($comment);
                }

                // Likes
                for ($l = 0; $l < 2; $l++) {
                    $like = new Like();
                    $like->setTemplate($template);
                    $like->setUser($faker->randomElement($users));
                    $manager->persist($like);
                }
            }
        }

        // Flush everything to the database
        $manager->flush();

    }
}
