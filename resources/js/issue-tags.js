const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const showTagError = (container, message) => {
    const errorEl = container.querySelector('[data-tag-error]');

    if (!errorEl) {
        return;
    }

    if (message) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
        return;
    }

    errorEl.textContent = '';
    errorEl.classList.add('hidden');
};

const createAttachedChip = (tag) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.dataset.tagId = String(tag.id);
    button.dataset.action = 'detach';
    button.className = 'inline-flex items-center gap-1.5 border border-stone-300 bg-white px-2.5 py-1 text-xs font-medium text-stone-700 hover:border-stone-900';
    button.innerHTML = `<span class="h-2 w-2 rounded-sm" style="background-color: ${tag.color ?? '#78716c'}"></span>${tag.name} ×`;

    return button;
};

const createAvailableChip = (tag) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.dataset.tagId = String(tag.id);
    button.dataset.action = 'attach';
    button.className = 'inline-flex items-center gap-1.5 border border-dashed border-stone-300 px-2.5 py-1 text-xs text-stone-600 hover:border-stone-500 hover:text-stone-900';
    button.textContent = `+ ${tag.name}`;

    return button;
};

const request = async (url, method) => {
    const response = await fetch(url, {
        method,
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw new Error(data.message ?? 'Something went wrong.');
    }

    return data;
};

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('issue-tags');

    if (!container) {
        return;
    }

    const attachUrl = (tagId) => container.dataset.attachUrl.replace('__TAG__', tagId);
    const detachUrl = (tagId) => container.dataset.detachUrl.replace('__TAG__', tagId);
    const attachedList = container.querySelector('[data-attached-tags]');
    const availableList = container.querySelector('[data-available-tags]');
    const emptyAttached = container.querySelector('[data-empty-attached]');
    const emptyAvailable = container.querySelector('[data-empty-available]');

    const updateEmptyStates = () => {
        if (emptyAttached) {
            emptyAttached.classList.toggle('hidden', attachedList.children.length > 0);
        }

        if (emptyAvailable) {
            emptyAvailable.classList.toggle('hidden', availableList.children.length > 0);
        }
    };

    container.addEventListener('click', async (event) => {
        const button = event.target.closest('button[data-action]');

        if (!button || button.disabled) {
            return;
        }

        const tagId = button.dataset.tagId;
        const isAttach = button.dataset.action === 'attach';

        showTagError(container, null);
        button.disabled = true;

        try {
            const data = await request(
                isAttach ? attachUrl(tagId) : detachUrl(tagId),
                isAttach ? 'POST' : 'DELETE',
            );

            if (isAttach) {
                attachedList.appendChild(createAttachedChip(data.tag));
                button.remove();
            } else {
                availableList.appendChild(createAvailableChip(data.tag));
                button.remove();
            }

            updateEmptyStates();
        } catch (error) {
            showTagError(container, error.message);
            button.disabled = false;
        }
    });

    updateEmptyStates();
});
