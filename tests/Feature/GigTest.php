<?php

use App\Models\Gig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/* Tests needed:

Index page shows "No gigs" when there are no gigs
Index page shows correct number of gigs (and the correct gigs) when there are gigs
Search of text on index page returns all matching gigs and nothing more
Search of tags on index page returns all matching gigs and nothing more

Pressing FAB on index page goes to Create page
Pressing FAB on show page goes to Create page
Pressing FAB on manage page goes to Create page

Pressing Create Gig on empty Create form produces must-be-entered errors for all fields except logo
Pressing Create Gig on Create form with missing must-be-entered values produces errors for the fields which have been omitted
Pressing Back on Create form (regardless of its state of completion) should return to Index page


*/


describe('Page loading while not logged in', function () {
  it('shows the gigs index page', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSeeText("Copyright");
  });

  it('shows a single gig', function () {
    $gig = Gig::factory()->create();
    $response = $this->get("/{$gig->id}");
    $response->assertStatus(200);
  });

  it('shows a register page', function () {
    $response = $this->get("/register");
    $response->assertStatus(200);
  });

  it('shows a login page', function () {
    $response = $this->get("/login");
    $response->assertStatus(200);
  });
});

describe('Logout', function () {
  it('logs out', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->post('/logout');
    $response->assertRedirect('/');
    $home = $this->get('/');
    $home->assertSeeText('Copyright');
    $home->assertSeeText('Register');
    $home->assertSeeText('Login');
  });
});

describe('Registration', function () {
  it('tries to register but presses Register button without completing any fields on the form', function () {
    $response = $this->post('/users', []);
    $response->assertSessionHasErrors([
      'name',
      'email',
      'password'
    ]);
  });

  it('tries to register but presses Register button without completing some fields on the form', function () {
    $response = $this->post('/users', [
      'name' => 'John Doe',
      'email' => 'jdoe@example.com'
    ]);
    $response->assertSessionHasErrors([
      'password' => 'The password field is required.'
    ]);
  });

  it('tries to register but presses Register button when passwords do not match', function () {
    $response = $this->post('/users', [
      'name' => 'John Doe',
      'email' => 'jdoe@example.com',
      'password' => 'doughnut',
      'password_confirmation' => 'rutabaga'
    ]);
    $response->assertSessionHasErrors([
      'password' => 'The password field confirmation does not match.'
    ]);
  });

  it('presses Register button after completing form with data that is all valid', function () {
    $response = $this->post('/users', [
      'name' => 'John Doe',
      'email' => 'jdoe@example.com',
      'password' => 'doughnut',
      'password_confirmation' => 'doughnut'
    ]);
    $response->assertRedirect('/');
    $this->assertDatabaseHas('users', ['email' => 'jdoe@example.com']);
    $home = $this->get('/');
    $home->assertSeeText('Copyright');
    $home->assertSeeText('Welcome John Doe');
    $home->assertSeeText('Manage Gigs');
    $home->assertSeeText('Logout');
  });
});

describe('Login', function () {
  beforeEach(function () {
    $response = $this->post('/users', [
      'name' => 'John Doe',
      'email' => 'jdoe@example.com',
      'password' => 'doughnut',
      'password_confirmation' => 'doughnut'
    ]);
  });

  it('tries to login but presses Login button without completing any fields on the form', function () {
    $response = $this->post('/users/authenticate', []);
    $response->assertSessionHasErrors([
      'email' => 'The email field is required.',
      'password' => 'The password field is required.',
    ]);
  });

  it('tries to login but presses Login button without completing some fields on the form', function () {
    $response = $this->post('/users/authenticate', [
      'name' => 'John Doe',
    ]);
    $response->assertSessionHasErrors([
      'password' => 'The password field is required.'
    ]);
  });

  it('presses Login button after completing form with data that is all valid', function () {
    $response = $this->post('/users/authenticate', [
      'email' => 'jdoe@example.com',
      'password' => 'doughnut',
    ]);
    $response->assertRedirect('/');
    $home = $this->get('/');
    $home->assertSeeText('Copyright');
    $home->assertSeeText('Welcome John Doe');
    $home->assertSeeText('Manage Gigs');
    $home->assertSeeText('Logout');
  });

  it('tries to login with invalid credentials', function () {
    User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('correctpassword')
    ]);
    $response = $this->post('/users/authenticate', [
      'email' => 'test@example.com',
      'password' => 'wrongpassword'
    ]);
    $response->assertSessionHasErrors(['email']);
  });
});

