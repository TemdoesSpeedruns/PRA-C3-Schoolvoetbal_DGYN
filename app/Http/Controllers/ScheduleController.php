<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Field;
use App\Models\Referee;
use App\Services\SchedulePlanningService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    protected SchedulePlanningService $schedulingService;

    public function __construct(SchedulePlanningService $schedulingService)
    {
        $this->middleware(['auth', 'admin']);
        $this->schedulingService = $schedulingService;
    }

    /**
     * Toon schedule planning interface
     */
    public function index(Request $request): View
    {
        $query = GameMatch::with(['tournament', 'homeSchool', 'awaySchool', 'field', 'referee']);

        // Filter op status
        $status = $request->query('status', 'all');
        switch ($status) {
            case 'unscheduled':
                $query->whereNull('scheduled_time');
                break;
            case 'scheduled':
                $query->whereNotNull('scheduled_time');
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            // 'all' - geen filter
        }

        $matches = $query->orderBy('scheduled_time', 'asc')->get();

        $fields = Field::where('is_active', true)->get();
        $referees = Referee::where('is_active', true)->get();

        return view('admin.schedule.index', compact('matches', 'fields', 'referees'));
    }

    /**
     * Plan een wedstrijd in
     */
    public function schedule(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'referee_id' => 'nullable|exists:referees,id',
            'scheduled_time' => 'required|date_format:Y-m-d H:i',
        ]);

        $field = Field::find($validated['field_id']);
        $referee = $validated['referee_id'] ? Referee::find($validated['referee_id']) : null;
        $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['scheduled_time']);

        $result = $this->schedulingService->scheduleMatch($match, $field, $referee, $startTime);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Verwijder planning van wedstrijd
     */
    public function unschedule(GameMatch $match)
    {
        $match->update([
            'scheduled_time' => null,
            'field_id' => null,
            'referee_id' => null,
            'status' => 'scheduled',
        ]);

        return back()->with('success', 'Wedstrijd planning verwijderd.');
    }

    /**
     * Beheer velden
     */
    public function fieldsIndex(): View
    {
        $fields = Field::all();
        return view('admin.schedule.fields.index', compact('fields'));
    }

    /**
     * Maak nieuw veld aan
     */
    public function fieldStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:fields,name',
            'type' => 'required|in:voetbal,lijnbal',
            'capacity' => 'required|integer|min:1',
        ]);

        Field::create($validated);
        return back()->with('success', 'Veld aangemaakt!');
    }

    /**
     * Beheer scheidsrechters
     */
    public function refereesIndex(): View
    {
        $referees = Referee::with('school')->get();
        $schools = \App\Models\School::where('status', 'approved')->get();
        return view('admin.schedule.referees.index', compact('referees', 'schools'));
    }

    /**
     * Voeg scheidsrechter toe
     */
    public function refereeStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email|unique:referees,email',
            'type' => 'required|in:school,external',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        Referee::create($validated);
        return back()->with('success', 'Scheidsrechter toegevoegd!');
    }
}
