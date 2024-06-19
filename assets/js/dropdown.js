import {Dropdown} from 'bootstrap';

window.addEventListener('DOMContentLoaded', (event) => {
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    const dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new Dropdown(dropdownToggleEl)
    })
});
