<?php 
namespace Martinshaw\StreetGroupInterviewTechTest;

use JsonSerializable;

class HomeownerName implements JsonSerializable {
    private string $title;
    private ?string $firstName = null;
    private ?string $initial = null;
    private string $lastName;
    
    public function __construct(string $title, ?string $firstName, ?string $initial, string $lastName) {
        $this->title = $title;
        $this->firstName = $firstName;
        $this->initial = $initial;
        $this->lastName = $lastName;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getFirstName(): ?string {
        return $this->firstName;
    }

    public function getInitial(): ?string {
        return $this->initial;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function jsonSerialize(): array {
        return [
            'title' => $this->getTitle(),
            'firstName' => $this->getFirstName(),
            'initial' => $this->getInitial(),
            'lastName' => $this->getLastName(),
        ];
    }
}