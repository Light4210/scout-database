<?php

namespace App\Command;

use App\Entity\Invite;
use App\Entity\User;
use App\Repository\InviteRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
final class InviteComand extends Command
{
    public function __construct(UserRepository $userRepository, InviteRepository $inviteRepository, MailerInterface $mailer)
    {
        parent::__construct('invitation');
        $this->userRepository = $userRepository;
        $this->inviteRepository = $inviteRepository;
        $this->mailer = $mailer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = [
            'muronchuk402@gmail.com' => 'none',
            'tarnavskij2002@gmail.com' => 'sheaf',
            'natali.17p7@gmail.com' => 'none',
            'dvinnik450@gmail.com' => 'akela',
            'andrusyak.nataliya@gmail.com' => 'крайова',
            'romanovich1414@gmail.com' => 'none',
            'Halyabilan@gmail.com' => 'sheaf',
            'kostivandrii29@gmail.com' => 'troopLeader',
            'kuntijvolodimir6@gmail.com' => 'none',
            'zhelizko.nastya@gmail.com' => 'troopLeader',
            'hadiychuksolomia@gmail.com' => 'none',
            'denys.reksha@icloud.com' => 'akela',
            'petrokapa6it@gmail.com' => 'akela',
            'maria.hulia10@gmail.com' => 'troopLeader',
            'dimaichuck@gmail.com' => 'none',
            'marymarichka14@gmail.com' => 'troopLeader',
            'soroka0209@gmail.com' => 'troopLeader',
            'ju.vasuliv@gmail.com' => 'none',
            'Biletska.Marichka@gmail.com' => 'none',
            'owerkomr@gmail.com' => 'none',
            'yarynazaviiska@gmail.com' => 'sheaf',
            'vladkondratskyi@gmail.com' => 'sheaf',
        ];

        /** @var Invite $user */
        foreach ($users as $email => $role) {
            $data = new Invite();
            $data->setEmail($email);
            $data->setMinistry($role);
            $registerLink = 'http://uigse.org.ua/register?token=' . $data->getToken();

            $email = (new Email())
                ->from('from@example.com')
                ->to($data->getEmail())
                ->subject('Welcome to UIGSE database!')
                ->text("this registration link is valid 10 days: $registerLink");
            $this->mailer->send($email);
        }
        return Command::SUCCESS;
    }
}