describe('Gig model', function () {
  it('proves that a gig is owned by an owner', function () {
    $user = User::factory()->create();
    $gig = Gig::factory()->create(['user_id' => $user->id]);
    expect($gig->user->id)->toBe($user->id);
  });
});

describe('Search', function () {
  beforeEach(function () {
    $this->gig1 = Gig::factory()->create([
      'title' => 'Senior Laravel Developer',
      'location' => 'Toronto, ON',
      'tags' => 'Family law, estate law'
    ]);
    $this->gig2 = Gig::factory()->create([
      'title' => 'Junior Laravel Developer',
      'location' => 'Toronto, ON',
      'tags' => 'Family law, estate law'
    ]);
    $this->gig3 = Gig::factory()->create([
      'title' => 'Paralegal',
      'location' => 'Laravel, PQ',
      'tags' => 'Family law, estate law'
    ]);
    $this->gig4 = Gig::factory()->create([
      'title' => 'Paralegal',
      'location' => 'Toronto, ON',
      'tags' => 'Javascript, Laravel'
    ]);
    $this->gig5 = Gig::factory()->create([
      'title' => 'Paralegal',
      'location' => 'Toronto, ON',
      'tags' => 'Family law, estate law'
    ]);
  });

  it('searches tags for a particular value', function () {
    $results = Gig::filter(['tags' => 'Laravel'])->get();
    // expect($results)->toHaveCount(1);
    // Method 1
    expect($results->contains($this->gig1))->toBeFalse();
    expect($results->contains($this->gig2))->toBeFalse();
    expect($results->contains($this->gig3))->toBeFalse();
    expect($results->contains($this->gig4))->toBeTrue();
    expect($results->contains($this->gig5))->toBeFalse();
    // Method 2
    expect($results->pluck('id'))->toContain($this->gig4->id);
    // Method 3
    expect($results->pluck('id')->all())->toEqualCanonicalizing([
      $this->gig4->id
    ]);
  });

  it('searches title, location and tags for a particular value', function () {
    $results = Gig::filter(['search' => 'Laravel'])->get();
    expect($results)->toHaveCount(4);
    // Method 1
    expect($results->contains($this->gig1))->toBeTrue();
    expect($results->contains($this->gig2))->toBeTrue();
    expect($results->contains($this->gig3))->toBeTrue();
    expect($results->contains($this->gig4))->toBeTrue();
    expect($results->contains($this->gig5))->toBeFalse();
    // Method 2
    expect($results->pluck('id'))->toContain($this->gig1->id, $this->gig2->id, $this->gig3->id, $this->gig4->id);
    // Method 3
    expect($results->pluck('id')->all())->toEqualCanonicalizing([
      $this->gig1->id,
      $this->gig2->id,
      $this->gig3->id,
      $this->gig4->id
    ]);
  });
});

describe('Show gig details', function () {
  it('shows the gig data on the gig detail page (except the logo)', function () {
    $gig = Gig::factory()->create();
    $response = $this->get("/{$gig->id}");
    $response->assertSeeText($gig->title);
    $response->assertSeeText($gig->company);
    foreach (explode(',', $gig->tags) as $tag) {
      $response->assertSeeText(trim($tag));
    }
    $response->assertSeeText($gig->location);
    $response->assertSee($gig->website);
    $response->assertSee($gig->email);
    $response->assertSeeText($gig->description);
  });

  it('shows the logo on the gig detail page when there is a logo', function () {
    $gig = Gig::factory()->create(['logo' => 'logos/test.jpg']);
    $response = $this->get("/{$gig->id}");
    $response->assertSee('logos/test.jpg');
  });

  it('shows the default logo on the gig detail page when there is no logo', function () {
    $gig = Gig::factory()->create(['logo' => null]);
    $response = $this->get("/{$gig->id}");
    $response->assertSee('images/No_Image_Available.jpg');
  });
});

