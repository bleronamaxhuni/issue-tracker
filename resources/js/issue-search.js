import Alpine from 'alpinejs';

const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const openModal = (name) => {
    window.dispatchEvent(new CustomEvent('open-modal', { detail: name }));
};

const bindModalTriggers = (root) => {
    root.querySelectorAll('[data-open-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            openModal(button.dataset.openModal);
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('issue-search');

    if (!container) {
        return;
    }

    const resultsEl = container.querySelector('[data-results]');
    const searchInput = container.querySelector('[data-search-input]');
    const filterForm = container.querySelector('[data-filter-form]');
    const statusEl = container.querySelector('[data-search-status]');
    const indexUrl = container.dataset.indexUrl;

    let debounceTimer = null;
    let activeController = null;

    const buildUrl = () => {
        const params = new URLSearchParams();

        filterForm.querySelectorAll('[data-filter-select]').forEach((field) => {
            if (field.value) {
                params.set(field.name, field.value);
            }
        });

        const search = searchInput?.value.trim();

        if (search) {
            params.set('search', search);
        }

        const query = params.toString();

        return query ? `${indexUrl}?${query}` : indexUrl;
    };

    const setLoading = (loading) => {
        if (!statusEl) {
            return;
        }

        statusEl.classList.toggle('hidden', !loading);
        statusEl.textContent = loading ? 'Searching...' : '';
    };

    const fetchResults = async () => {
        if (activeController) {
            activeController.abort();
        }

        activeController = new AbortController();
        const url = buildUrl();

        setLoading(true);

        try {
            const response = await fetch(url, {
                signal: activeController.signal,
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken(),
                },
            });

            if (!response.ok) {
                throw new Error('Search failed.');
            }

            const data = await response.json();

            resultsEl.innerHTML = data.html;
            Alpine.initTree(resultsEl);
            bindModalTriggers(resultsEl);

            const countEl = resultsEl.querySelector('[data-issue-count]');

            if (countEl && data.count_label) {
                countEl.textContent = data.count_label;
            }

            window.history.replaceState(null, '', url);
        } catch (error) {
            if (error.name !== 'AbortError' && statusEl) {
                statusEl.classList.remove('hidden');
                statusEl.textContent = 'Could not load results. Please try again.';
            }
        } finally {
            setLoading(false);
            activeController = null;
        }
    };

    const debouncedFetch = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchResults, 300);
    };

    searchInput?.addEventListener('input', debouncedFetch);

    filterForm?.querySelectorAll('[data-filter-select]').forEach((select) => {
        select.addEventListener('change', fetchResults);
    });

    bindModalTriggers(resultsEl);
});
