const csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const jsonRequest = async (url, options = {}) => {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            ...options.headers,
        },
        ...options,
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(data.message ?? 'Something went wrong.');
        error.status = response.status;
        error.errors = data.errors ?? {};
        throw error;
    }

    return data;
};

const createCommentItem = (comment) => {
    const item = document.createElement('li');
    item.className = 'py-4';
    item.dataset.commentId = String(comment.id);

    const author = document.createElement('p');
    author.className = 'text-sm font-medium text-gray-900';
    author.textContent = comment.author_name;

    const meta = document.createElement('p');
    meta.className = 'text-xs text-gray-400';
    meta.textContent = comment.created_at;

    const body = document.createElement('p');
    body.className = 'mt-1 text-sm text-gray-600';
    body.textContent = comment.body;

    item.append(author, meta, body);

    return item;
};

const clearFormErrors = (form) => {
    form.querySelectorAll('[data-error]').forEach((element) => {
        element.textContent = '';
        element.classList.add('hidden');
    });
};

const showFormErrors = (form, errors) => {
    clearFormErrors(form);

    Object.entries(errors).forEach(([field, messages]) => {
        const element = form.querySelector(`[data-error="${field}"]`);

        if (!element || !messages?.length) {
            return;
        }

        element.textContent = messages[0];
        element.classList.remove('hidden');
    });
};

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('issue-comments');

    if (!container) {
        return;
    }

    const listUrl = container.dataset.listUrl;
    const storeUrl = container.dataset.storeUrl;
    const list = container.querySelector('[data-comment-list]');
    const emptyState = container.querySelector('[data-empty-comments]');
    const loadMoreButton = container.querySelector('[data-load-more]');
    const form = container.querySelector('[data-comment-form]');
    const submitButton = form.querySelector('[data-submit]');

    let nextPageUrl = null;
    let loading = false;

    const updateEmptyState = () => {
        if (!emptyState) {
            return;
        }

        emptyState.classList.toggle('hidden', list.children.length > 0);
    };

    const updateLoadMore = () => {
        if (!loadMoreButton) {
            return;
        }

        loadMoreButton.classList.toggle('hidden', !nextPageUrl);
    };

    const appendComments = (comments, { prepend = false } = {}) => {
        comments.forEach((comment) => {
            const item = createCommentItem(comment);

            if (prepend) {
                list.prepend(item);
                return;
            }

            list.append(item);
        });

        updateEmptyState();
    };

    const loadComments = async (url, { prepend = false } = {}) => {
        if (loading) {
            return;
        }

        loading = true;

        if (loadMoreButton) {
            loadMoreButton.disabled = true;
        }

        try {
            const data = await jsonRequest(url);
            appendComments(data.data, { prepend });
            nextPageUrl = data.next_page_url;
            updateLoadMore();
        } finally {
            loading = false;

            if (loadMoreButton) {
                loadMoreButton.disabled = false;
            }
        }
    };

    loadComments(listUrl);

    loadMoreButton?.addEventListener('click', () => {
        if (nextPageUrl) {
            loadComments(nextPageUrl);
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearFormErrors(form);
        submitButton.disabled = true;

        const formData = new FormData(form);

        try {
            const data = await jsonRequest(storeUrl, {
                method: 'POST',
                body: formData,
            });

            list.prepend(createCommentItem(data.comment));
            updateEmptyState();
            form.reset();
        } catch (error) {
            if (error.status === 422) {
                showFormErrors(form, error.errors);
                return;
            }

            const generalError = form.querySelector('[data-error="general"]');

            if (generalError) {
                generalError.textContent = error.message;
                generalError.classList.remove('hidden');
            }
        } finally {
            submitButton.disabled = false;
        }
    });
});
