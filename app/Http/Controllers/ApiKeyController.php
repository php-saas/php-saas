<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiKeyResource;
use App\Models\PersonalAccessToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApiKeyController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', PersonalAccessToken::class);

        return Inertia::render('api-keys/index', [
            'apiKeys' => ApiKeyResource::collection(user()->tokens()->simplePaginate(20))
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

    public function destroy(PersonalAccessToken $apiKey): RedirectResponse
    {
        $this->authorize('delete', $apiKey);

        $apiKey->delete();

        return back()->with('success', __('Api Key deleted.'));
    }
}
