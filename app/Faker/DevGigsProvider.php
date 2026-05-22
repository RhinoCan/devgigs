<?php

namespace App\Faker;

use Faker\Provider\Base;

class DevGigsProvider extends Base
{
    protected array $skills = [
        'PHP',
        'Laravel',
        'MySQL',
        'PostgreSQL',
        'Redis',
        'REST API',
        'API',
        'Backend',
        'Frontend',
        'Vue.js',
        'JavaScript',
        'TypeScript',
        'HTML',
        'CSS',
        'Tailwind CSS',
        'Docker',
        'Git',
        'Linux',
        'CI/CD',
        'Unit Testing',
        'PHPUnit',
        'TDD',
        'AWS',
        'Nginx',
        'Apache',
        'Composer',
        'MVC',
        'OOP',
        'Livewire',
        'Inertia.js',
    ];


    public function techSkills(
        int $min = 1,
        int $max = 4
    ): array {
        $count = random_int($min, $max);

        return static::randomElements(
            $this->skills,
            $count
        );
    }

    public function techSkillsCsv(
        int $min = 1,
        int $max = 4
    ): string {
        return implode(
            ', ',
            $this->techSkills($min, $max)
        );
    }

    protected array $titles = [
      'Full Stack Developer',
      'Backend Developer',
      'Frontend Developer',
      'Software Engineer',
      'DevOps Engineer',
      'Cloud Infrastructure Engineer',
      'Database Administrator',
      'QA Engineer',
      'Site Reliability Engineer',
      'API Developer',
      'PHP Developer',
      'Laravel Developer',
      'JavaScript Developer',
      'UI/UX Developer',
      'Mobile App Developer',
      'Systems Analyst',
      'Technical Lead',
      'Software Architect',
      'Security Engineer',
      'Platform Engineer'
    ];

    public function techTitle() {

      return static::randomElement(
            $this->titles
        );
    }
}