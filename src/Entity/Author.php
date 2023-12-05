<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: 'integer')]
    private ?int $nb_books = 0;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
    private Collection $books;

    #[ORM\OneToMany(mappedBy: 'nbbooks', targetEntity: book::class)]
    private Collection $nbbooks;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->nbbooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, book>
     */
    public function getNbbooks(): Collection
    {
        return $this->nbbooks;
    }

    public function addNbbook(book $nbbook): static
    {
        if (!$this->nbbooks->contains($nbbook)) {
            $this->nbbooks->add($nbbook);
            $nbbook->setNbbooks($this);
        }

        return $this;
    }

    public function removeNbbook(book $nbbook): static
    {
        if ($this->nbbooks->removeElement($nbbook)) {
            if ($nbbook->getNbbooks() === $this) {
                $nbbook->setNbbooks(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getUsername();
    }

}