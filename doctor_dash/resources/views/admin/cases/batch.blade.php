@extends('layouts.admin')

@section('title', 'Case Collection - ' . $title)
@section('header', 'Case Collection')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    
    @if(session('success'))
        <div class="mb-6 bg-green-500/10 border border-green-500/20 rounded-xl p-4">
            <p class="text-green-400 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif
    
    <div class="mb-5 flex items-center justify-between px-1">
        <a href="{{ route('admin.cases.index') }}" class="text-sm text-gray-400 hover:text-white flex items-center gap-2 font-medium transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to All Cases
        </a>
    </div>

    @php $firstReport = $reports->first(); @endphp
    <!-- Case Information Card (Redesigned) -->
    <div class="bg-[#111111] rounded-[24px] border border-white/10 p-6 md:p-10 mb-8 shadow-2xl relative overflow-hidden group">
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
                <div>
                    <h3 class="text-xl font-black text-[#FACC15] tracking-tight uppercase">Case Information</h3>
                    <p class="text-[10px] text-slate-500 tracking-widest mt-1">MAIN DETAILS & CLINICAL CONTEXT</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.cases.downloadBatch', $batch_id) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#FACC15] border border-[#FACC15] text-[11px] font-black text-black hover:bg-[#FACC15]/90 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-yellow-400/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        {{ $reports->count() === 1 ? 'SAVE FILE' : 'SAVE ALL FILES' }}
                    </a>
                </div>
            </div>
            
            <div class="space-y-16">
                <div>
                    <h4 class="text-[12px] font-black text-[#FACC15] uppercase tracking-[0.2em] mb-1">CASE TITLE</h4>
                    <p class="text-white text-2xl font-medium leading-relaxed max-w-4xl whitespace-pre-wrap mb-3">{{ $title }}</p>
                </div>
                
                <div>
                    <h4 class="text-[12px] font-black text-[#FACC15] uppercase tracking-[0.2em] mb-1">DESCRIPTION</h4>
                    @if($firstReport && $firstReport->description)
                        <p class="text-white text-1xl font-medium leading-relaxed max-w-4xl whitespace-pre-wrap mb-3">{{ $firstReport->description }}</p>
                    @else
                        <p class="text-slate-600 italic font-medium mb-10">No description provided for this case.</p>
                    @endif
                </div>

                <div class="py-6 border-t border-white/5 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-12">
                    
                  

                  

                    @if($firstReport->implant_brand)
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">IMPLANT SYSTEM</h4>
                        <span class="text-white text-xs font-bold uppercase tracking-wider">{{ $firstReport->implant_brand }}</span>
                    </div>
                    @endif

                    @if(isset($firstReport->clinical_data['gender']))
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">GENDER</h4>
                        <span class="text-white text-xs font-bold uppercase tracking-wider">{{ $firstReport->clinical_data['gender'] }}</span>
                    </div>
                    @endif

                    @if(isset($firstReport->clinical_data['age']))
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">AGE</h4>
                        <span class="text-white text-xs font-bold uppercase tracking-wider">{{ $firstReport->clinical_data['age'] }}</span>
                    </div>
                    @endif

                    @if(!empty($firstReport->clinical_data['services']))
                    <div class="md:col-span-2 lg:col-span-3">
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3">SERVICES NEEDED</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($firstReport->clinical_data['services'] as $service)
                                <span class="text-[#FACC15] text-[10px] font-black uppercase tracking-widest bg-[#FACC15]/10 px-3 py-1.5 rounded-lg border border-[#FACC15]/20">
                                    {{ $service }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @elseif(isset($firstReport->clinical_data['service_package']))
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">PACKAGE</h4>
                        <span class="text-[#FACC15] text-xs font-black uppercase tracking-widest bg-[#FACC15]/10 px-3 py-1.5 rounded-lg border border-[#FACC15]/20">
                            {{ str_replace('_', ' ', $firstReport->clinical_data['service_package']) }}
                        </span>
                    </div>
                    @endif

                    @if(isset($firstReport->clinical_data['medical_history']))
                    <div class="md:col-span-2 lg:col-span-4 mt-4 p-4 rounded-xl bg-white/5 border border-white/10">
                        <h4 class="text-[10px] font-black text-[#FACC15] uppercase tracking-[0.2em] mb-3">MEDICAL HISTORY / CONCERNS</h4>
                        <p class="text-white text-sm leading-relaxed">{{ $firstReport->clinical_data['medical_history'] }}</p>
                    </div>
                    @endif

                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">STATUS</h4>
                        <span class="inline-flex items-center px-6 py-3 rounded-xl text-[11px] font-black border {{ \App\Models\Report::STATUSES[$firstReport->status] ?? 'border-slate-500/30 text-slate-400 bg-transparent' }} shadow-2xl">
                            {{ strtoupper($firstReport->status) }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">FILES COUNT</h4>
                        <div class="flex items-center gap-3">
                            <span class="text-[#FACC15] text-1xl font-black tracking-tight">{{ $reports->count() }}</span>
                            <span class="text-slate-500 text-[11px] font-bold uppercase tracking-widest">file(s)</span>
                        </div>
                    </div>

                    @if(isset($firstReport->clinical_data['submission_date']))
                    <div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">SUBMISSION DATE</h4>
                        <span class="text-white text-xs font-bold uppercase tracking-wider">{{ $firstReport->clinical_data['submission_date'] }}</span>
                    </div>
                    @endif

                    @if(!empty($firstReport->clinical_data['dentist_info']['first_name']))
                    <div class="md:col-span-2 lg:col-span-4 mt-6 pt-6 border-t border-white/5 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">DENTIST NAME</h4>
                            <p class="text-white text-sm font-bold">{{ $firstReport->clinical_data['dentist_info']['first_name'] }} {{ $firstReport->clinical_data['dentist_info']['last_name'] }}</p>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">DENTIST EMAIL</h4>
                            <p class="text-white text-sm font-medium">{{ $firstReport->clinical_data['dentist_info']['email'] }}</p>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">DENTIST PHONE</h4>
                            <p class="text-white text-sm font-medium">{{ $firstReport->clinical_data['dentist_info']['phone'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Interface Container -->
    <div id="case-detail-tabs" class="bg-[#0c0c0c] rounded-[24px] border border-white/5 p-5 md:p-8" style="box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5);">
        <!-- Tab Navigation -->
        <div class="flex gap-2 mb-8 border-b border-white/10 overflow-x-auto no-scrollbar scroll-smooth">
            <style>
                .tab-button.active {
                    color: white;
                    border-bottom-color: #FACC15;
                }
            </style>
            <button data-tab="files" class="tab-button px-6 py-3 text-sm font-bold text-gray-400 hover:text-white transition-colors border-b-2 border-transparent whitespace-nowrap">
                Files
            </button>
            <button data-tab="notes" class="tab-button px-6 py-3 text-sm font-bold text-gray-400 hover:text-white transition-colors border-b-2 border-transparent whitespace-nowrap">
                Case Notes
            </button>
            <button data-tab="chat" class="tab-button px-6 py-3 text-sm font-bold text-gray-400 hover:text-white transition-colors border-b-2 border-transparent whitespace-nowrap relative">
                Client Chat
                <span id="chat-notification-indicator" class="hidden absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
        </div>

        <!-- Files Tab Content -->
        <div data-tab-content="files" class="tab-content hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <h3 class="text-xl font-bold text-white tracking-tight">Original Case Files ({{ $originalFiles->count() }})</h3>
                <div class="mt-12 flex justify-center">
                <button type="button" id="toggle-upload-btn" class="group flex items-center gap-4 px-8 py-4 bg-[#FACC15] hover:bg-[#EAB308] rounded-2xl text-black font-black tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(250,204,21,0.2)]">
                    <svg class="w-6 h-6 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    ADD NEW FILES TO THIS CASE
                </button>
                </div>
            </div>
            
            @if($originalFiles->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 mb-8">
                    @foreach ($originalFiles as $report)
                        <div class="bg-[#111111] rounded-[20px] border border-white/10 p-5 group flex flex-col justify-between hover:border-white/20 transition-all duration-300">
                            <div class="flex flex-col mb-6">
                                <div class="flex items-start justify-between mb-2">
                                    <p class="text-sm font-bold text-white leading-tight pr-4 break-words line-clamp-2" title="{{ $report->original_name }}">
                                        {{ $report->original_name }}
                                    </p>
                                    <div class="flex flex-col items-end gap-1">
                                        <span class="shrink-0 inline-flex items-center rounded-md bg-[#FACC15]/10 px-2 py-0.5 text-[10px] font-bold text-[#FACC15] border border-[#FACC15]/20 uppercase tracking-widest">
                                            {{ strtoupper(pathinfo($report->original_name, PATHINFO_EXTENSION)) }}
                                        </span>
                                        @if(isset($report->clinical_data['file_category']))
                                            <span class="shrink-0 inline-flex items-center rounded-md bg-blue-500/10 px-2 py-0.5 text-[8px] font-black text-blue-400 border border-blue-500/20 uppercase tracking-tighter">
                                                {{ str_replace('_', ' ', $report->clinical_data['file_category']) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    {{ $report->mime_type }} • {{ round($report->size / 1024, 1) }} KB
                                </p>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-[10px] text-gray-500">
                                        Uploaded: {{ $report->created_at->format('Y-m-d h:i A') }}
                                    </p>
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded bg-white/5 border border-white/10 {{ optional($report->updatedBy)->role === 'admin' ? 'text-[#FACC15]' : 'text-blue-400' }} uppercase tracking-widest flex items-center gap-1.5">
                                        <span class="opacity-70 font-bold">{{ optional($report->updatedBy)->name }}</span>
                                        <span class="w-1 h-1 rounded-full bg-current opacity-30"></span>
                                        <span>{{ optional($report->updatedBy)->role === 'admin' ? 'Admin' : 'Client' }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        class="flex-1 flex items-center justify-center gap-2 py-2 rounded-lg bg-black border border-white/10 text-[11px] font-bold text-white hover:bg-white/5 transition-colors text-center shadow-sm"
                                        onclick='window.openBHPreview({
                                            url: {{ json_encode(route("admin.cases.preview", $report)) }},
                                            downloadUrl: {{ json_encode(route("admin.cases.download", $report)) }},
                                            mime: {{ json_encode($report->mime_type) }},
                                            title: {{ json_encode($title) }},
                                            name: {{ json_encode($report->original_name) }},
                                            created: {{ json_encode($report->created_at->format("Y-m-d h:i A")) }}
                                        })'>
                                        View File
                                    </button>

                                    <a href="{{ route('admin.cases.download', $report) }}" 
                                    class="flex-1 flex items-center justify-center gap-2 py-2 rounded-lg bg-[#FACC15] border border-[#FACC15] text-[11px] font-black text-black hover:bg-[#FACC15]/90 transition-colors text-center shadow-sm">
                                        Save File
                                    </a>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                        class="copy-link-btn flex-1 py-1.5 rounded-lg bg-white/5 border border-white/10 text-[11px] font-bold text-white hover:bg-white/10 transition-colors"
                                        data-report-id="{{ $report->id }}">
                                        Copy Link
                                    </button>
                                    <form action="{{ route('case.files.destroy', $report) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this file?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div id="upload-section-container" class="hidden mt-8 bg-[#111111]/50 rounded-3xl border border-white/5 p-8 animate-fade-in-up">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-black text-white tracking-tight uppercase">Upload New Files</h3>
                        <p class="text-[11px] text-slate-500 font-bold tracking-widest mt-1">THE UPLOAD WILL START AUTOMATICALLY UPON SELECTION</p>
                    </div>
                    <button type="button" id="close-upload-btn" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form id="ajax-upload-form" action="{{ route('case.files.upload', $batch_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="group relative">
                        <input type="file" name="files[]" id="new_case_files" multiple
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="border-2 border-dashed border-white/10 rounded-2xl p-10 flex flex-col items-center justify-center gap-4 group-hover:border-[#FACC15]/30 group-hover:bg-[#FACC15]/5 transition-all">
                            <div class="h-14 w-14 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:bg-[#FACC15]/20 group-hover:border-[#FACC15]/30 group-hover:scale-110 transition-all">
                                <svg class="w-8 h-8 text-slate-400 group-hover:text-[#FACC15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-white font-bold tracking-tight">Drop files here or click to upload</p>
                                <p class="text-[11px] text-slate-500 font-bold tracking-widest mt-1 uppercase">SUPPORTED FILES: ANY FORMAT, NO SIZE LIMIT</p>
                            </div>
                        </div>
                    </div>

                    <div id="file-list-preview" class="hidden grid grid-cols-2 md:grid-cols-4 gap-4 p-4 rounded-2xl bg-black/40 border border-white/5">
                        <!-- Preview list will be populated by JS -->
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" id="submit-upload-btn" class="group flex items-center gap-3 px-8 py-4 bg-[#FACC15] hover:bg-[#EAB308] rounded-2xl text-black font-black tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(250,204,21,0.2)]">
                            <svg class="w-5 h-5 transition-transform group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            SAVE FILES
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Case Notes Tab Content -->
        <div data-tab-content="notes" class="tab-content hidden">
            <div class="max-w-6xl mx-auto space-y-6">
                <!-- Case Notes Header & List -->
                <div class="bg-[#111111] rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
                    <div class="p-6 border-b border-white/10 flex items-center justify-between bg-gradient-to-r from-white/[0.02] to-transparent">
                        <div>
                            <h3 class="text-xl font-black text-white tracking-tight uppercase">Case Notes</h3>
                            <p class="text-[11px] text-slate-500 font-bold tracking-widest mt-1">INTERNAL DOCUMENTATION & TIMELINE</p>
                        </div>
                        <button type="button" onclick="document.getElementById('add-note-section').scrollIntoView({behavior: 'smooth'})"
                            class="px-5 py-2.5 rounded-xl bg-[#FACC15] text-black text-xs font-black hover:bg-[#FACC15]/90 transition-all flex items-center gap-2 shadow-lg shadow-yellow-400/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            ADD NEW NOTE
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/[0.02]">
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">Details</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">Subject</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">Note Content</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @php
                                    $allNotes = $reports->first()->caseNotes->sortByDesc('created_at');
                                @endphp
                                @forelse($allNotes as $note)
                                    <tr class="hover:bg-white/[0.02] transition-colors group">
                                        <td class="px-6 py-5 align-top w-48">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-xs font-black text-[#FACC15]">{{ $note->user->name }}</span>
                                                <span class="text-[10px] text-slate-500 font-bold">{{ $note->created_at->format('M d, Y') }}</span>
                                                <span class="text-[10px] text-slate-600 font-medium">{{ $note->created_at->format('h:i A') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 align-top w-64">
                                            <span class="text-sm font-bold text-white tracking-tight leading-snug">{{ $note->subject }}</span>
                                        </td>
                                        <td class="px-6 py-5 align-top">
                                            <div class="text-sm text-slate-300 leading-relaxed prose prose-invert prose-sm max-w-none">
                                                {!! $note->message !!}
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 align-top text-right">
                                            <form action="{{ route('case.notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this note?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="h-12 w-12 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10">
                                                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </div>
                                                <p class="text-slate-500 text-sm font-bold tracking-tight">No case notes recorded yet</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add Note Form -->
                <div id="add-note-section" class="bg-[#111111] rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
                    <div class="p-6 border-b border-white/10 bg-gradient-to-r from-white/[0.02] to-transparent">
                        <h3 class="text-xl font-black text-white tracking-tight uppercase">Add Case Note</h3>
                        <p class="text-[11px] text-slate-500 font-bold tracking-widest mt-1">RECORD IMPORTANT UPDATES OR INSTRUCTIONS</p>
                    </div>

                    <form action="{{ route('case.notes.store', $batch_id) }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label for="subject" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Subject / Title</label>
                            <input type="text" name="subject" id="subject" required placeholder="e.g., Clinical instruction for printing"
                                class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl text-white text-sm font-bold placeholder-slate-600 focus:outline-none focus:border-[#FACC15] transition-all shadow-inner">
                        </div>

                        <div class="space-y-2">
                            <label for="editor" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Detailed Message</label>
                            <div class="rounded-2xl overflow-hidden border border-white/10 bg-black shadow-inner">
                                <textarea name="message" id="editor"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" 
                                class="px-10 py-4 rounded-2xl bg-[#FACC15] border border-[#FACC15] text-sm font-black text-black hover:bg-[#FACC15]/90 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-yellow-400/20">
                                SAVE CASE NOTE
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- styles for CKEditor dark mode -->
        <style>
            .ck-editor__edged { border: none !important; }
            .ck-editor__main > .ck-editor__editable {
                background: #000 !important;
                color: #fff !important;
                border: none !important;
                min-height: 200px;
                padding: 1.5rem !important;
                font-size: 14px !important;
            }
            .ck.ck-toolbar {
                background: #0c0c0c !important;
                border: none !important;
                border-bottom: 1px solid rgba(255,255,255,0.1) !important;
                padding: 0.5rem !important;
            }
            .ck.ck-button {
                color: #fff !important;
                cursor: pointer !important;
            }
            .ck.ck-button:hover {
                background: rgba(255,255,255,0.05) !important;
            }
            .ck.ck-button.ck-on {
                background: #FACC15 !important;
                color: #000 !important;
            }
            .ck.ck-toolbar__separator {
                background: rgba(255,255,255,0.1) !important;
            }
            .ck.ck-reset_all * {
                color: #fff !important;
            }
            .ck.ck-dropdown__panel {
                background: #0c0c0c !important;
                border: 1px solid rgba(255,255,255,0.1) !important;
            }
            .ck.ck-list {
                background: #0c0c0c !important;
            }
            .ck.ck-list__item:hover {
                background: rgba(255,255,255,0.05) !important;
            }
        </style>
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                        ]
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        </script>

        <!-- Client Chat Tab Content -->
        <div data-tab-content="chat" class="tab-content hidden">
            <div class="max-w-5xl mx-auto">
                <h3 class="text-xl font-bold mb-6 text-white tracking-tight">Case Discussion</h3>
                
                <div class="bg-[#111111] rounded-xl border border-white/10 overflow-hidden">
                    <div class="flex flex-col h-[500px] md:h-[700px]">
                        <!-- Messages Container -->
                        <div id="case-chat-messages" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4">
                            <!-- Messages will be loaded here by JavaScript -->
                        </div>

                        <!-- Message Input Form -->
                        <div class="border-t border-white/10 p-4 bg-[#0c0c0c]">
                            <form id="case-chat-form" class="flex gap-3">
                                <textarea 
                                    id="case-chat-input" 
                                    placeholder="Type your message..." 
                                    rows="2"
                                    class="flex-1 px-4 py-3 bg-[#111111] border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-[#FACC15] resize-none"
                                ></textarea>
                                <button type="submit" class="px-8 py-3 rounded-xl bg-[#FACC15] border border-[#FACC15] text-sm font-black text-black hover:bg-[#FACC15]/90 transition-colors self-end">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CaseDetailTabs
            window.caseDetailTabs = new CaseDetailTabs('case-detail-tabs', 'files');
            
            // Initialize CaseFileUpload
            window.caseFileUpload = new CaseFileUpload('case-file-upload-form', '{{ $batch_id }}');
            
            // Initialize CaseChatManager
            window.caseChatManager = new CaseChatManager(
                '{{ $batch_id }}',
                '{{ route('case.chat.messages', $batch_id) }}',
                '{{ route('case.chat.send', $batch_id) }}'
            );

            // Handle Copy Case Link button
            const copyCaseLinkBtn = document.getElementById('copy-case-link-btn');
            if (copyCaseLinkBtn) {
                copyCaseLinkBtn.addEventListener('click', async function() {
                    try {
                        const response = await fetch('{{ route('reports.batch.generate-link', ['batchId' => $batch_id]) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.url) {
                            const success = await window.copyToClipboard(data.url);
                            if (success) {
                                window.showToast('Collection link copied to clipboard');
                            } else {
                                window.showToast('Copied to clipboard, but your browser might restricted it. Please copy manually: ' + data.url, 'error');
                            }
                        }
                    } catch (error) {
                        window.showToast('Failed to generate collection link', 'error');
                    }
                });
            }

            // Handle Copy Link buttons for individual files
            document.querySelectorAll('.copy-link-btn').forEach(button => {
                button.addEventListener('click', async function() {
                    const reportId = this.dataset.reportId;
                    try {
                        const response = await fetch(`/reports/${reportId}/generate-link`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.url) {
                            const success = await window.copyToClipboard(data.url);
                            if (success) {
                                window.showToast('File link copied to clipboard');
                            } else {
                                window.showToast('Failed to copy. URL: ' + data.url, 'error');
                            }
                        }
                    } catch (error) {
                        window.showToast('Failed to generate link', 'error');
                    }
                });
            });

            // UI Toggle Logic
            const toggleBtn = document.getElementById('toggle-upload-btn');
            const closeBtn = document.getElementById('close-upload-btn');
            const container = document.getElementById('upload-section-container');

            if (toggleBtn && container) {
                toggleBtn.addEventListener('click', () => {
                    container.classList.remove('hidden');
                    toggleBtn.parentElement.classList.add('hidden');
                });
            }

            if (closeBtn && container) {
                closeBtn.addEventListener('click', () => {
                    container.classList.add('hidden');
                    toggleBtn.parentElement.classList.remove('hidden');
                });
            }

            // Handle New Files Preview with Appending & Manual Upload
            const newFilesInput = document.getElementById('new_case_files');
            const fileListPreview = document.getElementById('file-list-preview');
            const uploadForm = document.getElementById('ajax-upload-form');
            const submitBtn = document.getElementById('submit-upload-btn');
            let selectedFiles = [];

            const renderPreview = () => {
                fileListPreview.innerHTML = '';
                if (selectedFiles.length > 0) {
                    fileListPreview.classList.remove('hidden');
                    selectedFiles.forEach((file, index) => {
                        const extension = file.name.split('.').pop().toUpperCase();
                        const div = document.createElement('div');
                        div.className = 'bg-white/5 border border-white/10 rounded-xl p-3 flex flex-col gap-2 relative overflow-hidden group/item';
                        div.innerHTML = `
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-[#FACC15]/10 flex items-center justify-center text-[#FACC15] border border-[#FACC15]/20 text-[10px] font-black italic">
                                    ${extension}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-bold text-white truncate">${file.name}</p>
                                    <p class="text-[9px] text-slate-500 font-bold tracking-widest uppercase">${(file.size / (1024 * 1024)).toFixed(2)} MB</p>
                                </div>
                                <button type="button" class="remove-pending-file p-1.5 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover/item:opacity-100" data-index="${index}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div id="progress-container-${index}" class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                                <div id="progress-bar-${index}" class="h-full bg-[#FACC15] w-0 transition-all duration-300"></div>
                            </div>
                        `;
                        fileListPreview.appendChild(div);
                    });

                    // Add click listeners to remove buttons
                    document.querySelectorAll('.remove-pending-file').forEach(btn => {
                        btn.onclick = (e) => {
                            const idx = parseInt(e.currentTarget.dataset.index);
                            selectedFiles.splice(idx, 1);
                            renderPreview();
                        };
                    });
                } else {
                    fileListPreview.classList.add('hidden');
                }
            };

            if (newFilesInput && fileListPreview) {
                newFilesInput.addEventListener('change', function() {
                    const newFiles = Array.from(this.files);
                    selectedFiles = [...selectedFiles, ...newFiles];
                    this.value = '';
                    renderPreview();
                });
            }

            // AJAX Upload Logic (Manual)
            if (uploadForm) {
                uploadForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!selectedFiles.length) return;

                    const formData = new FormData();
                    selectedFiles.forEach(file => {
                        formData.append('files[]', file);
                    });

                    const xhr = new XMLHttpRequest();
                    const originalBtnContent = submitBtn.innerHTML;

                    // Update button content
                    submitBtn.innerHTML = `
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        UPLOADING...
                    `;

                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percent = (e.loaded / e.total) * 100;
                            selectedFiles.forEach((_, i) => {
                                const bar = document.getElementById(`progress-bar-${i}`);
                                if (bar) bar.style.width = percent + '%';
                            });
                            submitBtn.innerHTML = `
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                UPLOADING ${Math.round(percent)}%
                            `;
                        }
                    });

                    xhr.addEventListener('load', function() {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            window.showToast('Files uploaded successfully!');
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            window.showToast('Upload failed. Please try again.', 'error');
                            submitBtn.innerHTML = originalBtnContent;
                            submitBtn.disabled = false;
                        }
                    });

                    xhr.addEventListener('error', function() {
                        window.showToast('Upload failed. Network error.', 'error');
                        submitBtn.innerHTML = originalBtnContent;
                        submitBtn.disabled = false;
                    });

                    xhr.open('POST', uploadForm.action);
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.send(formData);
                    submitBtn.disabled = true;
                });
            }
        });
    </script>
@endsection
