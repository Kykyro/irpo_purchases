<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DebugTwigController extends AbstractController
{
    /**
     * @Route("/command/debug-twig/{year}/{type}", name="app_command_readiness_map")
     */
    public function debugTwig(KernelInterface $kernel, Request $request, int $year, String $type): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(true);

//        $input = new ArrayInput([
//            'command' => 'app:save:rmnew',
//            'year' => $year,
//            'type' => $type,
//        ]);
        $command = "php bin/console app:save:rmnew $year $type";
//        $command = "ls";
        $process = Process::fromShellCommandLine("cd ../bin", $command);
        // You can use NullOutput() if you don't need the output
//        $output = new BufferedOutput();
//        $application->run($input, $output);
//
//        // return the output, don't use if you used NullOutput()
//        $content = $output->fetch();
//        try {
//            $process->run();
//            $process->wait();
//        } catch (ProcessFailedException $e) {
//
//        }

//        $process = new Process(["app:save:rmnew $year $type"]);
        $process->run();

// executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }


        return $this->redirectToRoute('app_inspector_rm_history');
        $route = $request->headers->get('referer');
        return $this->redirect($route);
        // return new Response(""), if you used NullOutput()
        return new Response($content);
    }
}