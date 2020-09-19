<?php
declare(strict_types=1);

namespace devnullius\user\useCases\auth;

use devnullius\user\forms\auth\PasswordResetRequestForm;
use devnullius\user\forms\auth\ResetPasswordForm;
use devnullius\user\repositories\UserRepository;
use DomainException;
use RuntimeException;
use Yii;
use yii\base\Exception;
use yii\mail\MailerInterface;

final class PasswordResetService
{
    private MailerInterface $mailer;
    private UserRepository $users;

    public function __construct(UserRepository $users, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->users->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new DomainException('User is not active.');
        }

        try {
            $user->requestPasswordReset();
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
        $this->users->save($user);

        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $user]
            )
            ->setTo($user->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new RuntimeException('Sending error.');
        }
    }

    public function validateToken(string $token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new DomainException('Password reset token cannot be blank.');
        }
        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new DomainException('Wrong password reset token.');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        try {
            $user->resetPassword($form->password);
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
        }
        $this->users->save($user);
    }
}
