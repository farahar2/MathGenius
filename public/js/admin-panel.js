document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="admin"]');
    if (!root) return;
    const { apiFetch, escapeHtml, showToast } = window.MG;

    const RESOURCES = {
        chapitres: {
            label: 'Chapitres', endpoint: '/chapitres', adminOnly: false,
            columns: [
                { label: 'Titre', render: (r) => r.titre },
                { label: 'Niveau', render: (r) => (r.niveau ? r.niveau.nom : refLabel('niveaux', r.id_niveau)) },
                { label: 'Ordre', render: (r) => r.ordre ?? 0 },
                { label: 'Publié', render: (r) => (r.is_published ? 'Oui' : 'Non') },
            ],
            fields: [
                { name: 'titre', label: 'Titre', type: 'text', required: true },
                { name: 'description', label: 'Description', type: 'textarea' },
                { name: 'id_niveau', label: 'Niveau', type: 'select', optionsFrom: 'niveaux', optionLabel: (n) => n.nom, required: true },
                { name: 'ordre', label: 'Ordre', type: 'number' },
                { name: 'is_published', label: 'Publié', type: 'checkbox' },
            ],
        },
        lecons: {
            label: 'Leçons', endpoint: '/lecons', adminOnly: false,
            columns: [
                { label: 'Titre', render: (r) => r.titre },
                { label: 'Chapitre', render: (r) => (r.chapitre ? r.chapitre.titre : refLabel('chapitres', r.id_chapitre)) },
                { label: 'Ordre', render: (r) => r.ordre ?? 0 },
                { label: 'Publiée', render: (r) => (r.is_published ? 'Oui' : 'Non') },
            ],
            fields: [
                { name: 'titre', label: 'Titre', type: 'text', required: true },
                { name: 'contenu', label: 'Contenu', type: 'textarea', required: true },
                { name: 'id_chapitre', label: 'Chapitre', type: 'select', optionsFrom: 'chapitres', optionLabel: (c) => c.titre, required: true },
                { name: 'image', label: 'Image (URL)', type: 'text' },
                { name: 'fichier_pdf', label: 'PDF (URL)', type: 'text' },
                { name: 'ordre', label: 'Ordre', type: 'number' },
                { name: 'is_published', label: 'Publiée', type: 'checkbox' },
            ],
        },
        exercices: {
            label: 'Exercices', endpoint: '/exercices', adminOnly: false,
            columns: [
                { label: 'Titre', render: (r) => r.titre },
                { label: 'Leçon', render: (r) => (r.lecon ? r.lecon.titre : refLabel('lecons', r.id_lecon)) },
                { label: 'Ordre', render: (r) => r.ordre ?? 0 },
                { label: 'Publié', render: (r) => (r.is_published ? 'Oui' : 'Non') },
            ],
            fields: [
                { name: 'titre', label: 'Titre', type: 'text', required: true },
                { name: 'enonce', label: 'Énoncé', type: 'textarea', required: true },
                { name: 'correction', label: 'Corrigé', type: 'textarea', required: true },
                { name: 'id_lecon', label: 'Leçon', type: 'select', optionsFrom: 'lecons', optionLabel: (l) => l.titre, required: true },
                { name: 'image', label: 'Image (URL)', type: 'text' },
                { name: 'fichier_pdf', label: 'PDF (URL)', type: 'text' },
                { name: 'ordre', label: 'Ordre', type: 'number' },
                { name: 'is_published', label: 'Publié', type: 'checkbox' },
            ],
        },
        quiz: {
            label: 'Quiz', endpoint: '/quiz', adminOnly: false,
            columns: [
                { label: 'Leçon', render: (r) => (r.lecon ? r.lecon.titre : refLabel('lecons', r.id_lecon)) },
                { label: 'Difficulté', render: (r) => r.difficulte || '—' },
                { label: 'Niveau', render: (r) => r.niveau || '—' },
                { label: 'Durée', render: (r) => (r.duree_secondes ? `${Math.round(r.duree_secondes / 60)} min` : '—') },
            ],
            fields: [
                { name: 'id_lecon', label: 'Leçon', type: 'select', optionsFrom: 'lecons', optionLabel: (l) => l.titre, required: true },
                { name: 'difficulte', label: 'Difficulté', type: 'select', options: ['facile', 'moyen', 'difficile'] },
                { name: 'niveau', label: 'Niveau', type: 'select', options: ['debutant', 'intermediaire', 'avance'] },
                { name: 'duree_secondes', label: 'Durée (secondes)', type: 'number' },
            ],
            beforeSave(payload, refData) {
                const lecon = (refData.lecons || []).find((l) => String(l.id) === String(payload.id_lecon));
                if (lecon) payload.id_chapitre = lecon.id_chapitre;
                return payload;
            },
        },
        questions: {
            label: 'Questions', endpoint: '/questions', adminOnly: false,
            columns: [
                { label: 'Question', render: (r) => truncate(r.question, 60) },
                { label: 'Quiz', render: (r) => quizLabel(r.id_quiz) },
                { label: 'Réponse', render: (r) => r.bonne_reponse },
                { label: 'Ordre', render: (r) => r.ordre ?? 0 },
            ],
            fields: [
                { name: 'id_quiz', label: 'Quiz', type: 'select', optionsFrom: 'quiz', optionLabel: (q) => quizLabel(q.id), required: true },
                { name: 'question', label: 'Question', type: 'textarea', required: true },
                { name: 'option_a', label: 'Option A', type: 'text', required: true },
                { name: 'option_b', label: 'Option B', type: 'text', required: true },
                { name: 'option_c', label: 'Option C', type: 'text', required: true },
                { name: 'option_d', label: 'Option D', type: 'text', required: true },
                { name: 'bonne_reponse', label: 'Bonne réponse', type: 'select', options: ['A', 'B', 'C', 'D'], required: true },
                { name: 'explication', label: 'Explication', type: 'textarea' },
                { name: 'notion', label: 'Notion', type: 'text' },
                { name: 'ordre', label: 'Ordre', type: 'number' },
            ],
        },
        users: {
            label: 'Utilisateurs', endpoint: '/users', adminOnly: true,
            columns: [
                { label: 'Nom', render: (r) => `${r.prenom} ${r.name}` },
                { label: 'Email', render: (r) => r.email },
                { label: 'Rôle', render: (r) => r.role },
                { label: 'Niveau', render: (r) => (r.niveau ? r.niveau.nom : '—') },
            ],
            fields: [
                { name: 'prenom', label: 'Prénom', type: 'text', required: true },
                { name: 'name', label: 'Nom', type: 'text', required: true },
                { name: 'email', label: 'Email', type: 'text', required: true },
                { name: 'password', label: 'Mot de passe', type: 'password', requiredOnCreate: true, hint: 'Laisser vide pour ne pas changer (modification uniquement)' },
                { name: 'role', label: 'Rôle', type: 'select', options: ['student', 'formateur'] },
                { name: 'niveau_id', label: 'Niveau', type: 'select', optionsFrom: 'niveaux', optionLabel: (n) => n.nom },
                { name: 'is_premium', label: 'Premium', type: 'checkbox' },
            ],
        },
    };

    const state = {
        role: null,
        tab: 'chapitres',
        items: [],
        query: '',
        editingId: null,
        deleteTarget: null,
    };
    const refData = { niveaux: [], chapitres: [], lecons: [], quiz: [] };

    function refLabel(kind, id) {
        const item = (refData[kind] || []).find((x) => String(x.id) === String(id));
        return item ? (item.titre || item.nom) : '—';
    }
    function quizLabel(id) {
        const q = refData.quiz.find((x) => String(x.id) === String(id));
        if (!q) return `Quiz #${id}`;
        const lecon = refData.lecons.find((l) => String(l.id) === String(q.id_lecon));
        return lecon ? lecon.titre : `Quiz #${id}`;
    }
    function truncate(text, n) {
        const t = String(text || '');
        return t.length > n ? t.slice(0, n) + '…' : t;
    }

    document.addEventListener('mg:user-ready', async (e) => {
        state.role = e.detail.user.role;

        const [niveauxRes, chapitresRes, leconsRes, quizRes] = await Promise.all([
            apiFetch('/niveaux'), apiFetch('/chapitres'), apiFetch('/lecons'), apiFetch('/quiz'),
        ]);
        refData.niveaux = niveauxRes.ok ? (niveauxRes.data.data || []) : [];
        refData.chapitres = chapitresRes.ok ? (chapitresRes.data.data || []) : [];
        refData.lecons = leconsRes.ok ? (leconsRes.data.data || []) : [];
        refData.quiz = quizRes.ok ? (quizRes.data.data || []) : [];

        renderTabs();
        await loadTab(state.tab);
    }, { once: true });

    function renderTabs() {
        const wrap = root.querySelector('[data-admin-tabs]');
        const tabs = Object.entries(RESOURCES).filter(([, cfg]) => !cfg.adminOnly || state.role === 'admin');
        wrap.innerHTML = tabs.map(([key, cfg]) => {
            const active = key === state.tab;
            return `<button type="button" data-admin-tab="${key}" class="rounded-[9px] px-[18px] py-2.5 text-[13.5px] transition ${active ? 'bg-[#0E9E92] text-white' : 'text-[#5C667E]'}">${cfg.label}</button>`;
        }).join('');
        wrap.querySelectorAll('[data-admin-tab]').forEach((btn) => {
            btn.addEventListener('click', () => loadTab(btn.dataset.adminTab));
        });
    }

    async function loadTab(tab) {
        state.tab = tab;
        state.query = '';
        root.querySelector('[data-admin-search]').value = '';
        renderTabs();

        const cfg = RESOURCES[tab];
        root.querySelector('[data-admin-title]').textContent = cfg.label;
        root.querySelector('[data-admin-count]').textContent = 'Chargement…';
        root.querySelector('[data-admin-body]').innerHTML = '';
        root.querySelector('[data-admin-empty]').classList.add('hidden');

        const { ok, data } = await apiFetch(cfg.endpoint);
        state.items = ok ? (data.data || []) : [];

        if (['chapitres', 'lecons', 'quiz'].includes(tab)) {
            refData[tab] = state.items;
        }

        renderTable();
    }

    function filteredItems() {
        const cfg = RESOURCES[state.tab];
        if (!state.query) return state.items;
        const q = state.query.toLowerCase();
        return state.items.filter((item) => cfg.columns.some((col) => String(col.render(item) ?? '').toLowerCase().includes(q)));
    }

    function renderTable() {
        const cfg = RESOURCES[state.tab];
        const list = filteredItems();

        root.querySelector('[data-admin-count]').textContent = `${list.length} / ${state.items.length} enregistrement${state.items.length > 1 ? 's' : ''}`;
        root.querySelector('[data-admin-head]').innerHTML = cfg.columns.map((c) => `<th class="px-6 py-3 font-normal">${escapeHtml(c.label)}</th>`).join('') + '<th class="px-6 py-3"></th>';

        const body = root.querySelector('[data-admin-body]');
        const empty = root.querySelector('[data-admin-empty]');

        if (!list.length) {
            body.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        body.innerHTML = list.map((item) => `
            <tr class="border-b border-[#EBEEF5] transition hover:bg-[#EEF1F7]">
                ${cfg.columns.map((c) => `<td class="max-w-[240px] truncate px-6 py-3.5">${escapeHtml(c.render(item))}</td>`).join('')}
                <td class="px-6 py-3.5 text-right">
                    <button type="button" data-edit="${item.id}" class="mg-mono mr-2 rounded-[7px] border border-[#DEE2EC] px-2.5 py-1.5 text-[10.5px] text-[#4B5568] transition hover:border-[#5E7F12] hover:text-[#12161F]">MODIFIER</button>
                    <button type="button" data-delete="${item.id}" class="mg-mono rounded-[7px] border border-[#F3D8CE] px-2.5 py-1.5 text-[10.5px] text-[#C4442A] transition hover:bg-[#FDF2ED]">SUPPRIMER</button>
                </td>
            </tr>
        `).join('');

        body.querySelectorAll('[data-edit]').forEach((btn) => btn.addEventListener('click', () => openForm(btn.dataset.edit)));
        body.querySelectorAll('[data-delete]').forEach((btn) => btn.addEventListener('click', () => openDeleteConfirm(btn.dataset.delete)));
    }

    root.querySelector('[data-admin-search]').addEventListener('input', (e) => {
        state.query = e.target.value;
        renderTable();
    });

    root.querySelector('[data-admin-create]').addEventListener('click', () => openForm(null));

    // ---- Create / edit modal ----
    const modal = document.querySelector('[data-admin-modal]');

    function fieldOptions(field) {
        if (field.options) return field.options.map((v) => ({ value: v, label: v }));
        const list = refData[field.optionsFrom] || [];
        return list.map((item) => ({ value: item.id, label: field.optionLabel(item) }));
    }

    function openForm(id) {
        const cfg = RESOURCES[state.tab];
        state.editingId = id;
        const item = id ? state.items.find((i) => String(i.id) === String(id)) : null;

        document.querySelector('[data-admin-modal-kind]').textContent = id ? 'MODIFICATION' : 'CRÉATION';
        document.querySelector('[data-admin-modal-title]').textContent = `${id ? 'Modifier' : 'Ajouter'} — ${cfg.label}`;

        const form = document.querySelector('[data-admin-form]');
        form.innerHTML = cfg.fields.map((f) => {
            const value = item ? item[f.name] : '';
            if (f.type === 'checkbox') {
                return `
                    <label class="flex items-center gap-2.5 text-[13.5px] text-[#12161F]">
                        <input type="checkbox" name="${f.name}" ${value ? 'checked' : ''} class="h-4 w-4 rounded border-[#DEE2EC]">
                        ${escapeHtml(f.label)}
                    </label>
                `;
            }
            if (f.type === 'textarea') {
                return `
                    <div>
                        <label class="mg-mono mb-1.5 block text-[10.5px] tracking-wide text-[#77819A]">${escapeHtml(f.label.toUpperCase())}</label>
                        <textarea name="${f.name}" rows="3" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-2.5 text-[14px] text-[#12161F] outline-none focus:border-[#7C5CFF]">${escapeHtml(value ?? '')}</textarea>
                    </div>
                `;
            }
            if (f.type === 'select') {
                const opts = fieldOptions(f);
                return `
                    <div>
                        <label class="mg-mono mb-1.5 block text-[10.5px] tracking-wide text-[#77819A]">${escapeHtml(f.label.toUpperCase())}</label>
                        <select name="${f.name}" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-2.5 text-[14px] text-[#12161F] outline-none focus:border-[#7C5CFF]">
                            <option value="">—</option>
                            ${opts.map((o) => `<option value="${escapeHtml(o.value)}" ${String(value) === String(o.value) ? 'selected' : ''}>${escapeHtml(o.label)}</option>`).join('')}
                        </select>
                    </div>
                `;
            }
            return `
                <div>
                    <label class="mg-mono mb-1.5 block text-[10.5px] tracking-wide text-[#77819A]">${escapeHtml(f.label.toUpperCase())}</label>
                    <input type="${f.type === 'password' ? 'password' : f.type === 'number' ? 'number' : 'text'}" name="${f.name}" value="${f.type === 'password' ? '' : escapeHtml(value ?? '')}" placeholder="${escapeHtml(f.hint || '')}" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-2.5 text-[14px] text-[#12161F] outline-none focus:border-[#7C5CFF]">
                </div>
            `;
        }).join('');

        document.querySelector('[data-admin-modal-error]').classList.add('hidden');
        modal.classList.remove('hidden');
        modal.classList.add('grid');
    }

    document.querySelector('[data-admin-modal-cancel]').addEventListener('click', closeForm);
    function closeForm() {
        modal.classList.add('hidden');
        modal.classList.remove('grid');
    }

    document.querySelector('[data-admin-modal-save]').addEventListener('click', async () => {
        const cfg = RESOURCES[state.tab];
        const form = document.querySelector('[data-admin-form]');
        let payload = {};

        cfg.fields.forEach((f) => {
            const el = form.querySelector(`[name="${f.name}"]`);
            if (!el) return;
            if (f.type === 'checkbox') { payload[f.name] = el.checked; return; }
            if (el.value === '') return; // omit empty optional fields instead of sending null/''
            payload[f.name] = f.type === 'number' ? Number(el.value) : el.value;
        });

        if (cfg.beforeSave) payload = cfg.beforeSave(payload, refData);

        const saveBtn = document.querySelector('[data-admin-modal-save]');
        saveBtn.disabled = true;
        const errBox = document.querySelector('[data-admin-modal-error]');
        errBox.classList.add('hidden');

        const { ok, data } = state.editingId
            ? await apiFetch(`${cfg.endpoint}/${state.editingId}`, { method: 'PUT', body: JSON.stringify(payload) })
            : await apiFetch(cfg.endpoint, { method: 'POST', body: JSON.stringify(payload) });

        saveBtn.disabled = false;

        if (!ok) {
            const messages = data && data.errors ? Object.values(data.errors).flat().join(' ') : (data && data.message) || 'Une erreur est survenue.';
            errBox.textContent = messages;
            errBox.classList.remove('hidden');
            return;
        }

        closeForm();
        showToast(state.editingId ? 'Élément mis à jour.' : 'Élément créé.');
        await loadTab(state.tab);
    });

    // ---- Delete confirm modal ----
    const confirmModal = document.querySelector('[data-admin-confirm]');

    function openDeleteConfirm(id) {
        state.deleteTarget = id;
        const cfg = RESOURCES[state.tab];
        document.querySelector('[data-admin-confirm-text]').textContent = `Cet élément (${cfg.label.toLowerCase()}) sera définitivement supprimé. Cette action est irréversible.`;
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('grid');
    }

    document.querySelector('[data-admin-confirm-cancel]').addEventListener('click', () => {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('grid');
    });

    document.querySelector('[data-admin-confirm-delete]').addEventListener('click', async () => {
        const cfg = RESOURCES[state.tab];
        const { ok, data } = await apiFetch(`${cfg.endpoint}/${state.deleteTarget}`, { method: 'DELETE' });
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('grid');

        if (!ok) {
            showToast((data && data.message) || 'Suppression impossible.');
            return;
        }

        showToast('Élément supprimé.');
        await loadTab(state.tab);
    });
});
