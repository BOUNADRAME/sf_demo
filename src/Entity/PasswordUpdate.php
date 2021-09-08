<?php

namespace App\Entity;

use App\Repository\PasswordUpdateRepository;

use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{
    /**
     * @Assert\NotBlank(message="champs obligatoire !")
     */
    private $oldPassword;

    /**
     * @Assert\Length(min=4, minMessage="Votre password doit faire au moins 4 caractères !")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Vous n'avez pas correctement confirmer votre password")
     */
    private $confirmPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}