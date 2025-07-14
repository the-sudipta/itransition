<?php
// src/Service/SalesforceClientService.php
namespace App\Service;

use App\Entity\Answer;
use App\Entity\Comment;
use App\Entity\FormSubmit;
use App\Entity\Like;
use App\Entity\SalesforceAccount;
use App\Entity\Template;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SalesforceClientService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Take the token payload from Salesforce, link it to $user,
     * and write (or update) your SalesforceAccount row.
     *
     * @return SalesforceAccount
     */
    public function persistAuthData(array $data, User $user): SalesforceAccount
    {
        $repo = $this->em->getRepository(SalesforceAccount::class);

        // 1) find existing or new
        $acct = $repo->findOneBy(['user' => $user]) ?: new SalesforceAccount();

        // 2) Convert Salesforce’s issued_at (ms → s)
        $issuedAtTs = (int) ($data['issued_at'] / 1000);

        // 3) Build issuedAt in PHP’s default timezone, then +1h
        $issuedAt  = (new \DateTime())->setTimestamp($issuedAtTs);
        $expiresAt = $issuedAt->add(new \DateInterval('PT1H'));

        // 4) Persist
        $acct
            ->setUser($user)
            ->setAccessToken($data['access_token'])
            ->setRefreshToken($data['refresh_token'])
            ->setInstanceUrl($data['instance_url'])
            ->setIssuedAt($issuedAt)
            ->setExpiresAt($expiresAt)
        ;

        $this->em->persist($acct);
        $this->em->flush();

        return $acct;
    }



//    ###############################################################################################################################
//    ######################################### SYNC DATA IN THE SALESFORCE ACCOUNT #################################################
//    ###############################################################################################################################



    public function prepareAllPayloads(): array
    {
        return [
            'Form_Template__c' => $this->mapEntities(
                $this->em->getRepository(Template::class)->findAll(),
                'Template'
            ),
            'Form_Comment__c'  => $this->mapEntities(
                $this->em->getRepository(Comment::class)->findAll(),
                'Comment'
            ),
            'Form_Like__c'     => $this->mapEntities(
                $this->em->getRepository(Like::class)->findAll(),
                'Like'
            ),
        ];
    }

    private function mapEntities(array $entities, string $type): array
    {
        $sObject = "Form_{$type}__c";
        $method  = "map{$type}";
        $out     = [];

        foreach ($entities as $e) {
            $record = $this->$method($e);
            $record['attributes'] = [
                'type'        => $sObject,
                'referenceId' => "{$type}-{$e->getId()}",
            ];
            $out[] = $record;
        }

        return $out;
    }

    private function mapTemplate(Template $t): array
    {
        return [
            'Original_ID__c'    => $t->getId(),
            'Title__c'          => $t->getTitle(),
            'Topic__c'          => $t->getTopic(),
            'Description__c'    => $t->getDescription(),
            'Image_URL__c'      => $t->getImage(),
            'Is_Public__c'      => (bool)$t->isPublic(),
            'Version__c'        => $t->getVersion(),
            'User_ID__c'        => $t->getUser()->getId(),
            'Created_At__c'     => $t->getCreatedAt()->format(\DateTime::ATOM),
            'Last_Updated_At__c'=> $t->getLastUpdatedAt()->format(\DateTime::ATOM),
        ];
    }

    private function mapComment(Comment $c): array
    {
        return [
            'Original_ID__c'  => $c->getId(),
            'Content__c'      => $c->getContent(),
            'Created_At__c'   => $c->getCreatedAt()->format(\DateTime::ATOM),
            'User_ID__c'      => $c->getUser()->getId(),
            'Template__r'     => ['Original_ID__c' => $c->getTemplate()->getId()],
        ];
    }

    private function mapLike(Like $l): array
    {
        return [
            'Original_ID__c'  => $l->getId(),
            'User_ID__c'      => $l->getUser()->getId(),
            'Template__r'     => ['Original_ID__c' => $l->getTemplate()->getId()],
        ];
    }

}
