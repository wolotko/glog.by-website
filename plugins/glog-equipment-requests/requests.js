(() => {
    const dialog = document.querySelector('#glog-requests-dialog');
    if (!dialog || typeof dialog.showModal !== 'function' || typeof fetch !== 'function') return;

    const cart = dialog.querySelector('[data-glog-cart]');
    const status = dialog.querySelector('[data-glog-dialog-status]');
    const form = dialog.querySelector('[data-glog-checkout]');
    const submit = dialog.querySelector('[form="glog-requests-checkout"]');
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
            error.fields = result.data?.fields;
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
            const details = element('div');
            const controls = element('div');
            controls.className = 'glog-request-item-controls';
            if (item.url) {
                const link = element('a', item.title);
                link.href = item.url;
                const meta = element('span', item.code + ' · ' + printPrice(item.unit_price) + ' за ед.');
                meta.className = 'glog-request-item-meta';
                details.append(link, meta);

                const stepper = element('div');
                stepper.className = 'glog-request-quantity';
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
                    changeQuantity(item.product_id, Number(quantity.value), 'input');
                });

                for (const [delta, symbol, label] of [[-1, '−', 'Уменьшить'], [1, '+', 'Увеличить']]) {
                    const step = element('button', symbol);
                    step.type = 'button';
                    step.dataset.step = String(delta);
                    step.setAttribute('aria-label', label + ' количество для ' + item.title);
                    const next = item.quantity + delta;
                    step.disabled = next < 1 || next > 99;
                    step.addEventListener('click', () => changeQuantity(item.product_id, next, '[data-step="' + delta + '"]'));
                    stepper.append(step);
                }
                stepper.insertBefore(quantity, stepper.lastChild);
                controls.append(stepper);
            } else {
                details.append(element('span', item.code + ' больше недоступна.'));
            }

            const remove = element('button', 'Удалить');
            remove.type = 'button';
            remove.className = 'glog-requests-text-button';
            remove.setAttribute('aria-label', 'Удалить ' + (item.title || item.code));
            remove.addEventListener('click', () => changeQuantity(item.product_id, 0));
            controls.append(remove);
            line.dataset.productId = item.product_id;
            line.append(details, controls);
            list.append(line);
        }
        const summary = element('p');
        summary.className = 'glog-requests-total';
        summary.append(element('span', 'Расчётная сумма'), element('strong', printPrice(data.total)));
        const note = element('p', 'Поставка под заказ для юридических лиц и ИП. Менеджер подтвердит цену и условия поставки.');
        note.className = 'glog-requests-note';
        cart.append(list, summary, note);
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

    async function changeQuantity(productId, quantity, focusSelector) {
        await run(async () => {
            status.textContent = '';
            render(await call('quantity', { product_id: productId, quantity, revision }));
            const row = cart.querySelector('[data-product-id="' + productId + '"]');
            const control = focusSelector && row?.querySelector(focusSelector);
            (control && !control.disabled ? control : row?.querySelector('input') || dialog.querySelector('[autofocus]')).focus();
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

    function setFieldError(field, message = '') {
        const description = document.getElementById(field.getAttribute('aria-describedby'));
        if (!description) return;
        description.textContent = message;
        description.hidden = !message;
        if (message) field.setAttribute('aria-invalid', 'true');
        else field.removeAttribute('aria-invalid');
    }

    let invalidScheduled = false;
    form.addEventListener('invalid', event => {
        const field = event.target;
        if (!field.dataset.requiredMessage) return;
        event.preventDefault();
        status.textContent = '';
        setFieldError(field, field.validity.valueMissing ? field.dataset.requiredMessage : field.validationMessage);
        if (!invalidScheduled) {
            invalidScheduled = true;
            queueMicrotask(() => {
                invalidScheduled = false;
                form.querySelector('[aria-invalid="true"]')?.focus();
            });
        }
    }, true);

    form.addEventListener('input', event => {
        if (event.target.hasAttribute('aria-invalid')) setFieldError(event.target);
    });

    form.addEventListener('submit', event => {
        event.preventDefault();
        const data = Object.fromEntries(new FormData(form));
        run(async () => {
            form.querySelectorAll('[aria-invalid]').forEach(field => setFieldError(field));
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
            } catch (error) {
                if (!error.fields) throw error;
                let first;
                for (const field of form.elements) {
                    if (!Object.prototype.hasOwnProperty.call(error.fields, field.name)) continue;
                    setFieldError(field, error.fields[field.name]);
                    first ||= field;
                }
                if (!first) throw error;
                status.textContent = '';
                first.focus();
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
