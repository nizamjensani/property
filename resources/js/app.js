import './bootstrap';

import $ from 'jquery';
window.$ = window.jQuery = $

import select2 from 'select2'
select2(window.$) // attach plugin to the same jQuery instance

import 'select2/dist/css/select2.css'

function initSelect2() {
  $('.js-select2-multi').each(function () {
    if ($(this).hasClass('select2-hidden-accessible')) return

    $(this).select2({
      width: '100%',
      closeOnSelect: false,
      placeholder: $(this).data('placeholder') || 'Select...',
    })
  })
  
}

document.addEventListener('DOMContentLoaded', initSelect2)

  