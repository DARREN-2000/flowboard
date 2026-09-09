<?php
declare(strict_types=1);

namespace App\Tests\Functional\Security;

use App\Entity\User;
use App\Entity\Workspace;
use App\Entity\WorkspaceMember;
use App\Enum\WorkspaceRole;
use App\Security\Voter\WorkspaceVoter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class VoterTest extends TestCase
{
    public function testWorkspaceVoter(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $repo = $this->createMock(EntityRepository::class);

        $em->method('getRepository')->willReturn($repo);

        $voter = new WorkspaceVoter($em);
        $this->assertNotNull($voter);
    }
}
