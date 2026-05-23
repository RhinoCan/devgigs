<?php

namespace App\Http\Controllers;

use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use App\Models\Gig;

class GigController extends Controller
{
  // Show all gigs
  public function index()
  {
    return view('gigs.index', [
      'gigs' => Gig::latest()->filter(request(['tag', 'search']))->paginate(6)
    ]);
  }

  // Create new gig
  public function create()
  {
    return view('gigs.create');
  }

  // Store new gigs
  public function store(Request $request)
  {
    $formFields = $request->validate([
      'title' => 'required',
      'company' => 'required',
      'location' => 'required',
      'website' => ['required', 'url'],
      'email' => ['required', 'email'],
      'tags' => 'required',
      'description' => 'required'
    ]);

    if ($request->hasFile('logo')) {
      $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
      $result = $cloudinary->uploadApi()->upload(
        $request->file('logo')->getRealPath(),
        ['folder' => 'devgigs/logos']
      );
      $formFields['logo'] = $result['secure_url'];
    }

    $formFields['user_id'] = auth()->id();

    Gig::create($formFields);

    return redirect('/')->with('message', 'Your gig has been added to the database.');
  }

  // Show one gig
  public function show(Gig $gig)
  {
    return view('gigs.show', [
      'gig' => $gig
    ]);
  }

  // Edit one gig
  public function edit(Gig $gig)
  {
    if ($gig->user_id != auth()->id()) {
      abort(403, "You cannot edit gigs you didn't add to the database");
    }
    return view('gigs.edit', ['gig' => $gig]);
  }

// Update one gig
public function update(Request $request, Gig $gig)
{
    if ($gig->user_id != auth()->id()) {
        abort(403, "You cannot edit gigs you didn't add to the database");
    }

    $formFields = $request->validate([
        'title' => 'required',
        'company' => 'required',
        'location' => 'required',
        'website' => ['required', 'url'],
        'email' => ['required', 'email'],
        'tags' => 'required',
        'description' => 'required'
    ]);

    if ($request->hasFile('logo')) {
        if ($gig->logo) {
            // Extract public ID from URL and delete from Cloudinary
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $publicId = pathinfo(parse_url($gig->logo, PHP_URL_PATH), PATHINFO_FILENAME);
            $cloudinary->uploadApi()->destroy('devgigs/logos/' . $publicId);
        }
        $cloudinary = $cloudinary ?? new Cloudinary(env('CLOUDINARY_URL'));
        $result = $cloudinary->uploadApi()->upload(
            $request->file('logo')->getRealPath(),
            ['folder' => 'devgigs/logos']
        );
        $formFields['logo'] = $result['secure_url'];
    } elseif ($request->input('remove_logo') === '1') {
        if ($gig->logo) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $publicId = pathinfo(parse_url($gig->logo, PHP_URL_PATH), PATHINFO_FILENAME);
            $cloudinary->uploadApi()->destroy('devgigs/logos/' . $publicId);
        }
        $formFields['logo'] = null;
    }

    $gig->update($formFields);

    return redirect('/')->with('message', 'Your gig has been updated in the database.');
}

  // Destroy one gig
  public function destroy(Gig $gig)
  {
    if ($gig->user_id != auth()->id()) {
      abort(403, "You cannot delete gigs you didn't add to the database");
    }
    $gig->delete();
    return redirect('/')->with('message', 'Your gig has been deleted from the database.');
  }

  // Manage gigs
  public function manage()
  {
    return view('gigs.manage', ['gigs' => auth()->user()->gigs]);
  }

  // Confirm deletion of one gig
  public function confirmDelete(Gig $gig)
  {
    if ($gig->user_id != auth()->id()) {
      abort(403, "You cannot delete gigs you didn't add to the database");
    }
    return view('gigs.delete-confirm', ['gig' => $gig]);
  }
}
