<?php

namespace App\Http\Controllers;

use App\Http\Resources\TokenResource;
use App\Models\PersonalAccessToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TokenController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', PersonalAccessToken::class);

        return Inertia::render('tokens/index', [
            'tokens' => TokenResource::collection(user()->tokens()->simplePaginate(20)),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', PersonalAccessToken::class);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'ability' => 'required|in:read,write',
        ]);

        $abilities = ['read'];
        if ($request->input('ability') === 'write') {
            $abilities[] = 'write';
        }
        $token = user()->createToken($request->input('name'), $abilities);

        return back()
            ->with('success', __('Api key created.'))
            ->with('data', [
                'token' => $token->plainTextToken,
            ]);
    }

    public function destroy(PersonalAccessToken $token): RedirectResponse
    {
        $this->authorize('delete', $token);

        $token->delete();

        return back()->with('success', __('Token deleted.'));
    }
}
