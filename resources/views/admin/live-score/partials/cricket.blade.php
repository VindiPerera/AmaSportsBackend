{{--
    Full two-innings cricket match scorer: toss setup, per-team batting
    lineup + bowling figures, ball-by-ball engine (run/wide/no-ball/out,
    over rollover, maidens), innings transition, and winner determination.
    Entirely client-side state (per spec) — but round-trips through the same
    Firestore document (`cricket_score`) the rest of live-score already
    uses, restored via $score, so a page refresh mid-match doesn't lose
    anything. Every scoring action auto-syncs via the Update endpoint;
    Start/Finish reuse the existing MySQL-backed routes.
--}}
<div id="cricket-root">
    <div id="cricket-scorer">
        <div id="cricket-status" class="hidden mb-4 rounded border px-4 py-3 text-sm"></div>

        {{-- ============ SCREEN 1: TOSS SETUP ============ --}}
        <div id="toss-panel" class="bg-white rounded-2xl border border-slate-200/80 p-6 max-w-lg shadow-soft">
            <h2 class="text-base font-extrabold text-slate-900 mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                <span>🪙</span> Toss &amp; Match Initiation
            </h2>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Team A Name</label>
                    <input id="team-a-name" type="text" value="{{ $match->homeTeam->name }}" 
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Team B Name</label>
                    <input id="team-b-name" type="text" value="{{ $match->awayTeam->name }}" 
                           class="w-full rounded-xl bg-slate-50 border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none transition-all">
                </div>
            </div>

            <p class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Toss Winner</p>
            <div class="grid grid-cols-2 gap-3 mb-5">
                <label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-bold text-slate-800 cursor-pointer hover:border-brand-red transition-all">
                    <input type="radio" name="toss-winner" value="home" class="text-brand-red focus:ring-brand-red"> {{ $match->homeTeam->name }}
                </label>
                <label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-bold text-slate-800 cursor-pointer hover:border-brand-red transition-all">
                    <input type="radio" name="toss-winner" value="away" class="text-brand-red focus:ring-brand-red"> {{ $match->awayTeam->name }}
                </label>
            </div>

            <p class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Toss Choice</p>
            <div class="grid grid-cols-2 gap-3 mb-6">
                <label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-bold text-slate-800 cursor-pointer hover:border-brand-red transition-all">
                    <input type="radio" name="toss-choice" value="bat" class="text-brand-red focus:ring-brand-red"> Bat First 🏏
                </label>
                <label class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-bold text-slate-800 cursor-pointer hover:border-brand-red transition-all">
                    <input type="radio" name="toss-choice" value="bowl" class="text-brand-red focus:ring-brand-red"> Bowl First ⚾
                </label>
            </div>

            <button type="button" data-action="start-match" class="w-full rounded-xl bg-brand-red hover:bg-red-700 text-white py-3.5 text-xs font-black uppercase tracking-wider shadow-lg shadow-red-600/25 hover:scale-[1.01] transition-all">
                🚀 Start Match &amp; Activate Live Score Engine
            </button>
        </div>

        {{-- ============ SCREEN 2: MATCH ADMIN ============ --}}
        <div id="match-panel" class="hidden">
            <div id="result-banner" class="hidden mb-5 rounded-2xl border border-amber-300 bg-amber-50/90 px-5 py-4 text-sm font-bold text-amber-900 shadow-soft flex items-center gap-3"></div>

            <div class="flex gap-3 mb-5 max-w-lg">
                <button type="button" id="tab-a-btn" data-action="switch-tab" data-tab="A" class="flex-1 rounded-xl px-4 py-2.5 text-xs font-black uppercase tracking-wider transition-all"></button>
                <button type="button" id="tab-b-btn" data-action="switch-tab" data-tab="B" class="flex-1 rounded-xl px-4 py-2.5 text-xs font-black uppercase tracking-wider transition-all"></button>
            </div>

            <div id="tab-content"></div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="button" id="start-2nd-innings-btn" data-action="start-2nd-innings"
                        class="hidden rounded-xl bg-slate-900 text-white px-6 py-3 text-xs font-black uppercase tracking-wider hover:bg-slate-800 shadow-sm transition-all">
                    Start 2nd Innings
                </button>
                <button type="button" id="finish-match-btn" data-action="finish-match"
                        class="hidden rounded-xl bg-slate-200 text-slate-800 px-6 py-3 text-xs font-black uppercase tracking-wider hover:bg-slate-300 transition-all">
                    Finish Match
                </button>
            </div>
        </div>
    </div>

    {{-- ============ MODALS ============ --}}
    <div id="out-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 max-w-sm w-full shadow-2xl">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-black text-sm">✕</span>
                <div>
                    <h3 class="text-sm font-black text-slate-900">Wicket Down!</h3>
                    <p class="text-xs font-semibold text-slate-500">Select the next incoming batter</p>
                </div>
            </div>
            <ul id="out-modal-list" class="divide-y divide-slate-100 max-h-64 overflow-y-auto mb-4 -mx-2 px-2"></ul>
            <button type="button" data-action="cancel-out" class="w-full rounded-xl bg-slate-100 text-slate-700 py-2.5 text-xs font-bold hover:bg-slate-200 transition-colors">
                Cancel Wicket Action
            </button>
        </div>
    </div>

    <div id="add-player-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 max-w-sm w-full shadow-2xl">
            <h3 class="text-base font-black text-slate-900 mb-1">Add Player to Lineup</h3>
            <p class="text-xs font-semibold text-slate-500 mb-4">Enter player name to append to the match squad.</p>
            <input id="add-player-name" type="text" placeholder="Full name (e.g. A. Jayasinghe)" 
                   class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none mb-4">
            <div class="flex gap-2.5">
                <button type="button" data-action="confirm-add-player" class="flex-1 rounded-xl bg-brand-red hover:bg-red-700 text-white py-2.5 text-xs font-black uppercase tracking-wider transition-all">Add to Squad</button>
                <button type="button" data-action="cancel-add-player" class="flex-1 rounded-xl bg-slate-100 text-slate-700 py-2.5 text-xs font-bold hover:bg-slate-200 transition-all">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const HOME_NAME = @json($match->homeTeam->name);
    const AWAY_NAME = @json($match->awayTeam->name);
    const HOME_ROSTER = @json($homeRosterNames);
    const AWAY_ROSTER = @json($awayRosterNames);
    const MATCH_STATUS = @json($match->status);
    const START_URL = @json(route('admin.live-score.start', $match));
    const UPDATE_URL = @json(route('admin.live-score.update', $match));
    const FINISH_URL = @json(route('admin.live-score.finish', $match));
    const CSRF_TOKEN = @json(csrf_token());
    const RESTORED = @json($score ?: null);

    let state = {};
    let pendingEndOfOver = false;
    let preOutSnapshot = null;
    let addPlayerTargetTeam = 'A';

    // --- Team / state construction -------------------------------------

    function makeTeam(name, rosterNames) {
        const names = rosterNames && rosterNames.length > 0
            ? rosterNames
            : Array.from({ length: 11 }, (_, i) => `Player ${i + 1}`);

        return {
            name,
            batters: names.map((n, i) => ({
                id: i + 1, name: n, status: i < 2 ? 'bat' : 'yet',
                runs: 0, balls: 0, fours: 0, sixes: 0, order: i + 1,
            })),
            bowlers: names.map((n, i) => ({
                id: i + 1, name: n, overs: 0, balls: 0, runs: 0, wickets: 0, maidens: 0,
            })),
            strikerId: names.length > 0 ? 1 : null,
            nonStrikerId: names.length > 1 ? 2 : null,
            runs: 0, wickets: 0, overs: 0, balls: 0,
            currentOver: [],
            currentOverRuns: 0,
            currentBowlerId: names.length > 0 ? 1 : null,
            log: [],
            allOut: false,
            started: false,
        };
    }

    function initState() {
        const hasRich = RESTORED && RESTORED.team_a && RESTORED.team_b;

        if (hasRich) {
            state = {
                tossWinner: RESTORED.toss_winner || null,
                tossChoice: RESTORED.toss_choice || null,
                innings: RESTORED.innings || 1,
                started: true,
                finished: MATCH_STATUS === 'finished',
                activeTab: RESTORED.innings === 2 ? 'B' : 'A',
                teamA: RESTORED.team_a,
                teamB: RESTORED.team_b,
                result: RESTORED.result || null,
            };
        } else {
            state = {
                tossWinner: null, tossChoice: null, innings: 1,
                started: false, finished: MATCH_STATUS === 'finished',
                activeTab: 'A', teamA: null, teamB: null, result: null,
            };
        }
    }

    // --- Helpers ------------------------------------------------------

    function team(key) {
        return key === 'A' ? state.teamA : state.teamB;
    }

    function getBattingTeamKey() {
        return state.innings === 2 ? 'B' : 'A';
    }

    function getBowlingTeamKey() {
        return getBattingTeamKey() === 'A' ? 'B' : 'A';
    }

    function getBattingTeam() {
        return team(getBattingTeamKey());
    }

    function getBatter(t, id) {
        return t.batters.find((b) => b.id === id) || null;
    }

    function getCurrentBowler() {
        const bt = getBattingTeam();
        const bowlingTeam = team(getBowlingTeamKey());
        return bowlingTeam.bowlers.find((b) => b.id === bt.currentBowlerId) || null;
    }

    function canScore() {
        const bt = getBattingTeam();
        return state.started && !state.finished && !state.result && bt && !bt.allOut;
    }

    function addLog(t, text) {
        t.log.unshift(text);
        t.log = t.log.slice(0, 10);
    }

    function swapStrike(t) {
        const tmp = t.strikerId;
        t.strikerId = t.nonStrikerId;
        t.nonStrikerId = tmp;
    }

    /**
     * Manual override — for scenarios the run/over rules don't cover on
     * their own (a mid-over correction, a run-out at the non-striker's end,
     * retired hurt, admin fixing a mis-tap). Just flips ends; doesn't touch
     * runs/balls/overs, so it's safe to use anytime the innings is live.
     */
    function swapStrikeManual() {
        if (!canScore()) return;
        const bt = getBattingTeam();
        if (!bt.strikerId || !bt.nonStrikerId) return;

        const striker = getBatter(bt, bt.strikerId);
        const nonStriker = getBatter(bt, bt.nonStrikerId);
        swapStrike(bt);
        addLog(bt, `${striker.name} and ${nonStriker.name} swapped ends.`);

        afterAction();
    }

    // --- Scoring engine -------------------------------------------------

    function registerLegalBall(bt, bowler, options) {
        options = options || {};
        bt.balls++;
        if (bowler) bowler.balls++;
        if (bt.balls >= 6) {
            if (options.deferEndOfOver) {
                pendingEndOfOver = true;
            } else {
                endOfOver(bt, bowler);
            }
        }
    }

    function endOfOver(bt, bowler) {
        bt.overs++;
        bt.balls = 0;
        if (bowler) {
            if (bt.currentOverRuns === 0) bowler.maidens += 1;
            bowler.overs += 1;
            bowler.balls = 0;
        }
        bt.currentOverRuns = 0;
        bt.currentOver = [];
        swapStrike(bt);
        addLog(bt, `End of over ${bt.overs}. Players swapped.`);
    }

    function afterAction() {
        checkMatchProgress();
        render();
        pushUpdate();
    }

    function checkMatchProgress() {
        if (state.result) return;

        if (state.innings === 2) {
            const target = state.teamA.runs + 1;
            if (state.teamB.runs >= target || state.teamB.allOut) {
                finalizeResult();
            }
        }
    }

    function finalizeResult() {
        const a = state.teamA.runs;
        const b = state.teamB.runs;

        if (b > a) {
            const wicketsLeft = state.teamB.batters.length - 1 - state.teamB.wickets;
            state.result = { winner: 'B', margin: `${state.teamB.name} wins by ${wicketsLeft} wicket${wicketsLeft === 1 ? '' : 's'}` };
        } else if (b < a) {
            state.result = { winner: 'A', margin: `${state.teamA.name} wins by ${a - b} run${(a - b) === 1 ? '' : 's'}` };
        } else {
            state.result = { winner: 'tie', margin: 'Match tied' };
        }

        addLog(state.teamB, state.result.margin);
    }

    function scoreRun(r) {
        if (!canScore()) return;
        const bt = getBattingTeam();
        const striker = getBatter(bt, bt.strikerId);
        const bowler = getCurrentBowler();
        if (!striker) return;

        bt.runs += r;
        striker.runs += r;
        striker.balls += 1;
        if (bowler) bowler.runs += r;
        bt.currentOverRuns += r;
        if (r === 4) striker.fours += 1;
        if (r === 6) striker.sixes += 1;
        bt.currentOver.push(String(r));
        addLog(bt, `${striker.name} scores ${r} run${r === 1 ? '' : 's'}.`);

        if (r % 2 === 1) swapStrike(bt);
        registerLegalBall(bt, bowler);

        afterAction();
    }

    function scoreWide() {
        if (!canScore()) return;
        const bt = getBattingTeam();
        const bowler = getCurrentBowler();

        bt.runs += 1;
        if (bowler) bowler.runs += 1;
        bt.currentOverRuns += 1;
        bt.currentOver.push('W');
        addLog(bt, 'Wide ball. +1 run.');
        // Not a legal ball — balls counter unchanged, no rotation.

        afterAction();
    }

    function scoreNoBall() {
        if (!canScore()) return;
        const bt = getBattingTeam();
        const bowler = getCurrentBowler();

        bt.runs += 1;
        if (bowler) bowler.runs += 1;
        bt.currentOverRuns += 1;
        bt.currentOver.push('NB');
        addLog(bt, 'No ball. +1 run.');

        registerLegalBall(bt, bowler); // IS a legal ball, no rotation.

        afterAction();
    }

    function scoreOut() {
        if (!canScore()) return;
        const bt = getBattingTeam();
        const striker = getBatter(bt, bt.strikerId);
        const bowler = getCurrentBowler();
        if (!striker) return;

        preOutSnapshot = JSON.parse(JSON.stringify(state));

        bt.wickets += 1;
        striker.balls += 1;
        striker.status = 'out';
        if (bowler) bowler.wickets += 1;
        bt.currentOver.push('X');
        addLog(bt, `${striker.name} is OUT! Wicket #${bt.wickets}.`);

        bt.strikerId = bt.nonStrikerId;
        bt.nonStrikerId = null;

        registerLegalBall(bt, bowler, { deferEndOfOver: true });

        const yetPlayers = bt.batters.filter((p) => p.status === 'yet');
        const allOut = bt.wickets >= (bt.batters.length - 1) || yetPlayers.length === 0;

        if (allOut) {
            bt.allOut = true;
            pendingEndOfOver = false;
            addLog(bt, 'All out!');
            preOutSnapshot = null;
            afterAction();
            return;
        }

        render();
        openOutModal(yetPlayers);
    }

    function selectNewBatter(playerId) {
        const bt = getBattingTeam();
        const player = getBatter(bt, playerId);
        if (!player) return;

        player.status = 'bat';
        bt.nonStrikerId = playerId;
        preOutSnapshot = null;
        closeOutModal();

        if (pendingEndOfOver) {
            pendingEndOfOver = false;
            endOfOver(bt, getCurrentBowler());
        }

        afterAction();
    }

    function cancelOut() {
        if (preOutSnapshot) {
            state = preOutSnapshot;
            preOutSnapshot = null;
            pendingEndOfOver = false;
        }
        closeOutModal();
        render();
    }

    function promotePlayer(playerId) {
        const bt = team(state.activeTab);
        const yet = bt.batters.filter((p) => p.status === 'yet').sort((a, b) => a.order - b.order);
        const idx = yet.findIndex((p) => p.id === playerId);
        if (idx <= 0) return;

        const current = yet[idx];
        const prev = yet[idx - 1];
        const tmp = current.order;
        current.order = prev.order;
        prev.order = tmp;

        afterAction();
    }

    function addPlayer(name) {
        name = (name || '').trim();
        if (!name) return;

        const bt = team(addPlayerTargetTeam);
        const maxOrder = bt.batters.reduce((m, p) => Math.max(m, p.order), 0);
        const nextId = bt.batters.reduce((m, p) => Math.max(m, Number(p.id) || 0), 0) + 1;
        bt.batters.push({ id: nextId, name, status: 'yet', runs: 0, balls: 0, fours: 0, sixes: 0, order: maxOrder + 1 });

        const nextBowlerId = bt.bowlers.reduce((m, b) => Math.max(m, Number(b.id) || 0), 0) + 1;
        bt.bowlers.push({ id: nextBowlerId, name, overs: 0, balls: 0, runs: 0, wickets: 0, maidens: 0 });

        closeAddPlayerModal();
        afterAction();
    }

    // --- Toss / match lifecycle -----------------------------------------

    function startMatch() {
        const tossWinnerInput = document.querySelector('input[name="toss-winner"]:checked');
        const tossChoiceInput = document.querySelector('input[name="toss-choice"]:checked');
        if (!tossWinnerInput || !tossChoiceInput) {
            showStatus(false, { message: 'Select a toss winner and choice first.' });
            return;
        }

        const teamAName = document.getElementById('team-a-name').value.trim() || HOME_NAME;
        const teamBName = document.getElementById('team-b-name').value.trim() || AWAY_NAME;
        const tossWinner = tossWinnerInput.value; // 'home' | 'away'
        const tossChoice = tossChoiceInput.value; // 'bat' | 'bowl'

        const homeBatsFirst = (tossWinner === 'home' && tossChoice === 'bat') || (tossWinner === 'away' && tossChoice === 'bowl');

        if (homeBatsFirst) {
            state.teamA = makeTeam(teamAName, HOME_ROSTER);
            state.teamB = makeTeam(teamBName, AWAY_ROSTER);
        } else {
            state.teamA = makeTeam(teamBName, AWAY_ROSTER);
            state.teamB = makeTeam(teamAName, HOME_ROSTER);
        }
        state.teamA.started = true;

        state.tossWinner = tossWinner;
        state.tossChoice = tossChoice;
        state.innings = 1;
        state.started = true;
        state.activeTab = 'A';

        addLog(state.teamA, `${tossWinner === 'home' ? HOME_NAME : AWAY_NAME} won the toss and chose to ${tossChoice}.`);

        render();
        submitLifecycle(START_URL);
    }

    function startSecondInnings() {
        if (state.innings !== 1 || state.result) return;
        state.innings = 2;
        state.teamB.started = true;
        state.activeTab = 'B';
        addLog(state.teamB, `Innings 2 begins. Target: ${state.teamA.runs + 1}.`);
        afterAction();
    }

    function finishMatch() {
        if (!confirm('Finish this match? This is final.')) return;
        submitLifecycle(FINISH_URL);
    }

    function switchTab(key) {
        state.activeTab = key;
        render();
    }

    // --- Backend sync -----------------------------------------------------

    function buildPayload() {
        return {
            team_a_name: state.teamA ? state.teamA.name : null,
            team_b_name: state.teamB ? state.teamB.name : null,
            toss_winner: state.tossWinner,
            toss_choice: state.tossChoice,
            innings: state.innings,
            team_a: state.teamA,
            team_b: state.teamB,
            result: state.result,
            summary: state.result ? state.result.margin : (getBattingTeam() && getBattingTeam().log[0]) || '',
        };
    }

    function showStatus(ok, data) {
        const box = document.getElementById('cricket-status');
        box.classList.remove(
            'hidden', 'border-green-300', 'bg-green-50', 'text-green-800',
            'border-amber-300', 'bg-amber-50', 'text-amber-800',
            'border-red-300', 'bg-red-50', 'text-red-800'
        );

        if (!ok) {
            box.classList.add('border-red-300', 'bg-red-50', 'text-red-800');
            box.textContent = (data && data.message) || 'Save failed.';
        } else if (data && data.firebase_ok === false) {
            box.classList.add('border-amber-300', 'bg-amber-50', 'text-amber-800');
            box.textContent = 'Saved, but Firebase push failed — mobile app may not see this update.';
        } else {
            box.classList.add('border-green-300', 'bg-green-50', 'text-green-800');
            box.textContent = 'Synced.';
            setTimeout(() => box.classList.add('hidden'), 1500);
        }
    }

    async function pushUpdate() {
        try {
            const res = await fetch(UPDATE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ cricket_score: buildPayload() }),
            });
            const data = await res.json().catch(() => ({}));
            showStatus(res.ok, data);
        } catch (e) {
            showStatus(false, { message: 'Network error — could not reach the server.' });
        }
    }

    async function submitLifecycle(url) {
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ cricket_score: buildPayload() }),
            });
            if (res.ok) {
                window.location.href = res.url;
            } else {
                const data = await res.json().catch(() => ({}));
                showStatus(false, data);
            }
        } catch (e) {
            showStatus(false, { message: 'Network error — could not reach the server.' });
        }
    }

    // --- Modals -------------------------------------------------------

    function openOutModal(yetPlayers) {
        const list = document.getElementById('out-modal-list');
        list.innerHTML = yetPlayers
            .slice()
            .sort((a, b) => a.order - b.order)
            .map((p) => `<li><button type="button" class="w-full text-left px-3.5 py-2.5 rounded-xl hover:bg-red-50 hover:text-brand-red text-sm font-bold text-slate-800 transition-colors" data-action="select-batter" data-player-id="${p.id}">🏏 ${escapeHtml(p.name)}</button></li>`)
            .join('');
        document.getElementById('out-modal').classList.remove('hidden');
    }

    function closeOutModal() {
        document.getElementById('out-modal').classList.add('hidden');
    }

    function openAddPlayerModal() {
        addPlayerTargetTeam = state.activeTab;
        document.getElementById('add-player-name').value = '';
        document.getElementById('add-player-modal').classList.remove('hidden');
        document.getElementById('add-player-name').focus();
    }

    function closeAddPlayerModal() {
        document.getElementById('add-player-modal').classList.add('hidden');
    }

    // --- Rendering ------------------------------------------------------

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = String(str == null ? '' : str);
        return div.innerHTML;
    }

    function dotColorClass(code) {
        if (code === '4') return 'bg-blue-600 text-white font-black shadow-xs';
        if (code === '6') return 'bg-emerald-600 text-white font-black shadow-xs';
        if (code === 'W' || code === 'NB') return 'bg-amber-500 text-white font-bold shadow-xs';
        if (code === 'X') return 'bg-brand-red text-white font-black shadow-xs';
        return 'bg-slate-100 text-slate-700 font-bold border border-slate-200';
    }

    function overDotsHtml(bt) {
        const items = bt.currentOver.slice();
        const remaining = Math.max(0, 6 - bt.balls);
        for (let i = 0; i < remaining; i++) items.push(null);

        const dots = items.map((code) => code === null
            ? `<div class="h-8 w-8 rounded-full border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center text-[10px] text-slate-400">·</div>`
            : `<div class="h-8 w-8 rounded-full flex items-center justify-center text-xs ${dotColorClass(code)}">${code}</div>`
        ).join('');

        return `<div class="flex flex-wrap items-center gap-1.5 mt-3">${dots}</div>`;
    }

    function targetBannerHtml() {
        const target = state.teamA.runs + 1;
        const need = Math.max(0, target - state.teamB.runs);
        return `<div class="inline-flex items-center gap-2 mt-3 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-xs font-black text-amber-900">
            <span>🎯 Target: ${target}</span>
            <span class="text-slate-400">•</span>
            <span>Need ${need} run${need === 1 ? '' : 's'} to win</span>
        </div>`;
    }

    function playerCardHtml(player, label, isStriker) {
        if (!player) return '<p class="text-sm font-semibold text-slate-400">—</p>';
        return `
            <div class="flex items-center justify-between gap-2 mb-1.5">
                <span class="text-[10px] font-black uppercase tracking-wider ${isStriker ? 'text-brand-red' : 'text-slate-500'}">${label}</span>
                ${isStriker ? '<span class="w-2 h-2 rounded-full bg-brand-red animate-pulse"></span>' : ''}
            </div>
            <p class="text-base sm:text-lg font-black text-slate-900 truncate">${escapeHtml(player.name)}</p>
            <p class="text-xs font-semibold text-slate-500 mt-0.5"><span class="text-slate-900 font-black">${player.runs}</span> runs <span class="text-slate-400">(${player.balls} b · ${player.fours}×4 · ${player.sixes}×6)</span></p>
        `;
    }

    function logHtml(bt) {
        if (bt.log.length === 0) return '<li class="text-xs font-semibold text-slate-400 py-1">No balls bowled in this innings yet.</li>';
        return bt.log.map((text) => `<li class="text-xs font-medium text-slate-700 py-1 flex items-start gap-2 border-b border-slate-50 last:border-0"><span class="text-slate-300">›</span><span>${escapeHtml(text)}</span></li>`).join('');
    }

    function canPromote(bt, p) {
        if (state.finished || bt.allOut || state.result) return false;
        const yet = bt.batters.filter((pl) => pl.status === 'yet').sort((a, b) => a.order - b.order);
        return yet.length > 0 && yet[0].id !== p.id;
    }

    function badgeClass(label) {
        if (label === 'Striker') return 'bg-red-50 text-brand-red border border-red-200';
        if (label === 'Non-striker') return 'bg-slate-100 text-slate-700 border border-slate-200';
        if (label === 'Out') return 'bg-slate-100 text-slate-400';
        return 'bg-slate-50 text-slate-500';
    }

    function battingTableHtml(bt) {
        const rows = bt.batters.slice().sort((a, b) => a.order - b.order).map((p) => {
            const sr = p.balls > 0 ? ((p.runs / p.balls) * 100).toFixed(1) : '—';
            let label = 'Yet to bat';
            if (p.id === bt.strikerId) label = 'Striker';
            else if (p.id === bt.nonStrikerId) label = 'Non-striker';
            else if (p.status === 'out') label = 'Out';

            const promoteBtn = (p.status === 'yet' && canPromote(bt, p))
                ? `<button type="button" class="text-slate-400 hover:text-brand-red text-xs ml-1 font-bold transition-colors" data-action="promote" data-player-id="${p.id}" title="Promote up order">↑</button>`
                : '';

            const isCurrent = p.id === bt.strikerId || p.id === bt.nonStrikerId;

            return `
                <tr class="${p.id === bt.strikerId ? 'bg-red-50/50' : isCurrent ? 'bg-slate-50/70' : 'hover:bg-slate-50/50'} transition-colors">
                    <td class="px-3.5 py-2.5 text-xs font-bold text-slate-800">${escapeHtml(p.name)}${p.id === bt.strikerId ? ' <span class="text-brand-red font-black">*</span>' : ''}${promoteBtn}</td>
                    <td class="px-3 py-2.5 text-xs font-black text-slate-900 text-center">${p.runs}</td>
                    <td class="px-3 py-2.5 text-xs font-semibold text-slate-600 text-center">${p.balls}</td>
                    <td class="px-3 py-2.5 text-xs font-semibold text-slate-600 text-center">${p.fours}</td>
                    <td class="px-3 py-2.5 text-xs font-semibold text-slate-600 text-center">${p.sixes}</td>
                    <td class="px-3 py-2.5 text-xs font-bold text-slate-700 text-center">${sr}</td>
                    <td class="px-3.5 py-2.5 text-xs"><span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider ${badgeClass(label)}">${label}</span></td>
                </tr>`;
        }).join('');

        return `
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-3.5 py-2.5">Batter</th>
                        <th class="px-3 py-2.5 text-center">R</th>
                        <th class="px-3 py-2.5 text-center">B</th>
                        <th class="px-3 py-2.5 text-center">4s</th>
                        <th class="px-3 py-2.5 text-center">6s</th>
                        <th class="px-3 py-2.5 text-center">SR</th>
                        <th class="px-3.5 py-2.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">${rows}</tbody>
            </table>`;
    }

    function bowlingTableHtml(bowlingTeam, currentBowlerId) {
        const rows = bowlingTeam.bowlers
            .filter((b) => b.balls > 0 || b.overs > 0 || b.id === currentBowlerId)
            .map((b) => {
                const totalOvers = b.overs + b.balls / 6;
                const econ = totalOvers > 0 ? (b.runs / totalOvers).toFixed(2) : '—';
                const isCurrent = b.id === currentBowlerId;
                return `
                    <tr class="${isCurrent ? 'bg-amber-50/50' : 'hover:bg-slate-50/50'} transition-colors">
                        <td class="px-3.5 py-2.5 text-xs font-bold text-slate-800">${escapeHtml(b.name)}${isCurrent ? ' <span class="text-amber-600 font-black">⚾</span>' : ''}</td>
                        <td class="px-3 py-2.5 text-xs font-black text-slate-900 text-center">${b.overs}.${b.balls}</td>
                        <td class="px-3 py-2.5 text-xs font-semibold text-slate-600 text-center">${b.runs}</td>
                        <td class="px-3 py-2.5 text-xs font-black text-brand-red text-center">${b.wickets}</td>
                        <td class="px-3 py-2.5 text-xs font-semibold text-slate-600 text-center">${b.maidens}</td>
                        <td class="px-3 py-2.5 text-xs font-bold text-slate-700 text-center">${econ}</td>
                    </tr>`;
            }).join('');

        return `
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-3.5 py-2.5">Bowler</th>
                        <th class="px-3 py-2.5 text-center">O</th>
                        <th class="px-3 py-2.5 text-center">R</th>
                        <th class="px-3 py-2.5 text-center">W</th>
                        <th class="px-3 py-2.5 text-center">M</th>
                        <th class="px-3 py-2.5 text-center">Econ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">${rows || '<tr><td colspan="6" class="px-3.5 py-4 text-center text-xs font-semibold text-slate-400">No overs bowled yet in this innings.</td></tr>'}</tbody>
            </table>`;
    }

    function renderTabContent() {
        const key = state.activeTab;
        const bt = team(key);
        const bowlingKey = key === 'A' ? 'B' : 'A';
        const bowlingTeam = team(bowlingKey);
        const isCurrentlyBatting = key === getBattingTeamKey();
        const isLive = isCurrentlyBatting && state.started && !state.finished && !state.result && !bt.allOut;

        if (!bt.started) {
            document.getElementById('tab-content').innerHTML = `
                <div class="bg-white rounded-2xl border border-slate-200/80 p-8 text-center text-sm font-semibold text-slate-400 shadow-soft">
                    ⏳ ${escapeHtml(bt.name)} haven't batted yet.
                </div>`;
            return;
        }

        let html = `
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 mb-5 shadow-soft">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-500">${escapeHtml(bt.name)} Innings</p>
                    ${isLive ? '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-50 text-brand-red border border-red-200"><span class="w-1.5 h-1.5 rounded-full bg-brand-red animate-pulse"></span>Live Innings</span>' : '<span class="text-xs font-bold text-slate-400">Innings Concluded</span>'}
                </div>
                <div class="flex flex-wrap items-baseline gap-3 mt-1">
                    <p class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">${bt.runs} <span class="text-brand-red">/</span> ${bt.wickets}</p>
                    <p class="text-sm font-bold text-slate-500">Overs: <span class="text-slate-900 font-extrabold">${bt.overs}.${bt.balls}</span></p>
                </div>
                ${state.innings === 2 && key === 'B' ? targetBannerHtml() : ''}
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">This Over</p>
                    ${overDotsHtml(bt)}
                </div>
            </div>`;

        const striker = getBatter(bt, bt.strikerId);
        const nonStriker = getBatter(bt, bt.nonStrikerId);
        html += `
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 ${isLive ? 'mb-3' : 'mb-5'}">
                <div class="rounded-2xl border-2 border-brand-red bg-red-50/40 p-4 shadow-xs">${playerCardHtml(striker, 'Striker 🏏', true)}</div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">${playerCardHtml(nonStriker, 'Non-Striker', false)}</div>
            </div>`;

        if (isLive && striker && nonStriker) {
            html += `
                <div class="flex justify-center mb-5">
                    <button type="button" data-action="swap-strike" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-black uppercase tracking-wider text-slate-700 hover:border-slate-300 hover:bg-slate-50 shadow-xs transition-all">
                        ⇄ Swap Striker / Non-Striker
                    </button>
                </div>`;
        }

        if (bt.allOut) {
            html += `<div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-red-800">All out — ${escapeHtml(bt.name)}'s innings is complete.</div>`;
        }

        if (isLive) {
            html += `
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 mb-5 shadow-soft">
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">Current Bowler (${escapeHtml(bowlingTeam.name)})</label>
                    <select id="bowler-select" class="w-full rounded-xl bg-slate-50 border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-800 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 outline-none mb-5">
                        ${bowlingTeam.bowlers.map((b) => `<option value="${b.id}" ${b.id === bt.currentBowlerId ? 'selected' : ''}>${escapeHtml(b.name)}</option>`).join('')}
                    </select>

                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Runs Off Ball</p>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-4">
                        ${[0, 1, 2, 3, 4, 6].map((r) => `
                            <button type="button" data-action="run" data-run="${r}" 
                                    class="rounded-xl py-3.5 text-lg font-black transition-all ${
                                        r === 4 ? 'bg-blue-600 hover:bg-blue-500 text-white shadow-sm' :
                                        r === 6 ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-sm' :
                                        'bg-slate-100 hover:bg-slate-200 text-slate-800'
                                    }">
                                ${r}
                            </button>
                        `).join('')}
                    </div>

                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-500 mb-2">Extras &amp; Wickets</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" data-action="wide" class="rounded-xl bg-amber-500 hover:bg-amber-600 text-white py-3 text-xs font-black uppercase tracking-wider shadow-sm transition-all">Wide (+1)</button>
                        <button type="button" data-action="noball" class="rounded-xl bg-amber-500 hover:bg-amber-600 text-white py-3 text-xs font-black uppercase tracking-wider shadow-sm transition-all">No Ball (+1)</button>
                        <button type="button" data-action="out" class="rounded-xl bg-brand-red hover:bg-red-700 text-white py-3 text-xs font-black uppercase tracking-wider shadow-md shadow-red-600/25 transition-all">Wicket (Out!)</button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 mb-5 shadow-soft">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-2">
                        <span>📋</span> Ball-By-Ball Activity Log
                    </h3>
                    <ul class="space-y-0.5 max-h-48 overflow-y-auto">${logHtml(bt)}</ul>
                </div>`;
        }

        html += `
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden mb-5">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Batting Card — ${escapeHtml(bt.name)}</h3>
                    ${!state.finished && !state.result ? `<button type="button" data-action="add-player" class="text-xs font-bold text-brand-red hover:underline">+ Add Batter</button>` : ''}
                </div>
                <div class="overflow-x-auto">${battingTableHtml(bt)}</div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Bowling Card — ${escapeHtml(bowlingTeam.name)}</h3>
                </div>
                <div class="overflow-x-auto">${bowlingTableHtml(bowlingTeam, isCurrentlyBatting ? bt.currentBowlerId : null)}</div>
            </div>`;

        document.getElementById('tab-content').innerHTML = html;
    }

    function render() {
        const tossPanel = document.getElementById('toss-panel');
        const matchPanel = document.getElementById('match-panel');

        if (!state.started) {
            tossPanel.classList.remove('hidden');
            matchPanel.classList.add('hidden');
            return;
        }

        tossPanel.classList.add('hidden');
        matchPanel.classList.remove('hidden');

        const tabA = document.getElementById('tab-a-btn');
        const tabB = document.getElementById('tab-b-btn');
        tabA.textContent = `${state.teamA.name} Innings`;
        tabB.textContent = `${state.teamB.name} Innings`;
        tabA.className = 'flex-1 rounded-xl px-4 py-2.5 text-xs font-black uppercase tracking-wider transition-all ' + 
            (state.activeTab === 'A' ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50');
        tabB.className = 'flex-1 rounded-xl px-4 py-2.5 text-xs font-black uppercase tracking-wider transition-all ' + 
            (state.activeTab === 'B' ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50');

        renderTabContent();

        const resultBanner = document.getElementById('result-banner');
        if (state.result) {
            resultBanner.classList.remove('hidden');
            resultBanner.innerHTML = `<span class="text-lg">🏆</span><div><p class="font-extrabold text-amber-900">${escapeHtml(state.result.margin)}</p><p class="text-xs text-amber-700 font-semibold">Match concluded.</p></div>`;
        } else {
            resultBanner.classList.add('hidden');
        }

        document.getElementById('start-2nd-innings-btn').classList.toggle('hidden', !(state.innings === 1 && !state.result && !state.finished));
        document.getElementById('finish-match-btn').classList.toggle('hidden', !state.result || state.finished);
    }

    // --- Wire up (event delegation — content is rebuilt via innerHTML) ----

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        const action = btn.dataset.action;

        if (action === 'start-match') startMatch();
        else if (action === 'switch-tab') switchTab(btn.dataset.tab);
        else if (action === 'run') scoreRun(Number(btn.dataset.run));
        else if (action === 'wide') scoreWide();
        else if (action === 'noball') scoreNoBall();
        else if (action === 'out') scoreOut();
        else if (action === 'swap-strike') swapStrikeManual();
        else if (action === 'promote') promotePlayer(Number(btn.dataset.playerId));
        else if (action === 'add-player') openAddPlayerModal();
        else if (action === 'confirm-add-player') addPlayer(document.getElementById('add-player-name').value);
        else if (action === 'cancel-add-player') closeAddPlayerModal();
        else if (action === 'select-batter') selectNewBatter(Number(btn.dataset.playerId));
        else if (action === 'cancel-out') cancelOut();
        else if (action === 'start-2nd-innings') startSecondInnings();
        else if (action === 'finish-match') finishMatch();
    });

    document.addEventListener('change', (e) => {
        if (e.target.id === 'bowler-select') {
            getBattingTeam().currentBowlerId = Number(e.target.value);
            afterAction();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && e.target.id === 'add-player-name') {
            addPlayer(e.target.value);
        }
    });

    initState();
    render();
})();
</script>
