const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const showAssigneeError = (container, message) => {
    const errorEl = container.querySelector('[data-assignee-error]');

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

const createAttachedChip = (user) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.dataset.userId = String(user.id);
    button.dataset.action = 'detach';
    button.className = 'inline-flex items-center gap-2 border border-stone-300 bg-white px-2.5 py-1 text-xs font-medium text-stone-700 hover:border-stone-900';
    button.title = user.email;
    button.textContent = `${user.name} ×`;

    return button;
};

const createAvailableChip = (user) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.dataset.userId = String(user.id);
    button.dataset.action = 'attach';
    button.className = 'inline-flex items-center gap-2 border border-dashed border-stone-300 px-2.5 py-1 text-xs text-stone-600 hover:border-stone-500 hover:text-stone-900';
    button.title = user.email;
    button.textContent = `+ ${user.name}`;

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
    const container = document.getElementById('issue-assignees');

    if (!container) {
        return;
    }

    const attachUrl = (userId) => container.dataset.attachUrl.replace('__USER__', userId);
    const detachUrl = (userId) => container.dataset.detachUrl.replace('__USER__', userId);
    const attachedList = container.querySelector('[data-attached-assignees]');
    const availableList = container.querySelector('[data-available-assignees]');
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

        const userId = button.dataset.userId;
        const isAttach = button.dataset.action === 'attach';

        showAssigneeError(container, null);
        button.disabled = true;

        try {
            const data = await request(
                isAttach ? attachUrl(userId) : detachUrl(userId),
                isAttach ? 'POST' : 'DELETE',
            );

            if (isAttach) {
                attachedList.appendChild(createAttachedChip(data.user));
                button.remove();
            } else {
                availableList.appendChild(createAvailableChip(data.user));
                button.remove();
            }

            updateEmptyStates();
        } catch (error) {
            showAssigneeError(container, error.message);
            button.disabled = false;
        }
    });

    updateEmptyStates();
});
