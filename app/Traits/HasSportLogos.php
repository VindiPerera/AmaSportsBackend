<?php

namespace App\Traits;

use App\Models\Player;
use App\Models\PlayerCollegeLogo;
use App\Models\PlayerTeam;
use App\Models\PlayerTeamLogo;
use App\Models\Sport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait HasSportLogos
{
    /**
     * Attaches team_names, team_logos, and college_logo_url strictly scoped
     * to the given sport (preventing cross-sport data bleeding).
     */
    protected function attachSportLogos(Player $player, Sport $sport, Model $profile): void
    {
        $profile->team_names = PlayerTeam::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->pluck('team_name')
            ->all();

        $profile->team_logos = PlayerTeamLogo::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->get()
            ->map(fn (PlayerTeamLogo $logo) => [
                'team_name' => $logo->team_name,
                'logo_url' => Storage::disk('public')->url($logo->logo_path),
            ])
            ->all();

        $collegeLogo = PlayerCollegeLogo::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->first();

        $profile->college_logo_url = $collegeLogo && $collegeLogo->logo_path
            ? Storage::disk('public')->url($collegeLogo->logo_path)
            : (! empty($profile->college_logo_path) ? Storage::disk('public')->url($profile->college_logo_path) : null);
    }
}
