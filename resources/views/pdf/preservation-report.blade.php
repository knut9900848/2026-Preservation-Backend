<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preservation Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            margin: 15mm 12mm 5mm 12mm;
            size: A4;
        }
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            padding-bottom: 45px;
        }
        .page-break { page-break-before: always; }
        .avoid-break { page-break-inside: avoid; }
        tr {
            page-break-inside: avoid;
        }
        .report-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' },
                        accent: { 50: '#f0fdf4', 500: '#22c55e', 600: '#16a34a' },
                        danger: { 50: '#fef2f2', 500: '#ef4444', 600: '#dc2626' },
                        slate: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-slate-800 text-[11px] leading-tight">

    {{-- ===== HEADER BANNER ===== --}}
    <div class="flex items-center justify-between border-b-[3px] border-primary-600 pb-3 mb-4">
        {{-- Company (IES) Logo --}}
        <div class="flex items-center gap-2">
            @if($setting->ies_logo)
                <img src="{{ public_path('storage/' . $setting->ies_logo) }}" alt="{{ $setting->ies_name }}" style="height: 48px; width: 120px; object-fit: contain;">
            @else
                <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Title --}}
        <div class="text-center">
            <h1 class="text-[18px] font-extrabold text-slate-900 tracking-tight">Preservation Report</h1>
            <div class="text-[10px] text-slate-500 mt-0.5">{{ $checkSheet->activity?->description }} / {{ $checkSheet->activity_code }}</div>
            @if($setting->project_name)
                <div class="text-[9px] text-slate-400 mt-0.5">{{ $setting->project_name }} @if($setting->project_code)({{ $setting->project_code }})@endif</div>
            @endif
        </div>

        {{-- Client Logo --}}
        <div class="flex items-center gap-2">
            @if($setting->client_logo)
                <img src="{{ public_path('storage/' . $setting->client_logo) }}" alt="{{ $setting->client_name }}" style="height: 48px; width: 120px; object-fit: contain;">
            @else
                <div class="w-12 h-12 bg-slate-100 rounded-lg border border-slate-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            @endif
        </div>
    </div>

    {{-- Document Number Badge --}}
    <div class="flex items-center justify-between mb-4">
        <div class="inline-flex items-center bg-primary-50 border border-primary-200 rounded-md px-3 py-1.5">
            <span class="text-[9px] font-semibold text-primary-600 uppercase tracking-wide mr-2">Doc No.</span>
            <span class="text-[12px] font-bold text-primary-800">{{ $checkSheet->sheet_number }}</span>
        </div>
        <div class="flex gap-2">
            @if(!empty($revisionNumber))
            <div class="inline-flex items-center bg-amber-50 border border-amber-200 rounded-md px-2.5 py-1">
                <span class="text-[9px] text-amber-600 mr-1.5">Rev.</span>
                <span class="text-[11px] font-bold text-amber-800">{{ $revisionNumber }}</span>
            </div>
            @endif
            <div class="inline-flex items-center bg-slate-50 border border-slate-200 rounded-md px-2.5 py-1">
                <span class="text-[9px] text-slate-500 mr-1.5">Round</span>
                <span class="text-[11px] font-bold text-slate-800">{{ $checkSheet->current_round }}</span>
            </div>
        </div>
    </div>

    {{-- ===== EQUIPMENT INFORMATION ===== --}}
    <div class="avoid-break mb-4">
        <div class="bg-slate-800 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-t-md">
            Equipment Information
        </div>
        <table class="w-full border-collapse">
            <tbody>
                <tr>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600 w-[18%]">Equipment Name</td>
                    <td class="border border-slate-200 px-3 py-2 font-medium w-[32%]">{{ $equipment->name }}</td>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600 w-[18%]">Tag No.</td>
                    <td class="border border-slate-200 px-3 py-2 font-medium w-[32%]">{{ $equipment->tag_no }}</td>
                </tr>
                <tr>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600">Category</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $equipment->category?->name ?? '-' }}</td>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600">Sub Category</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $equipment->subCategory?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600">Manufacturer</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $equipment->supplier?->name ?? '-' }}</td>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600">Location</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $equipment->currentLocation?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600">Discipline</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $checkSheet->activity?->description ?? '-' }}</td>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600">Frequency</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $checkSheet->frequency ? $checkSheet->frequency . ' Days' : '-' }}</td>
                </tr>
                <tr>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600">Completed Date</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $completedDate ? \Carbon\Carbon::parse($completedDate)->format('d M Y') : '-' }}</td>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600">Due Date</td>
                    <td class="border border-slate-200 px-3 py-2">{{ $checkSheet->due_date?->format('d M Y') ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ===== INSTRUCTION ===== --}}
    @if($checkSheet->instruction)
    <div class="avoid-break mb-4">
        <div class="bg-amber-600 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-t-md flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            Instruction
        </div>
        <div class="border border-slate-200 border-t-0 bg-amber-50 px-3 py-2.5 text-[10px] text-slate-700 leading-relaxed whitespace-pre-line rounded-b-md">{{ $checkSheet->instruction }}</div>
    </div>
    @endif

    {{-- ===== CHECKLIST ===== --}}
    <div class="mb-4">
        <div class="bg-slate-800 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-t-md flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Checklist Items
        </div>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th rowspan="2" class="bg-slate-100 border border-slate-200 px-1 py-2 text-center text-[9px] font-bold text-slate-600 uppercase tracking-wider w-[3%]">No.</th>
                    <th rowspan="2" class="bg-slate-100 border border-slate-200 px-2 py-2 text-left text-[9px] font-bold text-slate-600 uppercase tracking-wider w-[46%]">Description</th>
                    <th class="bg-primary-50 border border-slate-200 px-1 py-1.5 text-center text-[9px] font-bold text-primary-700 uppercase tracking-wider w-[8%]">IES</th>
                    <th colspan="2" class="bg-indigo-50 border border-slate-200 px-1 py-1.5 text-center text-[9px] font-bold text-indigo-700 uppercase tracking-wider w-[16%]">Client</th>
                    <th rowspan="2" class="bg-slate-100 border border-slate-200 px-1 py-2 text-center text-[9px] font-bold text-slate-600 uppercase tracking-wider w-[27%]">Remarks</th>
                </tr>
                <tr>
                    <th class="bg-slate-100 border border-slate-200 px-1 py-1.5 text-center text-[9px] font-bold text-slate-600 uppercase tracking-wider">DONE</th>
                    <th class="bg-slate-100 border border-slate-200 px-1 py-1.5 text-center text-[9px] font-bold text-slate-600 uppercase tracking-wider">ACPT</th>
                    <th class="bg-slate-100 border border-slate-200 px-1 py-1.5 text-center text-[9px] font-bold text-slate-600 uppercase tracking-wider">PUNCH</th>
                </tr>
            </thead>
            <tbody>
                @forelse($checkSheetItems as $index => $item)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                    <td class="border border-slate-200 px-1 py-1.5 text-center text-slate-500 font-medium">{{ $index + 1 }}</td>
                    <td class="border border-slate-200 px-2 py-1.5">{{ $item->description }}</td>
                    <td class="border border-slate-200 px-1 py-1.5 text-center">
                        @switch($item->status)
                            @case(0)
                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-[9px] font-medium bg-slate-100 text-slate-500">Y</span>
                            @break
                            @case(1)
                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-[9px] font-semibold bg-slate-100 text-slate-600">AR</span>
                            @break
                            @case(2)
                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-700">N/A</span>
                            @break
                            @case(3)
                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-[9px] font-bold bg-red-100 text-red-700">H</span>
                            @break
                            @default
                                <span class="text-slate-400">{{ $item->status }}</span>
                        @endswitch
                    </td>
                    <td class="border border-slate-200 px-1 py-1.5 text-center"></td>
                    <td class="border border-slate-200 px-1 py-1.5 text-center"></td>
                    <td class="border border-slate-200 px-1 py-1.5 text-slate-600">{{ $item->remarks ?? '' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border border-slate-200 px-3 py-6 text-center text-slate-400 italic">No checklist items</td>
                </tr>
                @endforelse
                <tr>
                    <td colspan="6" class="border border-slate-200 px-3 py-2">
                        <div class="flex items-center gap-4 text-[9px] text-slate-500">
                            <span class="font-semibold text-slate-600">Legend:</span>
                            <span><span class="inline-flex items-center px-1 py-0.5 rounded-full font-medium bg-slate-100 text-slate-500">Y</span> Yes</span>
                            <span><span class="inline-flex items-center px-1 py-0.5 rounded-full font-semibold bg-slate-100 text-slate-600">AR</span> Action Required</span>
                            <span><span class="inline-flex items-center px-1 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-700">N/A</span> Not Applicable</span>
                            <span><span class="inline-flex items-center px-1 py-0.5 rounded-full font-bold bg-red-100 text-red-700">H</span> Holding</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ===== PHOTOS ===== --}}
    @if($photoGroups->count() > 0)
    <div class="mb-4">
        <div class="bg-slate-800 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-t-md flex items-center gap-1.5 mb-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Photo Documentation
        </div>

        @foreach($photoGroups as $group)
        <div class="avoid-break border border-slate-200 {{ $loop->first ? 'border-t-0' : 'mt-3' }} rounded-b-md overflow-hidden">
            <div class="bg-slate-50 px-3 py-2 border-b border-slate-200">
                <span class="text-[10px] font-bold text-slate-700">{{ $group->description ?? 'Photo Group ' . $loop->iteration }}</span>
            </div>
            <table class="w-full border-collapse">
                <tr>
                    @foreach($group->photos as $photo)
                    <td class="text-center p-3 w-1/2 border border-slate-100 align-top">
                        <div class="bg-slate-50 rounded-md p-2 inline-block">
                            <img src="{{ public_path('storage/' . ($photo->thumbnail_path ?? $photo->path)) }}" alt="{{ $photo->original_filename }}" class="max-w-full rounded" style="max-height: 200px; aspect-ratio: 4/3; object-fit: cover;">
                        </div>
                        <div class="text-[9px] text-slate-400 mt-1.5 truncate">{{ $photo->original_filename }}</div>
                    </td>
                    @if($loop->iteration % 2 == 0 && !$loop->last)
                </tr><tr>
                    @endif
                    @endforeach
                </tr>
            </table>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ===== APPROVAL / SIGN-OFF ===== --}}
    <div class="avoid-break mb-4">
        <div class="bg-slate-800 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-t-md flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Approval &amp; Sign-off
        </div>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="bg-slate-100 border border-slate-200 px-3 py-2 text-center text-[9px] font-bold text-slate-600 uppercase tracking-wider w-[16%]"></th>
                    <th class="bg-primary-50 border border-slate-200 px-3 py-2 text-center text-[9px] font-bold text-primary-700 uppercase tracking-wider w-[28%]">
                        <div class="flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            CARRIED OUT BY (IES)
                        </div>
                    </th>
                    <th class="bg-indigo-50 border border-slate-200 px-3 py-2 text-center text-[9px] font-bold text-indigo-700 uppercase tracking-wider w-[28%]">
                        <div class="flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            APPROVED BY (CPY)
                        </div>
                    </th>
                    <th class="bg-amber-50 border border-slate-200 px-3 py-2 text-center text-[9px] font-bold text-amber-700 uppercase tracking-wider w-[28%]">
                        <div class="flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                            ACCEPTED BY (CLIENT)
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600 text-center">Name</td>
                    <td class="border border-slate-200 px-3 py-2 text-center font-medium">
                        @foreach($technicians as $tech)
                            {{ $tech->name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="border border-slate-200 px-3 py-2 text-center font-medium">
                        @foreach($inspectors as $inspector)
                            {{ $inspector->name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="border border-slate-200 px-3 py-2 text-center font-medium">{{ $checkSheet->reviewer?->name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600 text-center">Date</td>
                    <td class="border border-slate-200 px-3 py-2 text-center text-slate-600">{{ $checkSheet->performed_date?->format('d M Y') ?? '' }}</td>
                    <td class="border border-slate-200 px-3 py-2 text-center text-slate-600">{{ $checkSheet->reviewed_date?->format('d M Y') ?? '' }}</td>
                    <td class="border border-slate-200 px-3 py-2 text-center text-slate-600"></td>
                </tr>
                <tr>
                    <td class="bg-slate-50 border border-slate-200 px-3 py-2 font-semibold text-slate-600 text-center" style="height: 50px;">Signature</td>
                    <td class="border border-slate-200 px-3 py-2"></td>
                    <td class="border border-slate-200 px-3 py-2"></td>
                    <td class="border border-slate-200 px-3 py-2"></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ===== COMMENTS ===== --}}
    <div class="avoid-break mb-4">
        <div class="bg-slate-800 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-t-md flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            Comments
        </div>
        <div class="border border-slate-200 border-t-0 bg-white px-3 py-3 rounded-b-md" style="min-height: 70px;">
        </div>
    </div>

    {{-- ===== FOOTER (fixed on every page) ===== --}}
    <div class="report-footer pt-2 border-t border-slate-200 flex items-center justify-between">
        <div class="text-[8px] text-slate-400">
            Generated by Preservation Management System
        </div>
        <div class="text-[8px] text-slate-400">
            {{ now()->format('d M Y, H:i:s') }}
        </div>
        <div class="text-[8px] text-slate-400">
            Doc No. {{ $checkSheet->sheet_number }}
        </div>
    </div>


</body>
</html>
