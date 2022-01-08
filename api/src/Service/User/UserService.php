<?php

namespace App\Service\User;

use App\DataObject\UserDataObject;
use App\Entity\User;
use App\Exception\ApiException;
use App\FileUploader\FileUploader;
use App\Repository\UserRepository;
use App\Service\Validator\ValidatorDTOInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\UółniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService implements UserServiceInterface
{
    private ValidatorDTOInterface $validator;
    private UserPasswordEncoderInterface $authenticatedUser;
    private UserRepository $userRepository;
    private FileUploader $fileUploader;

    /**
     * UserService constructor
     *
     * @param ValidatorDTOInterface $validator
     * @param UserPasswordEncoderInterface $authenticatedUser
     * @param UserRepository $userRepository
     * @param FileUploader $fileUploader
     */
    public function __construct(
        ValidatorDTOInterface $validator,
        UserPasswordEncoderInterface $authenticatedUser,
        UserRepository $userRepository,
        FileUploader $fileUploader
    )
    {
        $this->validator = $validator;
        $this->authenticatedUser = $authenticatedUser;
        $this->userRepository = $userRepository;
        $this->fileUploader = $fileUploader;
    }

    /**
     * Create User
     *
     * @param UserDataObject $dto
     *
     * @return bool
     * @throws ApiException
     */
    public function createUser(UserDataObject $dto): bool
    {
        $this->validator->validate($dto);

        $user = new User();
        $user->setEmail($dto->email);
        $user->setNickname($dto->nickname);
        $user->setPassword($this->authenticatedUser->encodePassword($user, $dto->password));

        try {
            $this->userRepository->save($user);
        } catch (OptimisticLockException | ORMException $e) {

        } catch (UniqueConstraintViolationException $e) {
            throw new ApiException(
                message: 'Validation exception',
                data: ['email' => ['Użytkownik o podanym adresie email już istnieje']],
                statusCode: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return true;
    }

    /**
     * Get team list
     *
     * @param UserInterface $user
     *
     * @return Collection
     */
    public function getTeamList(UserInterface $user): Collection
    {
        /** @var User $user */
        $teams = $user->getTeams();
        foreach ($teams as $team) {
            $team->setAvatarUrl(
                Request::createFromGlobals()->getSchemeAndHttpHost() .
                $this->fileUploader->getTargetUrl() .
                $team->getAvatarUrl()
            );
        }

        return $teams;
    }

    /**
     * @param int $teamId
     * @param UserInterface $user
     *
     * @return array
     */
    public function getRepaymentList(int $teamId, UserInterface $user): array
    {
        /** @var User $user */
        $array['debts'] = $user->getDebtsFromRepayments();
        $array['credits'] = $user->getCreditsFromRepayments();

        foreach ($array['debts'] as $i => $repayment) {
            if ($repayment->getValue() === '0.00') {
                unset($array['debts'][$i]);
            }
        }

        foreach ($array['credits'] as $i => $repayment) {
            if ($repayment->getValue() === '0.00') {
                unset($array['credits'][$i]);
            }
        }

        return $array;
    }
}