import os
import pathlib

base_dir = r"C:\Users\DARREN\Downloads\BUILDS\flowboard\backend\src\DTO"
req_dir = os.path.join(base_dir, "Request")
res_dir = os.path.join(base_dir, "Response")

os.makedirs(req_dir, exist_ok=True)
os.makedirs(res_dir, exist_ok=True)

requests = {
    "RegisterRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class RegisterRequest
{
    #[Assert\\NotBlank]
    #[Assert\\Email]
    public string $email;

    #[Assert\\NotBlank]
    #[Assert\\Length(min: 8)]
    public string $password;

    #[Assert\\NotBlank]
    public string $fullName;
}
""",
    "LoginRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class LoginRequest
{
    #[Assert\\NotBlank]
    #[Assert\\Email]
    public string $email;

    #[Assert\\NotBlank]
    public string $password;
}
""",
    "CreateWorkspaceRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class CreateWorkspaceRequest
{
    #[Assert\\NotBlank]
    public string $name;

    public ?string $description = null;
}
""",
    "UpdateWorkspaceRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class UpdateWorkspaceRequest
{
    #[Assert\\NotBlank]
    public string $name;

    public ?string $description = null;
}
""",
    "InviteMemberRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use App\\Enum\\WorkspaceRole;
use Symfony\\Component\\Validator\\Constraints as Assert;

class InviteMemberRequest
{
    #[Assert\\NotBlank]
    #[Assert\\Email]
    public string $email;

    #[Assert\\NotBlank]
    public string $role;
}
""",
    "CreateProjectRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class CreateProjectRequest
{
    #[Assert\\NotBlank]
    public string $name;

    public ?string $description = null;
}
""",
    "UpdateProjectRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class UpdateProjectRequest
{
    #[Assert\\NotBlank]
    public string $name;

    public ?string $description = null;
}
""",
    "CreateTaskRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class CreateTaskRequest
{
    #[Assert\\NotBlank]
    public string $title;

    public ?string $description = null;
    
    public string $status = 'BACKLOG';
    
    public string $priority = 'MEDIUM';
    
    public ?string $assigneeId = null;
    
    public ?string $dueDate = null;
    
    public ?string $parentTaskId = null;
}
""",
    "UpdateTaskRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class UpdateTaskRequest
{
    public ?string $title = null;
    public ?string $description = null;
    public ?string $status = null;
    public ?string $priority = null;
    public ?string $assigneeId = null;
    public ?string $dueDate = null;
}
""",
    "MoveTaskRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class MoveTaskRequest
{
    #[Assert\\NotBlank]
    public string $status;

    #[Assert\\NotNull]
    public int $position;
}
""",
    "CreateCommentRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class CreateCommentRequest
{
    #[Assert\\NotBlank]
    public string $body;
}
""",
    "GenerateTasksRequest": """<?php
declare(strict_types=1);

namespace App\\DTO\\Request;

use Symfony\\Component\\Validator\\Constraints as Assert;

class GenerateTasksRequest
{
    #[Assert\\NotBlank]
    public string $prompt;
}
"""
}

responses = {
    "UserResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

use App\\Entity\\User;

class UserResponse
{
    public string $id;
    public string $email;
    public string $fullName;

    public static function fromEntity(User $user): self
    {
        $res = new self();
        $res->id = (string) $user->getId();
        $res->email = $user->getEmail();
        $res->fullName = $user->getFullName();
        return $res;
    }
}
""",
    "WorkspaceResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

use App\\Entity\\Workspace;

class WorkspaceResponse
{
    public string $id;
    public string $name;
    public string $slug;

    public static function fromEntity(Workspace $workspace): self
    {
        $res = new self();
        $res->id = (string) $workspace->getId();
        $res->name = $workspace->getName();
        $res->slug = $workspace->getSlug();
        return $res;
    }
}
""",
    "WorkspaceDetailResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

use App\\Entity\\Workspace;

class WorkspaceDetailResponse
{
    public string $id;
    public string $name;
    public string $slug;
    public ?string $description;

    public static function fromEntity(Workspace $workspace): self
    {
        $res = new self();
        $res->id = (string) $workspace->getId();
        $res->name = $workspace->getName();
        $res->slug = $workspace->getSlug();
        $res->description = $workspace->getDescription();
        return $res;
    }
}
""",
    "ProjectResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

use App\\Entity\\Project;

class ProjectResponse
{
    public string $id;
    public string $name;
    public string $slug;
    public ?string $description;

    public static function fromEntity(Project $project): self
    {
        $res = new self();
        $res->id = (string) $project->getId();
        $res->name = $project->getName();
        $res->slug = $project->getSlug();
        $res->description = $project->getDescription();
        return $res;
    }
}
""",
    "TaskResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

use App\\Entity\\Task;

class TaskResponse
{
    public string $id;
    public string $title;
    public ?string $description;
    public string $status;
    public string $priority;

    public static function fromEntity(Task $task): self
    {
        $res = new self();
        $res->id = (string) $task->getId();
        $res->title = $task->getTitle();
        $res->description = $task->getDescription();
        $res->status = $task->getStatus()->value;
        $res->priority = $task->getPriority()->value;
        return $res;
    }
}
""",
    "CommentResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

use App\\Entity\\Comment;

class CommentResponse
{
    public string $id;
    public string $body;
    public UserResponse $user;
    public string $createdAt;

    public static function fromEntity(Comment $comment): self
    {
        $res = new self();
        $res->id = (string) $comment->getId();
        $res->body = $comment->getBody();
        $res->user = UserResponse::fromEntity($comment->getUser());
        $res->createdAt = $comment->getCreatedAt()->format('c');
        return $res;
    }
}
""",
    "AiSuggestionResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

use App\\Entity\\AiSuggestion;

class AiSuggestionResponse
{
    public string $id;
    public string $type;
    public string $status;
    public array $outputData;

    public static function fromEntity(AiSuggestion $suggestion): self
    {
        $res = new self();
        $res->id = (string) $suggestion->getId();
        $res->type = $suggestion->getType()->value;
        $res->status = $suggestion->getStatus()->value;
        $res->outputData = $suggestion->getOutputData();
        return $res;
    }
}
""",
    "PaginatedResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

class PaginatedResponse
{
    public array $data;
    public int $total;
    public int $page;
    public int $limit;

    public function __construct(array $data, int $total, int $page, int $limit)
    {
        $this->data = $data;
        $this->total = $total;
        $this->page = $page;
        $this->limit = $limit;
    }
}
""",
    "ErrorResponse": """<?php
declare(strict_types=1);

namespace App\\DTO\\Response;

class ErrorResponse
{
    public string $message;
    public array $errors;

    public function __construct(string $message, array $errors = [])
    {
        $this->message = $message;
        $this->errors = $errors;
    }
}
"""
}

for name, content in requests.items():
    with open(os.path.join(req_dir, name + ".php"), "w", encoding="utf-8") as f:
        f.write(content)

for name, content in responses.items():
    with open(os.path.join(res_dir, name + ".php"), "w", encoding="utf-8") as f:
        f.write(content)

print("Generated all DTO files.")
