<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Project\Remove;

use App\Model\Flusher;
use App\Model\Projects\Project\Id;
use App\Model\Projects\Project\ProjectRepository;

class handler
{
    private $projects;
    private $flusher;

    public function __construct(ProjectRepository $projects, Flusher $flusher)
    {
        $this->projects = $projects;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $project = $this->projects->get(new Id($command->id));

        $this->projects->remove($project);

        $this->flusher->flush();
    }
}