<?php declare(strict_types=1);

namespace App\Console\Command;

use App\Rpc\Lib\UserInterface;
use Swoft\Console\Annotation\Mapping\Command;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Console\Helper\Show;
use Swoft\Console\Input\Input;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Class DemoCommand
 *
 * @Command()
 */
class DemoCommand
{
    /**
     * @var UserInterface
     * @Reference(pool="user.pool")
     */
    private $user;

    /**
     * @CommandMapping()
     * @param Input $input
     */
    public function test(Input $input): void
    {
//        Show::prettyJSON([
//            'args' => $input->getArgs(),
//            'opts' => $input->getOptions(),
//        ]);

        Show::prettyJSON(
            $this->user->getBigContent()
        );
    }
}
