@extends('layouts.app')

@section('title', 'Administration — MathGenius')

@section('content')
    <div data-page="admin" class="animate-[mgFade_.4s_ease_both]">
        <div class="mg-mono text-[11px] tracking-[.14em] text-[#0E9E92]">ESPACE FORMATEUR</div>
        <h1 class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F] sm:text-[38px]">Administration</h1>

        <div data-admin-tabs class="mt-6 flex w-fit flex-wrap gap-1.5 rounded-xl border border-[#E4E7EF] bg-[#F1F3F8] p-1"></div>

        <div class="mt-5 rounded-[18px] border border-[#E4E7EF] bg-white shadow-[0_1px_2px_rgba(18,22,31,.05)]">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E4E7EF] px-6 py-5">
                <div>
                    <div data-admin-title class="text-[16px] font-semibold text-[#12161F]">—</div>
                    <div data-admin-count class="mt-1 text-[12.5px] text-[#77819A]">—</div>
                </div>
                <div class="flex items-center gap-2.5">
                    <input data-admin-search type="text" placeholder="Rechercher…" class="w-[220px] rounded-[10px] border border-[#DEE2EC] bg-[#F4F6FA] px-3.5 py-2.5 text-[13.5px] text-[#12161F] outline-none focus:border-[#7C5CFF]">
                    <button type="button" data-admin-create class="whitespace-nowrap rounded-[10px] bg-[#7C5CFF] px-[18px] py-2.5 text-[13.5px] font-semibold text-white transition hover:bg-[#9179FF]">+ Ajouter</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-[13.8px]">
                    <thead>
                        <tr data-admin-head class="mg-mono border-b border-[#E4E7EF] text-[10px] tracking-[.1em] text-[#77819A]"></tr>
                    </thead>
                    <tbody data-admin-body></tbody>
                </table>
                <div data-admin-empty class="hidden px-6 py-10 text-center text-[13.5px] text-[#77819A]">Aucun élément.</div>
            </div>
        </div>
    </div>

    {{-- Create / edit modal --}}
    <div data-admin-modal class="fixed inset-0 z-[80] hidden place-items-center bg-[#12161F]/35 p-6 backdrop-blur-sm">
        <div class="w-full max-w-[520px] rounded-[20px] border border-[#E4E7EF] bg-white shadow-[0_30px_70px_-30px_rgba(18,22,31,.4)]">
            <div class="border-b border-[#E4E7EF] px-6 py-5">
                <div data-admin-modal-kind class="mg-mono text-[10px] tracking-[.14em] text-[#7C5CFF]">—</div>
                <div data-admin-modal-title class="mt-2 text-[19px] font-semibold text-[#12161F]">—</div>
            </div>
            <form data-admin-form class="flex flex-col gap-3.5 px-6 py-6"></form>
            <p data-admin-modal-error class="mx-6 mb-2 hidden rounded-[10px] border border-[#F3D8CE] bg-[#FDF2ED] px-3.5 py-2.5 text-[13px] text-[#C4442A]"></p>
            <div class="flex justify-end gap-2.5 border-t border-[#E4E7EF] bg-[#F8FAFD] px-6 py-[18px]">
                <button type="button" data-admin-modal-cancel class="rounded-[10px] border border-[#DEE2EC] bg-white px-5 py-3 text-[14px] text-[#4B5568] transition hover:border-[#9AA3B8]">Annuler</button>
                <button type="button" data-admin-modal-save class="rounded-[10px] bg-[#7C5CFF] px-[22px] py-3 text-[14px] font-semibold text-white transition hover:bg-[#9179FF]">Enregistrer</button>
            </div>
        </div>
    </div>

    {{-- Delete confirm modal --}}
    <div data-admin-confirm class="fixed inset-0 z-[80] hidden place-items-center bg-[#12161F]/35 p-6 backdrop-blur-sm">
        <div class="w-full max-w-[420px] rounded-[20px] border border-[#E4E7EF] bg-white p-7 shadow-[0_30px_70px_-30px_rgba(18,22,31,.4)]">
            <div class="text-[18px] font-semibold text-[#12161F]">Confirmer la suppression</div>
            <div data-admin-confirm-text class="mt-2.5 text-[14px] leading-relaxed text-[#5C667E]"></div>
            <div class="mt-6 flex justify-end gap-2.5">
                <button type="button" data-admin-confirm-cancel class="rounded-[10px] border border-[#DEE2EC] bg-white px-5 py-3 text-[14px] text-[#4B5568] transition hover:border-[#9AA3B8]">Annuler</button>
                <button type="button" data-admin-confirm-delete class="rounded-[10px] bg-[#C4442A] px-[22px] py-3 text-[14px] font-semibold text-white transition hover:bg-[#D9553A]">Supprimer</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-panel.js') }}"></script>
@endpush
