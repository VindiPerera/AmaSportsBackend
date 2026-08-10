<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firestore Live Score Push
    |--------------------------------------------------------------------------
    |
    | Server-side credentials for FirebaseLiveScoreService, which pushes
    | admin-panel live score updates to the same Firestore project the
    | mobile app listens to (sport-mobile/src/services/firebaseService.ts,
    | collection `live_scores`, doc id = match id).
    |
    | Both are blank by default. FirebaseLiveScoreService::isConfigured()
    | checks these before attempting a push and fails soft (logs + returns
    | false) when unset, so the rest of the admin panel works without them.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID'),

    // Path to a Firebase service-account JSON key. Defaults under the
    // `local` disk's root (storage/app/private) so it's never web-served.
    'credentials_path' => env('FIREBASE_CREDENTIALS_PATH', storage_path('app/private/firebase-service-account.json')),

];
