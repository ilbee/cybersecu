<?php

namespace App\Command;

use App\Entity\Movie;
use App\Entity\Personality;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class MoviesTmdbImportCommand extends Command
{
    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    protected static $defaultName = 'movies:tmdb:import';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Add a short description for your command';

    /**
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->fileSystem = $fileSystem;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filePath', InputArgument::REQUIRED, 'TMDB file path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '-1');

        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('filePath');

        if ( !$this->fileSystem->exists($filePath) ) {
            $io->error(sprintf('File %s does not exist', $filePath));
            return Command::FAILURE;
        }

        $lines = file($filePath);
        $movies = 0;
        foreach ( $lines as $line ) {
            $data = explode(';', $line);

            $movie = $this->entityManager->getRepository(Movie::class)->findOneBy(['title' => $data[5]]);
            if ( $movie ) {
                continue;
            }

            $movie = new Movie();
            $actorNames = explode('|', $data[6]);
            foreach ( $actorNames as $actorName ) {
                $actor = $this->entityManager
                    ->getRepository(Personality::class)
                    ->findOneBy(['name' => $actorName]);

                if (!$actor) {
                    $actor = new Personality();
                    $actor->setName($actorName);
                    $this->entityManager->persist($actor);
                }

                $movie->addActor($actor);
            }

            //dd($data);
            $movie
                ->setTitle($data[5])
                ->setWebsite($data[7])
                ->setTags($data[9])
                ->setDuration($data[12])
                ->setOverview($data[11])
                ->setReleasedAt(new \DateTimeImmutable($data[15]))
            ;

            $this->entityManager->persist($movie);
            $this->entityManager->flush();
            $movies++;
        }

        $io->success(sprintf('Successfully import %s movies', $movies));

        return Command::SUCCESS;
    }
}
