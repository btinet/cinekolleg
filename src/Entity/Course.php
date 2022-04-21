<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\OneToMany(targetEntity=LessonDoc::class, mappedBy="course")
     */
    private $lessonDocs;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=CourseComment::class, mappedBy="course")
     */
    private $courseComments;

    public function __construct()
    {
        $this->lessonDocs = new ArrayCollection();
        $this->courseComments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title.': '.$this->subject;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return Collection<int, LessonDoc>
     */
    public function getLessonDocs(): Collection
    {
        return $this->lessonDocs;
    }

    public function addLessonDoc(LessonDoc $lessonDoc): self
    {
        if (!$this->lessonDocs->contains($lessonDoc)) {
            $this->lessonDocs[] = $lessonDoc;
            $lessonDoc->setCourse($this);
        }

        return $this;
    }

    public function removeLessonDoc(LessonDoc $lessonDoc): self
    {
        if ($this->lessonDocs->removeElement($lessonDoc)) {
            // set the owning side to null (unless already changed)
            if ($lessonDoc->getCourse() === $this) {
                $lessonDoc->setCourse(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, CourseComment>
     */
    public function getCourseComments(): Collection
    {
        return $this->courseComments;
    }

    public function addCourseComment(CourseComment $courseComment): self
    {
        if (!$this->courseComments->contains($courseComment)) {
            $this->courseComments[] = $courseComment;
            $courseComment->setCourse($this);
        }

        return $this;
    }

    public function removeCourseComment(CourseComment $courseComment): self
    {
        if ($this->courseComments->removeElement($courseComment)) {
            // set the owning side to null (unless already changed)
            if ($courseComment->getCourse() === $this) {
                $courseComment->setCourse(null);
            }
        }

        return $this;
    }
}
