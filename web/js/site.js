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