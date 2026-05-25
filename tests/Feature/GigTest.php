<?php

use App\Models\Gig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Page loading', function () {
  it('shows the gigs index page', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
  });

  it('shows a single gig', function () {
    $gig = Gig::factory()->create();
    $response = $this->get("/{$gig->id}");
    $response->assertStatus(200);
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
  it('denies authenticated user access to edit gig that they do not own', function () {
    $response = $this->actingAs($this->otherUser)->get("/{$this->gig->id}/edit");
    $response->assertStatus(403);
  });

  it('denies authenticated user access to delete gig that they do not own', function () {
    $response = $this->actingAs($this->otherUser)->get("/{$this->gig->id}/delete-confirm");
    $response->assertStatus(403);
  });
});

describe('Test CRUD functionality', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
  });
  it('allows an authenticated user to create a gig', function () {
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
  it('allows an authenticated user to update their own gig', function () {
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
    $this->assertDatabaseHas('gigs', ['title' =>'Intermediate Laravel Developer', 'location' => 'Digby, NS']);
  });
  it('allows an authenticated user to delete their own gig', function () {
    $gig = Gig::factory()->create(['user_id' => $this->user->id]);
    $response = $this->actingAs($this->user)->delete("/{$gig->id}");
    $response->assertRedirect('/');
    $this->assertDatabaseMissing('gigs', ['id' => $gig->id]);
  });
});
