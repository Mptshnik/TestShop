/**
 * @param {String} fromId
 * @param {String} toId
 */
function copyFrom(fromId, toId) {
    /*** @type {HTMLInputElement}*/
    const fromInput = document.getElementById(fromId);
    /*** @type {HTMLInputElement}*/
    const toInput = document.getElementById(toId);

    toInput.value = fromInput.value;
}

async function onStatusChange() {
    const listId = $('#gridBoxes').yiiGridView('getSelectedRows');
    const status = document.getElementById('box-statuses').value;
    const boxes = getSelectedBoxes(listId);

    /** @type HTMLElement */
    const tableBody = document.getElementsByTagName('tbody')

    if (boxes.length === 0) {
        alert('Выберите коробки для изменения статуса');

        return;
    }

    const data = {
        status: status,
        listId: listId,
    };

    const res = await updateStatus(data);

    if (res.success) {
        alert('Success, reload the page')
    } else {
        alert(res.message)
    }
}

async function exportBoxes() {
    const listId = $('#gridBoxes').yiiGridView('getSelectedRows');

    const data = getSelectedBoxes(listId);

    if (data.length === 0) {
        alert('Выберите коробки для экспорта');

        return;
    }

    await exportBoxesToExcel(data)
}

async function updateStatus(data) {
    const response = await fetch('/box/change-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name=csrf-token]').prop('content')
        },
        body: JSON.stringify(data),
    });

    return response.json();
}

async function exportBoxesToExcel(data) {
    const response = await fetch('/box/export', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name=csrf-token]').prop('content')
        },
        body: JSON.stringify(data),
    }).then(async (response) => {
        const  file = await response.blob();
        const fileURL = URL.createObjectURL(file);
        const a = document.createElement("a");

        let filename = "", m;
        let disposition = response.headers.get('Content-Disposition');

        if (disposition && (m = /"([^"]+)"/.exec(disposition)) !== null) {
            filename = m[1];
        }

        if (typeof a.download === 'undefined') {
            window.location = fileURL;
        } else {
            a.href = fileURL;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
        }
    });
}

function getSelectedBoxes(listId) {
    const data = [];

    [...listId].forEach((boxId) => {
        /*** @type {HTMLInputElement}*/
        const checkbox = document.getElementById(`box-check-${boxId}`);
        const boxRowData = JSON.parse(checkbox.value);

        data.push(boxRowData);
    });

    return data;
}