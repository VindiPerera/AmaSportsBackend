<?php

namespace App\Services;

use App\Models\GameMatch;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Pushes live-score snapshots to Firestore's `live_scores` collection (the
 * same one sport-mobile/src/services/firebaseService.ts listens to) via the
 * plain REST API — deliberately not kreait/firebase-php + google/cloud-
 * firestore, which hard-requires the ext-grpc PHP extension that isn't
 * installed here. `google/auth` mints a service-account OAuth2 token; the
 * write itself is a plain authenticated HTTP PATCH via Laravel's Http
 * facade.
 *
 * Every public method fails soft: it logs a warning and returns false
 * instead of throwing, so a missing/broken Firebase config never blocks the
 * MySQL save the admin panel actually depends on.
 */
class FirebaseLiveScoreService
{
    private const SCOPE = 'https://www.googleapis.com/auth/datastore';

    private const COLLECTION = 'live_scores';

    public function isConfigured(): bool
    {
        $projectId = config('firebase.project_id');
        $credentialsPath = config('firebase.credentials_path');

        return filled($projectId) && filled($credentialsPath) && is_readable($credentialsPath);
    }

    /** Full snapshot overwrite — used on Start. */
    public function pushSnapshot(GameMatch $match, array $payload): bool
    {
        return $this->write($match, $payload, merge: false);
    }

    /** Partial merge (only the given top-level keys are replaced) — used on Update. */
    public function mergeUpdate(GameMatch $match, array $payload): bool
    {
        return $this->write($match, $payload, merge: true);
    }

    /** Final push — used on Finish, so any live mobile listeners settle. */
    public function markFinished(GameMatch $match, array $finalPayload): bool
    {
        return $this->mergeUpdate($match, array_merge($finalPayload, [
            'status' => 'finished',
            'last_updated' => now()->toISOString(),
        ]));
    }

    /**
     * Read back the current live document. Used by the admin Live Score
     * page on load: while a match is live, Update writes go to Firestore
     * only (not MySQL — see `matches.live_score`, which stays null until
     * Finish), so this is the only place the admin's current score
     * actually lives. Without this, navigating away from the page and back
     * mid-match would show a blank form even though the match is still
     * genuinely live.
     *
     * @return array<string, mixed>|null null if unconfigured, not found, or the request fails.
     */
    public function getSnapshot(GameMatch $match): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $token = $this->accessToken();
            $projectId = config('firebase.project_id');
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/".self::COLLECTION."/{$match->id}";

            $response = Http::withToken($token)->timeout(5)->get($url);

            if ($response->status() === 404) {
                return null;
            }

            if ($response->failed()) {
                Log::warning('Firestore fetch failed.', [
                    'match_id' => $match->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $this->decodeMap($response->json('fields', []));
        } catch (Throwable $e) {
            Log::warning('Firestore fetch threw.', ['match_id' => $match->id, 'message' => $e->getMessage()]);

            return null;
        }
    }

    private function write(GameMatch $match, array $payload, bool $merge): bool
    {
        if (! $this->isConfigured()) {
            Log::warning('Firestore push skipped — not configured.', ['match_id' => $match->id]);

            return false;
        }

        try {
            $token = $this->accessToken();
            $projectId = config('firebase.project_id');
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/".self::COLLECTION."/{$match->id}";

            if ($merge) {
                $mask = collect(array_keys($payload))
                    ->map(fn (string $field) => 'updateMask.fieldPaths='.urlencode($field))
                    ->implode('&');
                $url .= '?'.$mask;
            }

            $response = Http::withToken($token)->timeout(5)->patch($url, [
                'fields' => $this->encodeMap($payload),
            ]);

            if ($response->failed()) {
                Log::warning('Firestore push failed.', [
                    'match_id' => $match->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (Throwable $e) {
            Log::warning('Firestore push threw.', ['match_id' => $match->id, 'message' => $e->getMessage()]);

            return false;
        }
    }

    private function accessToken(): string
    {
        return Cache::remember('firebase_access_token', now()->addMinutes(50), function () {
            $credentials = new ServiceAccountCredentials(self::SCOPE, config('firebase.credentials_path'));
            $token = $credentials->fetchAuthToken();

            return $token['access_token'];
        });
    }

    /**
     * @param  array<string, mixed>  $map
     * @return array<string, mixed>
     */
    private function encodeMap(array $map): array
    {
        $fields = [];
        foreach ($map as $key => $value) {
            $fields[$key] = $this->encodeValue($value);
        }

        return $fields;
    }

    /**
     * Encode a plain PHP value into Firestore's typed REST value format.
     *
     * @return array<string, mixed>
     */
    private function encodeValue(mixed $value): array
    {
        return match (true) {
            is_null($value) => ['nullValue' => null],
            is_bool($value) => ['booleanValue' => $value],
            is_int($value) => ['integerValue' => (string) $value],
            is_float($value) => ['doubleValue' => $value],
            is_array($value) && array_is_list($value) => [
                'arrayValue' => ['values' => array_map(fn ($v) => $this->encodeValue($v), $value)],
            ],
            is_array($value) => ['mapValue' => ['fields' => $this->encodeMap($value)]],
            default => ['stringValue' => (string) $value],
        };
    }

    /**
     * @param  array<string, mixed>  $fields
     * @return array<string, mixed>
     */
    private function decodeMap(array $fields): array
    {
        $out = [];
        foreach ($fields as $key => $value) {
            $out[$key] = $this->decodeValue($value);
        }

        return $out;
    }

    /** Decode a single Firestore typed REST value back into a plain PHP value. */
    private function decodeValue(array $value): mixed
    {
        return match (true) {
            array_key_exists('nullValue', $value) => null,
            array_key_exists('booleanValue', $value) => $value['booleanValue'],
            array_key_exists('integerValue', $value) => (int) $value['integerValue'],
            array_key_exists('doubleValue', $value) => (float) $value['doubleValue'],
            array_key_exists('stringValue', $value) => $value['stringValue'],
            array_key_exists('arrayValue', $value) => array_map(
                fn ($item) => $this->decodeValue($item),
                $value['arrayValue']['values'] ?? []
            ),
            array_key_exists('mapValue', $value) => $this->decodeMap($value['mapValue']['fields'] ?? []),
            default => null,
        };
    }
}
