<?php

namespace Tests\Unit\Core\Repositories;

use App\Core\Repositories\InMemoryRepository;
use App\Users\Models\User;
use Tests\Unit\TestCase;

class InMemoryRepositoryTest extends TestCase
{

    /**
     * @return void
     */
    public function testGetBy(): void
    {
        $repository = new InMemoryRepository([
            new User([
                'login' => 'A',
                'name' => 'First',
            ]),

            new User([
                'login' => 'B',
                'name' => 'Second',
            ]),

            new User([
                'login' => 'C',
                'name' => 'Third',
            ]),
        ]);

        $userB = $repository->getBy('name', 'Second');

        // Assertion: make sure user was found
        $this->assertNotNull($userB);

        // Assertion: make sure it was that precise user we are looking for
        $this->assertEquals(
            'B', $userB->getAttribute('login')
        );

        $userD = $repository->getBy('name', 'Fourth');

        // Assertion: make sure repository returns `null` for non-existing
        // models
        $this->assertNull($userD);

        $userB->setAttribute('login', 'New login');
    }

    /**
     * @return void
     */
    public function testGetByReturnsClones(): void
    {
        $repository = new InMemoryRepository([
            new User([
                'name' => 'First',
            ]),
        ]);

        $user1 = $repository->getBy('name', 'First');
        $user2 = $repository->getBy('name', 'First');

        $user1->setAttribute('name', 'First - modified');

        $this->assertEquals(
            'First', $user2->getAttribute('name')
        );
    }

    /**
     * @return void
     */
    public function testPersist(): void
    {
        $repository = new InMemoryRepository();

        $userA = new User([
            'login' => 'A',
        ]);

        $userB = new User([
            'login' => 'B',
        ]);

        $repository->persist($userA);

        // Assertion: make sure user A has been assigned an identifier
        $this->assertNotNull(
            $userA->getAttribute('id')
        );

        $repository->persist($userB);

        // Assertion: make sure user B has been assigned an identifier
        $this->assertNotNull(
            $userB->getAttribute('id')
        );

        // Assertion: make sure both identifiers are unique
        $this->assertNotEquals(
            $userA->getAttribute('id'), $userB->getAttribute('id')
        );

        $currentUserAId = $userA->getAttribute('id');

        $repository->persist($userA);

        // Assertion: make sure updating user's id does not change when we're
        // only updating user
        $this->assertEquals(
            $currentUserAId, $userA->getAttribute('id')
        );
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        $repository = new InMemoryRepository([
            new User([
                'login' => 'A',
                'name' => 'First',
            ]),

            new User([
                'login' => 'B',
                'name' => 'Second',
            ]),
        ]);

        $repository->delete(
            $repository->getBy('name', 'First')
        );

        $users = $repository->getAll();

        // Assertion: make sure we are left with only one user
        $this->assertCount(1, $users);

        // Assertion: make sure the left user is the correct one
        $this->assertNotNull(
            $repository->getBy('name', 'Second')
        );
    }

}