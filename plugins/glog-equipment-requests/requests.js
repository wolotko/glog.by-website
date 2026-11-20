(() => {
    const dialog = document.querySelector('#glog-requests-dialog');
    if (!dialog || typeof dialog.showModal !== 'function' || typeof fetch !== 'function') return;

    const cart = dialog.querySelector('[data-glog-cart]');
    const status = dialog.querySelector('[data-glog-dialog-status]');
    const form = dialog.querySelector('[data-glog-checkout]');
    const addError = dialog.querySelector('[data-glog-add-error]');
    const format = new Intl.NumberFormat('ru-BY');
    let quote = '';
    let pending = false;
    let trigger;
    let revision = 0;

    async function call(operation, data = {}) {
        const response = await fetch(dialog.dataset.endpoint, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-GLOG-Request': '1' },
            body: JSON.stringify({ operation, ...data })
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            const error = new Error(result.data?.message || 'Не удалось выполнить действие.');
            error.status = response.status;
            throw error;
        }
        return result.data;
    }

    function element(tag, text) {
        const node = document.createElement(tag);
        if (text !== undefined) node.textContent = text;
        return node;
    }

    function updateCount(count) {
        document.querySelectorAll('[data-glog-count]').forEach(node => {
            node.hidden = count === 0;
            node.textContent = String(count);
        });
    }

    function printPrice(value) {
        return value === null ? 'уточняется' : format.format(value) + ' руб.';
    }

    function render(data) {
        quote = data.quote;
        revision = data.revision;
        updateCount(data.count);
        cart.replaceChildren();
        form.hidden = data.items.length === 0 || data.unavailable.length > 0;
        if (!data.items.length && !data.unavailable.length) {
            cart.append(element('p', 'В корзине пока нет техники.'));
            return;
        }

        const list = element('ul');
        list.className = 'glog-request-items';
        for (const item of [...data.items, ...data.unavailable]) {
            const line = element('li');
            if (item.url) {
                const link = element('a', item.title);
                link.href = item.url;
                line.append(link, element('span', item.code + ' · ' + printPrice(item.unit_price) + ' за ед.'));

                const label = element('label', 'Количество ');
                const quantity = element('input');
                quantity.type = 'number';
                quantity.min = '1';
                quantity.max = '99';
                quantity.value = item.quantity;
                quantity.setAttribute('aria-label', 'Количество для ' + item.title);
                quantity.addEventListener('change', () => {
                    if (!quantity.checkValidity()) {
                        quantity.reportValidity();
                        quantity.value = item.quantity;
                        return;
                    }
                    return changeQuantity(item.product_id, Number(quantity.value));
                });
                label.append(quantity);
                line.append(label);
            } else {
                line.append(element('span', item.code + ' больше недоступна.'));
            }

            const remove = element('button', 'Удалить');
            remove.type = 'button';
            remove.setAttribute('aria-label', 'Удалить ' + (item.title || item.code));
            remove.addEventListener('click', () => changeQuantity(item.product_id, 0));
            line.append(remove);
            list.append(line);
        }
        cart.append(list, element('p', 'Расчётная сумма: ' + printPrice(data.total)));
    }

    async function run(task, errorNode) {
        if (pending) return;
        pending = true;
        try {
            await task();
        } catch (error) {
            errorNode.textContent = error.status ? error.message : 'Подборка временно недоступна. Повторите попытку.';
            if (error.status === 409 && dialog.open) {
                await call('read').then(render).catch(() => {});
            }
        } finally {
            pending = false;
        }
    }

    async function changeQuantity(productId, quantity) {
        await run(async () => {
            status.textContent = '';
            render(await call('quantity', { product_id: productId, quantity, revision }));
        }, status);
    }

    document.addEventListener('click', event => {
        const button = event.target.closest('[data-glog-add], [data-glog-open]');
        if (!button || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

        event.preventDefault();
        if (pending || dialog.open) return;
        const adding = button.hasAttribute('data-glog-add');
        trigger = button;
        status.textContent = adding ? 'Добавляем…' : 'Загружаем…';
        addError.hidden = true;
        cart.replaceChildren();
        form.hidden = true;
        dialog.showModal();
        run(async () => {
            try {
                const data = adding
                    ? await call('add', { product_id: Number(button.dataset.glogAdd) })
                    : await call('read');
                render(data);
                status.textContent = adding ? 'Добавлено в корзину.' : '';
            } catch (error) {
                addError.hidden = !adding;
                throw error;
            }
        }, status);
    });

    dialog.addEventListener('close', () => {
        if (trigger) trigger.focus();
        trigger = null;
    });

    form.addEventListener('submit', event => {
        event.preventDefault();
        const submit = form.querySelector('[type="submit"]');
        const data = Object.fromEntries(new FormData(form));
        run(async () => {
            submit.disabled = true;
            status.textContent = 'Сохраняем заявку…';
            try {
                const confirmation = await call('submit', { ...data, quote });
                cart.replaceChildren();
                form.reset();
                form.hidden = true;
                updateCount(0);
                status.textContent = confirmation.message;
                dialog.querySelector('[autofocus]').focus();
            } finally {
                submit.disabled = false;
            }
        }, status);
    });

    if (document.querySelector('[data-glog-count]')) {
        call('read').then(result => {
            if (!pending && !revision && !quote) updateCount(result.count);
        }).catch(() => {});
    }
})();
