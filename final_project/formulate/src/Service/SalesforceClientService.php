<?php
// src/Service/SalesforceClientService.php
namespace App\Service;

use App\Entity\SalesforceAccount;
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

        // 1. See if we already have a record for this user
        $acct = $repo->findOneBy(['user' => $user])
            ?: new SalesforceAccount();

        // 2. Figure out issuedAt
        $issuedAt = \DateTimeImmutable::createFromFormat(
            'U',
            (string) ((int) ($data['issued_at'] / 1000))
        );

        // 3. Determine TTL: use Salesforce’s expires_in if provided,
        //    otherwise default to 2 hours (7200 seconds).
        $ttlSeconds = isset($data['expires_in'])
            ? (int) $data['expires_in']
            : 7200;

        $expiresAt = $issuedAt->add(new \DateInterval('PT' . $ttlSeconds . 'S'));

        // 4. Persist everything
        $acct
            ->setUser($user)
            ->setAccessToken($data['access_token'])
            ->setRefreshToken($data['refresh_token'])
            ->setInstanceUrl($data['instance_url'])
            ->setIssuedAt(new \DateTime('@' . ((int) ($data['issued_at'] / 1000))))
            ->setExpiresAt(\DateTime::createFromFormat('U', (string) $expiresAt->getTimestamp()))
        ;

        $this->em->persist($acct);
        $this->em->flush();

        return $acct;
    }






}
