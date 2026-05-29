# DevGigs
## Purpose and Audience
DevGigs is a portfolio project demonstrating a CRUD [^1]. In this case, the records are those representing imaginary "gigs" (job postings) at imaginary companies. The intended audience is prospective employers and anyone seeking a good example of a CRUD app.

## Features
The app can be used by guests to determine:
- what (imaginary) gigs are available
- what the gig entails
- who the (imaginary) employer is
- where the gig is located
- what skills the employer is seeking
- how to reach the employer by email
- where their website is
- what the company's logo is.

Buttons are provided so that the guest can send an email to the employer immediately or open their website in the guest's browser.

Two views are provided of the list of gigs: a grid view and a list view.

The guest can search for all instances of a given tag by clicking on the tag or do a more general search through the title, location or tags.

The guest can sort the list of gigs in order by title, company or location.

Registered users have the ability to create gigs, and then update and delete any gigs they have created, in addition to being able to do everything guests can do.

## Tech Stack
- Laravel 13.x
- Tailwind CSS 4.x (via Vite)
- Alpine.js (for flash messages)
- JavaScript (for handling the grid and list views)
- Docker
- Render (for production deployment)
- Neon (for the PostgreSQL database used in production)
- Cloudinary (for the storage of logos in production)

## Setup
1. Clone the repository
2. Run `composer install`
3. Run `npm install`
4. Copy `.env.example` to `.env` and configure your database
5. Run `php artisan key:generate`
6. Run `php artisan migrate --seed`
7. Run `npm run dev` and `php artisan serve` in separate terminals

## Credits
After a few years away from Laravel, I decided to refresh my memory of Laravel by taking Brad Traversy's excellent YouTube course that builds "Laragigs", an app that lets imaginary employers put imaginary jobs on a website. With some additional tutelage from Claude.AI to bring me up to speed on changes to Laravel since the course was written in 2022 and refresh my memory on other aspects of Laravel, I built the entire app following the course's instructions.

I saw a few things that I thought could be improved so I added the list view and the sorting and wrote a full suite of feature tests, again with the help of Claude.ai. I also put the project in GitHub and deployed it to production so it would be visible in a code portfolio.

[^1]: A CRUD is an app that Creates, Reads, Updates, and Deletes records.
