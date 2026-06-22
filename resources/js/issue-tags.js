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
    button.className = 'inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs text-white hover:opacity-80';
    button.style.backgroundColor = tag.color;
    button.textContent = `${tag.name} ×`;

    return button;
};

const createAvailableChip = (tag) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.dataset.tagId = String(tag.id);
    button.dataset.action = 'attach';
    button.className = 'inline-flex items-center rounded-full border border-gray-300 px-2 py-1 text-xs text-gray-700 hover:bg-gray-50';
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
