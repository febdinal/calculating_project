<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Project;
use App\Models\Quotation;
use App\Services\CalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        protected CalculatorService $calculatorService
    ) {}

    /**
     * Store a new project and freeze price snapshots.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'name' => ['required', 'string', 'max:255'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_company' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'mode' => ['nullable', 'string', 'in:save,quote'],
            'features' => ['nullable', 'array'],
            'features.*.feature_id' => ['required', 'exists:features,id'],
            'features.*.complexity' => ['nullable', 'string', 'in:basic,standard,advanced,custom'],
            'features.*.quantity' => ['nullable', 'integer', 'min:1'],
            'addons' => ['nullable', 'array'],
            'addons.*.addon_id' => ['required', 'exists:addons,id'],
            'addons.*.quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $selectedFeatures = $validated['features'] ?? [];
        $selectedAddons = $validated['addons'] ?? [];
        $isQuote = ($validated['mode'] ?? 'save') === 'quote';

        // 1. Create Base Project Record
        $project = new Project;
        $project->user_id = Auth::id(); // null if guest
        $project->package_id = $package->id;
        $project->name = $validated['name'];
        $project->customer_name = $validated['customer_name'];
        $project->customer_email = $validated['customer_email'];
        $project->customer_phone = $validated['customer_phone'];
        $project->customer_company = $validated['customer_company'] ?? null;
        $project->notes = $validated['notes'] ?? null;
        $project->status = $isQuote ? 'pending' : 'draft';
        $project->save();

        // 2. Freeze Snapshots via CalculatorService
        $this->calculatorService->saveProjectConfiguration(
            $project,
            $package,
            $selectedFeatures,
            $selectedAddons
        );

        // 3. Create Quotation if requested
        if ($isQuote) {
            Quotation::create([
                'project_id' => $project->id,
                'quotation_number' => Quotation::generateQuotationNumber(),
                'issued_at' => now(),
                'valid_until' => now()->addDays(30),
                'status' => 'sent',
                'terms_conditions' => "1. Penawaran harga bersifat tetap selama 30 hari kalender sejak tanggal diterbitkan.\n2. Biaya paket sudah termasuk infrastruktur dasar (Hosting/VPS, Domain, SSL, Backup Otomatis, dan Pemeliharaan Teknis).\n3. Layanan menggunakan model sewa tahunan dengan perpanjangan berkala.\n4. Pembayaran dapat dilakukan setelah penandatanganan konfirmasi kesepakatan.",
            ]);
        }

        // Store project ID in session for guest tracking
        $savedIds = session()->get('user_project_ids', []);
        $savedIds[] = $project->id;
        session()->put('user_project_ids', array_unique($savedIds));

        $redirectUrl = route('projects.show', $project);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $isQuote ? 'Quotation berhasil diajukan!' : 'Konfigurasi proyek berhasil disimpan!',
                'redirect_url' => $redirectUrl,
                'project_id' => $project->id,
            ]);
        }

        return redirect()->to($redirectUrl)
            ->with('success', $isQuote ? 'Permohonan Quotation berhasil diajukan!' : 'Konfigurasi proyek berhasil disimpan.');
    }

    /**
     * Customer view for a saved project configuration (Sanitized, NO cost data).
     */
    public function show(Project $project): View
    {
        $project->load([
            'package',
            'quotation',
            'projectFeatures' => function ($q) {
                $q->orderBy('category_name')->orderBy('feature_name');
            },
            'projectAddons',
        ]);

        return view('projects.show', compact('project'));
    }

    /**
     * Convert draft project to quotation request.
     */
    public function requestQuotation(Project $project): RedirectResponse
    {
        if ($project->status === 'draft') {
            $project->status = 'pending';
            $project->save();
        }

        if (! $project->quotation) {
            Quotation::create([
                'project_id' => $project->id,
                'quotation_number' => Quotation::generateQuotationNumber(),
                'issued_at' => now(),
                'valid_until' => now()->addDays(30),
                'status' => 'sent',
                'terms_conditions' => "1. Penawaran harga bersifat tetap selama 30 hari kalender sejak tanggal diterbitkan.\n2. Biaya paket sudah termasuk infrastruktur dasar (Hosting/VPS, Domain, SSL, Backup Otomatis, dan Pemeliharaan Teknis).\n3. Layanan menggunakan model sewa tahunan dengan perpanjangan berkala.\n4. Pembayaran dapat dilakukan setelah penandatanganan konfirmasi kesepakatan.",
            ]);
        }

        return back()->with('success', 'Permohonan Quotation resmi berhasil diajukan ke tim kami!');
    }

    /**
     * Stream / Download Formal Quotation PDF.
     */
    public function pdf(Project $project): Response
    {
        $project->load([
            'package',
            'quotation',
            'projectFeatures' => function ($q) {
                $q->orderBy('category_name')->orderBy('feature_name');
            },
            'projectAddons',
        ]);

        $pdf = Pdf::loadView('projects.pdf', compact('project'))
            ->setPaper('a4', 'portrait');

        $quotationNumber = $project->quotation?->quotation_number ?? 'QUO-'.$project->id;
        $fileName = "{$quotationNumber}-{$project->name}.pdf";

        return $pdf->stream($fileName);
    }

    /**
     * Printable HTML Quotation View (with @media print styling).
     */
    public function printView(Project $project): View
    {
        $project->load([
            'package',
            'quotation',
            'projectFeatures' => function ($q) {
                $q->orderBy('category_name')->orderBy('feature_name');
            },
            'projectAddons',
        ]);

        return view('projects.pdf', compact('project'));
    }

    /**
     * User's Project History.
     */
    public function myProjects(): View
    {
        $sessionIds = session()->get('user_project_ids', []);
        $userId = Auth::id();

        $projects = Project::where(function ($q) use ($userId, $sessionIds) {
            if ($userId) {
                $q->where('user_id', $userId);
            }
            if (! empty($sessionIds)) {
                $q->orWhereIn('id', $sessionIds);
            }
        })
            ->with(['package', 'quotation'])
            ->withCount(['projectFeatures', 'projectAddons'])
            ->latest()
            ->get();

        return view('projects.my_projects', compact('projects'));
    }
}