describe('Redirect unauthenticated users from creating, changing or deleting data', function () {
  it('redirects unauthenticated users away from the create gig page', function () {
    $response = $this->get("/create");
    $response->assertRedirect("/login");
  });

  it('redirects unauthenticated users away from the manage gig page', function () {
    $response = $this->get("/manage");
    $response->assertRedirect("/login");
  });

  it('redirects unauthenticated users away from the store gig page', function () {
    $response = $this->post("/");
    $response->assertRedirect("/login");
  });

  it('redirects unauthenticated users away from the edit gig page', function () {
    $gig = Gig::factory()->create();
    $response = $this->get("/{$gig->id}/edit");
    $response->assertRedirect("/login");
  });

  it('redirects unauthenticated users away from the update gig page', function () {
    $gig = Gig::factory()->create();
    $response = $this->put("/{$gig->id}");
    $response->assertRedirect("/login");
  });

  it('redirects unauthenticated users away from the destroy gig page', function () {
    $gig = Gig::factory()->create();
    $response = $this->delete("/{$gig->id}");
    $response->assertRedirect("/login");
  });

  it('redirects unauthenticated users away from the delete-confirm gig page', function () {
    $gig = Gig::factory()->create();
    $response = $this->get("/{$gig->id}/delete-confirm");
    $response->assertRedirect("/login");
  });
});

describe('Allow authenticated users to create, update, and delete their own data', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
  });

  it('takes authenticated user to create page', function () {
    $response = $this->actingAs($this->user)->get("/create");
    $response->assertStatus(200);
  });

  it('takes authenticated user to manage page', function () {
    $response = $this->actingAs($this->user)->get("/manage");
    $response->assertStatus(200);
  });

  it('takes authenticated user to edit page', function () {
    $gig = Gig::factory()->create(['user_id' => $this->user->id]);
    $response = $this->actingAs($this->user)->get("/{$gig->id}/edit");
    $response->assertStatus(200);
  });

  it('takes authenticated user to delete-confirm page', function () {
    $gig = Gig::factory()->create(['user_id' => $this->user->id]);
    $response = $this->actingAs($this->user)->get("/{$gig->id}/delete-confirm");
    $response->assertStatus(200);
  });
});

describe('Prevent authenticated users from changing or deleting data that they do not own', function () {
  beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->otherUser = User::factory()->create();
    $this->gig = Gig::factory()->create(['user_id' => $this->owner->id]);
  });

  it('denies authenticated users access to edit gig that they do not own', function () {
    $response = $this->actingAs($this->otherUser)->get("/{$this->gig->id}/edit");
    $response->assertStatus(403);
  });

  it('denies authenticated users access to update gig that they do not own', function () {
    $response = $this->actingAs($this->otherUser)->put("/{$this->gig->id}", [
      'title' => 'Senior Laravel Developer',
      'company' => 'Fisheries Canada',
      'location' => 'Halifax, NS',
      'website' => 'http://fisheries.gov.ca',
      'email' => 'hr@fisheries.gov.ca',
      'tags' => 'frontend, backend, Javascript',
      'description' => 'lorem ipsum'
    ]);
    $response->assertStatus(403);
  });

  it('denies authenticated users access to delete gig that they do not own', function () {
    $response = $this->actingAs($this->otherUser)->get("/{$this->gig->id}/delete-confirm");
    $response->assertStatus(403);
  });

  it('denies authenticated users access to destroy gig that they do not own', function () {
    $response = $this->actingAs($this->otherUser)->delete("/{$this->gig->id}");
    $response->assertStatus(403);
  });
});

