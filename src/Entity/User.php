<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Tasks::class, mappedBy="user")
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity=Tasks::class, mappedBy="createdBy")
     */
    private $taskCreadedBy;

    /**
     * @ORM\OneToMany(targetEntity=Tasks::class, mappedBy="updatedBy")
     */
    private $taskUpdatedBy;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->taskCreadedBy = new ArrayCollection();
        $this->taskUpdatedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Tasks[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tasks[]
     */
    public function getTaskCreadedBy(): Collection
    {
        return $this->taskCreadedBy;
    }

    public function addTaskCreadedBy(Tasks $taskCreadedBy): self
    {
        if (!$this->taskCreadedBy->contains($taskCreadedBy)) {
            $this->taskCreadedBy[] = $taskCreadedBy;
            $taskCreadedBy->setCreatedBy($this);
        }

        return $this;
    }

    public function removeTaskCreadedBy(Tasks $taskCreadedBy): self
    {
        if ($this->taskCreadedBy->removeElement($taskCreadedBy)) {
            // set the owning side to null (unless already changed)
            if ($taskCreadedBy->getCreatedBy() === $this) {
                $taskCreadedBy->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tasks[]
     */
    public function getTaskUpdatedBy(): Collection
    {
        return $this->taskUpdatedBy;
    }

    public function addTaskUpdatedBy(Tasks $taskUpdatedBy): self
    {
        if (!$this->taskUpdatedBy->contains($taskUpdatedBy)) {
            $this->taskUpdatedBy[] = $taskUpdatedBy;
            $taskUpdatedBy->setUpdatedBy($this);
        }

        return $this;
    }

    public function removeTaskUpdatedBy(Tasks $taskUpdatedBy): self
    {
        if ($this->taskUpdatedBy->removeElement($taskUpdatedBy)) {
            // set the owning side to null (unless already changed)
            if ($taskUpdatedBy->getUpdatedBy() === $this) {
                $taskUpdatedBy->setUpdatedBy(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getEmail();
    }
}
