<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\CreateProjectRequest;
use App\DTO\Request\UpdateProjectRequest;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Workspace;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    private EntityManagerInterface $em;
    private ActivityService $activityService;

    public function __construct(EntityManagerInterface $em, ActivityService $activityService)
    {
        $this->em = $em;
        $this->activityService = $activityService;
    }

    public function createProject(Workspace $workspace, CreateProjectRequest $request, User $user): Project
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name), '-')) . '-' . substr(uniqid(), -5);
        $project = new Project($workspace, $request->name, $slug, $user);
        $project->setDescription($request->description);

        $this->em->persist($project);
        $this->em->flush();

        $this->activityService->logActivity($workspace, $user, 'project.created', ['name' => $project->getName()], $project);

        return $project;
    }

    public function updateProject(Project $project, UpdateProjectRequest $request, User $user): Project
    {
        $project->setName($request->name);
        if ($request->description !== null) {
            $project->setDescription($request->description);
        }

        $this->em->flush();

        $this->activityService->logActivity($project->getWorkspace(), $user, 'project.updated', ['name' => $project->getName()], $project);

        return $project;
    }

    public function deleteProject(Project $project, User $user): void
    {
        $this->activityService->logActivity($project->getWorkspace(), $user, 'project.deleted', ['name' => $project->getName()]);
        $this->em->remove($project);
        $this->em->flush();
    }
}
