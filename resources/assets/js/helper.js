'use strict';

/**
 * Set a button/link to disabled state
 * @param  {DOM Node} btnEl
 */
function disableButton(btnEl) {
    btnEl.setAttribute('disabled', 'true');
    btnEl.classList.add('disabled');
}

/**
 * Set a button/link to enabled state
 * @param  {DOM Node} btnEl
 */
function enableButton(btnEl) {
    btnEl.removeAttribute('disabled');
    btnEl.classList.remove('disabled');
}

module.exports = {
    disableButton: disableButton,
    enableButton: enableButton
};