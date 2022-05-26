<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 * @Vich\Uploadable
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

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="courses")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document;

    /**
     * @Vich\UploadableField(mapping="course_image", fileNameProperty="document")
     * @var File
     */
    private $documentFile;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity=CourseImage::class, mappedBy="course")
     */
    private $courseImages;

    /**
     * @ORM\OneToMany(targetEntity=CourseSection::class, mappedBy="course")
     */
    private $courseSections;

    /**
     * @ORM\OneToMany(targetEntity=CourseAppointment::class, mappedBy="course")
     */
    private $courseAppointments;

    public function __construct()
    {
        $this->lessonDocs = new ArrayCollection();
        $this->courseComments = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->courseImages = new ArrayCollection();
        $this->courseSections = new ArrayCollection();
        $this->courseAppointments = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getDocument()
    {
        return $this->document;
    }


    public function setDocument($document): void
    {
        $this->document = $document;
    }


    public function getDocumentFile()
    {
        return $this->documentFile;
    }

    /**
     * @param File|null $documentFile
     */
    public function setDocumentFile(File $documentFile = null): void
    {
        $this->documentFile = $documentFile;

        if ($documentFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updated = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return Collection<int, CourseImage>
     */
    public function getCourseImages(): Collection
    {
        return $this->courseImages;
    }

    public function addCourseImage(CourseImage $courseImage): self
    {
        if (!$this->courseImages->contains($courseImage)) {
            $this->courseImages[] = $courseImage;
            $courseImage->setCourse($this);
        }

        return $this;
    }

    public function removeCourseImage(CourseImage $courseImage): self
    {
        if ($this->courseImages->removeElement($courseImage)) {
            // set the owning side to null (unless already changed)
            if ($courseImage->getCourse() === $this) {
                $courseImage->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CourseSection>
     */
    public function getCourseSections(): Collection
    {
        return $this->courseSections;
    }

    public function addCourseSection(CourseSection $courseSection): self
    {
        if (!$this->courseSections->contains($courseSection)) {
            $this->courseSections[] = $courseSection;
            $courseSection->setCourse($this);
        }

        return $this;
    }

    public function removeCourseSection(CourseSection $courseSection): self
    {
        if ($this->courseSections->removeElement($courseSection)) {
            // set the owning side to null (unless already changed)
            if ($courseSection->getCourse() === $this) {
                $courseSection->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CourseAppointment>
     */
    public function getCourseAppointments(): Collection
    {
        return $this->courseAppointments;
    }

    public function addCourseAppointment(CourseAppointment $courseAppointment): self
    {
        if (!$this->courseAppointments->contains($courseAppointment)) {
            $this->courseAppointments[] = $courseAppointment;
            $courseAppointment->setCourse($this);
        }

        return $this;
    }

    public function removeCourseAppointment(CourseAppointment $courseAppointment): self
    {
        if ($this->courseAppointments->removeElement($courseAppointment)) {
            // set the owning side to null (unless already changed)
            if ($courseAppointment->getCourse() === $this) {
                $courseAppointment->setCourse(null);
            }
        }

        return $this;
    }
}