describe('Test CRUD functionality', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
  });

  it('allows an authenticated user to create a gig wihout a logo', function () {
    $response = $this->actingAs($this->user)->post('/', [
      'title' => 'Senior Laravel Developer',
      'company' => 'Fisheries Canada',
      'location' => 'Halifax, NS',
      'website' => 'http://fisheries.gov.ca',
      'email' => 'hr@fisheries.gov.ca',
      'tags' => 'frontend, backend, Javascript',
      'description' => 'lorem ipsum'
    ]);
    $response->assertRedirect('/');
    $this->assertDatabaseHas('gigs', ['title' => 'Senior Laravel Developer']);
  });

  it('allows an authenticated user to create a gig wih a logo', function () {
    $response = $this->actingAs($this->user)->post('/', [
      'title' => 'Senior Laravel Developer',
      'company' => 'Fisheries Canada',
      'location' => 'Halifax, NS',
      'website' => 'http://fisheries.gov.ca',
      'email' => 'hr@fisheries.gov.ca',
      'tags' => 'frontend, backend, Javascript',
      'logo_url' => 'https://res.cloudinary.com/daufnw5dc/image/upload/something.jpg',
      'description' => 'lorem ipsum'
    ]);
    $response->assertRedirect('/');
    $this->assertDatabaseHas('gigs', ['logo' => 'https://res.cloudinary.com/daufnw5dc/image/upload/something.jpg']);
  });

  it('allows an authenticated user to update their own gig without a logo', function () {
    $gig = Gig::factory()->create(['user_id' => $this->user->id]);
    $response = $this->actingAs($this->user)->put("/{$gig->id}", [
      'title' => 'Intermediate Laravel Developer',
      'company' => 'Fisheries Canada',
      'location' => 'Digby, NS',
      'website' => 'http://fisheries.gov.ca',
      'email' => 'hr@fisheries.gov.ca',
      'tags' => 'fontend, backend, Javascript',
      'description' => 'lorem ipsum'
    ]);
    $response->assertRedirect('/');
    $this->assertDatabaseHas('gigs', ['title' => 'Intermediate Laravel Developer', 'location' => 'Digby, NS']);
  });

  it('allows an authenticated user to update their own gig with a logo', function () {
    $gig = Gig::factory()->create(['user_id' => $this->user->id]);
    $response = $this->actingAs($this->user)->put("/{$gig->id}", [
      'title' => 'Intermediate Laravel Developer',
      'company' => 'Fisheries Canada',
      'location' => 'Digby, NS',
      'website' => 'http://fisheries.gov.ca',
      'email' => 'hr@fisheries.gov.ca',
      'tags' => 'fontend, backend, Javascript',
      'logo_url' => 'https://res.cloudinary.com/daufnw5dc/image/upload/something.jpg',
      'description' => 'lorem ipsum'
    ]);
    $response->assertRedirect('/');
    $this->assertDatabaseHas('gigs', ['logo' => 'https://res.cloudinary.com/daufnw5dc/image/upload/something.jpg']);
  });

  it('allows an authenticated user to update their own gig with a logo which deletes the logo', function () {
    $gig = Gig::factory()->create(['user_id' => $this->user->id]);
    $response = $this->actingAs($this->user)->put("/{$gig->id}", [
      'title' => 'Intermediate Laravel Developer',
      'company' => 'Fisheries Canada',
      'location' => 'Digby, NS',
      'website' => 'http://fisheries.gov.ca',
      'email' => 'hr@fisheries.gov.ca',
      'tags' => 'fontend, backend, Javascript',
      'remove_logo' => '1',
      'description' => 'lorem ipsum'
    ]);
    $response->assertRedirect('/');
    $this->assertDatabaseHas('gigs', ['location' => 'Digby, NS', 'logo' => null]);
  });

  it('allows an authenticated user to delete their own gig', function () {
    $gig = Gig::factory()->create(['user_id' => $this->user->id]);
    $response = $this->actingAs($this->user)->delete("/{$gig->id}");
    $response->assertRedirect('/');
    $this->assertDatabaseMissing('gigs', ['id' => $gig->id]);
  });
});
