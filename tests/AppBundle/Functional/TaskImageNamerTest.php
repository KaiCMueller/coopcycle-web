<?php

namespace Tests\AppBundle\Functional;

use AppBundle\Entity\Address;
use AppBundle\Entity\Task;
use AppBundle\Entity\TaskImage;
use AppBundle\Utils\TaskImageNamer;
use Cocur\Slugify\Slugify;
use PHPUnit\Framework\TestCase;

class TaskImageNamerTest extends TestCase
{
    public function testGetImageDownloadFileName()
    {
        $taskImageNamer = new TaskImageNamer();
        $slugify = new Slugify();

        $date = new \DateTime();
        $dateFormatted = $date->format('Y-m-d');

        $taskAdress = $this->prophesize(Address::class);
        $taskAdress->getName()
            ->willReturn('Test Name');

        $task = $this->prophesize(Task::class);
        $task->getAddress()
            ->willReturn($taskAdress);
        $task->getCreatedAt()
            ->willReturn($date);

        $taskImage = $this->prophesize(TaskImage::class);
        $taskImage->getTask()
            ->willReturn($task);
        $taskImage->getImageName()
            ->willReturn('122.png');
        $taskImage->getId()
            ->willReturn(122);

        $taskImageName = $taskImageNamer->getImageDownloadFileName($taskImage->reveal(), $slugify);

        $this->assertEquals(
            sprintf('122_test-name_%s.png', $dateFormatted),
            $taskImageName
        );
    }
}
