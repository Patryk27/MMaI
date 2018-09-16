<?php

namespace Tests\Unit\Core\Repositories;

use App\Core\Repositories\InMemoryRepository;
use App\Users\Models\User;
use Tests\Unit\TestCase;

class InMemoryRepositoryTest extends TestCase
{

    /**
     * This test checks basic functionality of the "getBy()" method.
     *
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

        // Find user & make sure it was found
        $this->assertNotNull(
            $userB = $repository->getBy('name', 'Second')
        );

        // Make sure it was that precise user we are looking for
        $this->assertEquals('B', $userB->login);

        // Make sure repository returns `null` for non-existing
        $this->assertNull(
            $repository->getBy('name', 'Fourth')
        );
    }

    /**
     * This test makes sure that "getBy" returns clones, so that user cannot
     * "accidentally" overwrite repository without explicit "persist" call.
     *
     * @return void
     */
    public function testGetByReturnsClones(): void
    {
        $repository = new InMemoryRepository([
            new User([
                'login' => 'First',
            ]),
        ]);

        $user1 = $repository->getBy('login', 'First');
        $user2 = $repository->getBy('login', 'First');

        $user1->login = 'First - modified';

        // If repository hasn't returned a clone of $user1, both instances would
        // be modified right now - make sure that did not happen.
        $this->assertEquals('First', $user2->login);
    }

    /**
     * This test checks basic functionality of the "persist()" method.
     *
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
        $repository->persist($userB);

        // Repository should assign each new model a unique identifier - make
        // sure it happened
        $this->assertNotNull($userA->id);
        $this->assertNotNull($userB->id);
        $this->assertNotEquals($userA->id, $userB->id);

        // When saving an already existing model, its it should remain unchanged
        $userAId = $userA->id;

        $repository->persist($userA);

        $this->assertEquals($userAId, $userA->id);
    }

    /**
     * This test makes sure that mutating a model after persisting it does not
     * make it change in the in-memory repository.
     *
     * @return void
     */
    public function testPersistsSavesClones(): void
    {
        $repository = new InMemoryRepository([
            $user = new User([
                'login' => 'A',
            ])
        ]);

        // Mutate the model, but do not save the changes
        $user->login = 'B';

        // Make sure that repository still contains the "user A" model, not
        // "user B"
        $this->assertNotNull(
            $repository->getBy('login', 'A')
        );
    }

    /**
     * This test makes sure that the "persist()" method automatically fills-in
     * the "created at" and "updated at" properties.
     *
     * @return void
     */
    public function testPersistSetsTimestamps(): void
    {
        new InMemoryRepository([
            $user = new User([
                'login' => 'A',
            ]),
        ]);

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    /**
     * This test checks the basic functionality of the "delete()" method.
     *
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

        // Make sure we are left with only one user
        $this->assertCount(1, $users);

        // Make sure the left user is the correct one
        $this->assertNotNull(
            $repository->getBy('name', 'Second')
        );
    }

}
