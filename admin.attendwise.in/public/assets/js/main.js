function mscGetBarcode(code, target){
    $.ajax({
        type: "POST",
        url: "get-barcode",
        data: {
            "_token":$("meta[name=auth_token]").attr("content"),
            "code":code,
        },
        beforeSend:()=>{
            $(""+target).attr("src", "assets/images/loading.gif");
        },  
        success: function (response) {
            $(""+target).attr("src", response);
        },
        error: (err)=>{
            try {
                const res = JSON.parse(err.responseJSON.message);
                mscToast({
                    msg:res.msg ?? "Something went wrong",
                    color:res.color ?? "danger",
                    icon:res.icon ?? "exclamation-triangle",
                });
            } catch (error) {
                mscToast({
                    msg:"Something went wrong",
                    color: "danger",
                    icon: "exclamation-triangle",
                });
            }
        },
    });
}
function mscToast(param = {}){
    const uid = "msc_toast_"+Math.round(Math.random()*100000);
    $(".msc-response-box").append(`<div class="msc-response-text ${param.color ?? "success"} animate__animated animate__fadeInRight" id="${uid}">
                                        <i class="fas fa-${param.icon ?? "check-circle"} msc-response-icon"></i>
                                        <span>${param.msg ?? "This is toast."}</span>
                                        <button class="btn-close float-end msc-close-response"></button>
                                    </div>`);
    setTimeout(() => {
        $("#"+uid).removeClass("animate__animated animate__fadeInDown").addClass("animate__animated animate__fadeOutRight");
    }, param.time ?? 4000);

    setTimeout(() => {
        $("#"+uid).remove();
    }, (param.time ?? 4000)+900);
    return uid;
}
function previewImage(e){
    const [file] = e.files;
    if (file) {
        $("label[for="+e.id+"] img").attr("src", URL.createObjectURL(file))
    }
}
function MscCheckDoctorAvail(param){
        $.ajax({
            type: "POST",
            url: "staff-portal/check/doctor/date",
            data: param.data,
            success: function (response) {
                const result = (response);
                response = JSON.parse(response);
                if (response.status == "success") {
                    if (param.callback && param.callback.success) {
                        callBackStringToFunction(param.callback.success);
                    }
                }else{
                    mscToast({
                        msg:response.msg,
                        color:response.color,
                        icon:response.icon,
                    });
                }
            },
            error: function(err){
                mscToast({
                    msg:err.responseJSON.message,
                    color:"danger",
                    icon:"exclamation-circle",
                });
            }
        });   
    }
function fetchRelatedList(data){
    const selectedValue = $(""+data.target).find("option:selected").html();
    $.ajax({
        type: "POST",
        url: data.url,
        data: {
            "_token":$("meta[name=auth_token]").attr("content"),
            data:data.data,
        },
        beforeSend:()=>{
            if (data.showLoading === undefined || data.showLoading == true) {
                $(""+data.target).html("<option value=''>Loading...</option>");
            }else{
                // $(""+data.target).html("");
            }
        },
        success: function (response) {
            response = JSON.parse(response);
            if (response.status === "success") {
                $(""+data.target).html("");
                response.data.forEach(element => {
                    $(""+data.target).append(`<option ${element.status == null ? "":(element.status != true ? "disabled":"")} value="${element.encrypted_id}" ${element.name == selectedValue ? "selected":""}>${element.name}</option>`);
                });
                if (data.callback) {
                    callBackStringToFunction(data.callback);
                }
                // mscToast({
                //     "msg":response.msg,
                //     "color":response.color,
                //     "icon":response.icon,
                // });
            }else{
                mscToast({
                    "msg":response.msg,
                    "color":response.color,
                    "icon":response.icon,
                });
            }
        },
        error: (err)=>{
            try {
                const res = JSON.parse(err.responseJSON.message);
            mscToast({
              msg:res.msg ?? "Something went wrong",
              color:res.color ?? "danger",
              icon:res.icon ?? "exclamation-triangle",
            });
            } catch (error) {
                mscToast({
                    msg:"Something went wrong",
                    color: "danger",
                    icon: "exclamation-triangle",
                  });
                }
        },
    }); 
   }
   function callBackStringToFunction(callbackStr) {
    if (!callbackStr) return;

            // Split by ',' not inside (), handle arguments, strip spaces
            const callbacks = callbackStr.match(/[^,]+?\(.*?\)|[^,]+/g);

            (callbacks || []).forEach(fnCall => {
                fnCall = fnCall.trim();
                if (!fnCall) return;
                // This is the safe version: extract function name and arguments
                const match = fnCall.match(/^(\w+)\((.*)\)$/);
                if (match) {
                    const fnName = match[1];
                    let argsStr = match[2].trim();
                    let args = [];
                    if (argsStr) {
                        try {
                            // Wrap args in [] and eval to array
                            args = eval(`[${argsStr}]`);
                        } catch (e) {
                            console.error('Callback argument parsing failed:', e);
                        }
                    }
                    if (typeof window[fnName] === "function") {
                        window[fnName](...args);
                    }
                } else if (typeof window[fnCall] === "function") {
                    // For simple "funName" callbacks with no '()'
                    window[fnCall]();
                }
            });
}
function validateForm(elements){
    let sendState = true;
    elements.each(function(){
        if ($(this).val() == "" || $(this).val() == null || $(this).val() == undefined) {
            if ($(this).parent().children(`.msc-err-txt`).length === 0) {
                $(this).parent().append(`<small class='pt-1 fw-bold msc-err-txt text-danger'>*This field is required.</small>`);
            }
            sendState = false;
        }else if (($(this).attr("max") && Number($(this).val()) > Number($(this).attr("max"))) || 
    ($(this).attr("maxlength") && $(this).val().length > $(this).attr("maxlength")) || 
    ($(this).attr("min") && Number($(this).val()) < Number($(this).attr("min")))){
            if ($(this).parent().children(`.msc-err-txt`).length === 0) {
                $(this).parent().append(`<small class='pt-1 fw-bold msc-err-txt text-danger'>*Invalid value.</small>`);
            }
            sendState = false;
        }else{
            $(this).parent().children(`.msc-err-txt`).remove();
        }
    });
    return sendState;
}
$(document).ready(function () {
    $('select.msc-searchable').each(function () {
      const $select = $(this).hide();
      const isMultiple = $select.prop('multiple');
      const options = $select.find('option');

      const $wrapper = $('<div class="search-wrapper"></div>');
      const $selected = $('<div class="form-control search-selected form-select"></div>');
      const $input = $('<input type="text" class="search-input" placeholder="Search...">');
      const $dropdown = $('<div class="search-dropdown"></div>');

      // Populate dropdown
      options.each(function () {
        const val = $(this).val();
        const text = $(this).text();
        if (val !== "") {
          $dropdown.append(`<div data-value="${val}">${text}</div>`);
        }
      });

      $selected.append($input);
      $wrapper.append($selected).append($dropdown);
      $select.after($wrapper);
        $.each($select[0].attributes, function () {
        const attrName = this.name;
        const attrValue = this.value;

        // Check if it's an inline event handler like 'onchange', 'onfocusout'
        if (/^on/.test(attrName) && attrValue) {
            const eventType = attrName.slice(2); // remove 'on'

            // Attach to custom input
            $input.on(eventType, function (event) {
            const func = new Function('event', attrValue);
            func.call($select[0], event); // call with original select as 'this'
            });
        }
        });

      let currentIndex = -1;

      // FILTER
      function filterDropdown() {
        const search = $input.val().toLowerCase();
        $dropdown.children('div').each(function () {
          const text = $(this).text().toLowerCase();
          $(this).toggle(text.includes(search));
        });
        currentIndex = -1;
        $dropdown.show();
      }

      // UPDATE ACTIVE
      function updateActive($items) {
        $items.removeClass('active');
        if (currentIndex >= 0) {
          const $activeItem = $items.eq(currentIndex);
          $activeItem.addClass('active');

          // ✅ Scroll into view if not visible
          const dropdownScrollTop = $dropdown.scrollTop();
          const dropdownHeight = $dropdown.height();
          const itemTop = $activeItem.position().top;
          const itemHeight = $activeItem.outerHeight();

          if (itemTop < 0) {
            $dropdown.scrollTop(dropdownScrollTop + itemTop);
          } else if (itemTop + itemHeight > dropdownHeight) {
            $dropdown.scrollTop(dropdownScrollTop + itemTop - dropdownHeight + itemHeight);
          }
        }
      }


      // SELECT ITEM
      function selectItem($item) {
        const value = $item.data('value');
        const text = $item.text();

        if (isMultiple) {
          if ($select.find(`option[value="${value}"]`).is(':selected')) return;
          $select.find(`option[value="${value}"]`).prop('selected', true);
          addTag(value, text);
          $input.val('');
          filterDropdown();
        } else {
          $select.val(value).trigger('change');
          $input.val(text);
          $dropdown.hide();
        }
      }

      // ADD TAG (for multiple)
      function addTag(value, text) {
        const $existing = $selected.find(`.search-tag[data-value="${value}"]`);
        if ($existing.length) return;

        const $tag = $(`<span class="search-tag" data-value="${value}">${text}<span class="remove-tag" data-value="${value}">&times;</span></span>`);
        $tag.insertBefore($input);
      }

      // INITIALIZE selected values
      function syncFromSelect() {
        const selectedValues = $select.val();
        $selected.find('.search-tag').remove();

        if (isMultiple) {
          if (Array.isArray(selectedValues)) {
            selectedValues.forEach(function (val) {
              const text = $select.find(`option[value="${val}"]`).text();
              if (val !== "") addTag(val, text);
            });
          }
        } else {
          const selectedOption = $select.find('option:selected');
          if (selectedOption.length > 0) {
            const val = selectedOption.val();
            const text = selectedOption.text();
            $input.val(text);
          } else {
            $select.val('');
            $input.val('');
          }
        }
      }


      // Watch for manual changes in original select
      $select.on('change', function () {
        syncFromSelect();
      });

      // Initial sync
      syncFromSelect();

      // INPUT EVENTS
      $input.on('input focus', filterDropdown);

      $input.on('keydown', function (e) {
        const $items = $dropdown.children('div:visible');
        if (e.key === "ArrowDown") {
          e.preventDefault();
          if (currentIndex < $items.length - 1) currentIndex++;
          updateActive($items);
        } else if (e.key === "ArrowUp") {
          e.preventDefault();
          if (currentIndex > 0) currentIndex--;
          updateActive($items);
        } else if (e.key === "Enter") {
          e.preventDefault();
          if (currentIndex >= 0) {
            selectItem($items.eq(currentIndex));
          }
        }
      });

      // SELECT BY CLICK
      $dropdown.on('click', 'div', function () {
        selectItem($(this));
      });

      // REMOVE TAG
      $selected.on('click', '.remove-tag', function () {
        const value = $(this).data('value');
        $(this).parent().remove();
        $select.find(`option[value="${value}"]`).prop('selected', false).trigger('change');
      });

      // OUTSIDE CLICK
      $(document).on('click', function (e) {
        if (!$(e.target).closest($wrapper).length) {
          $dropdown.hide();
        }
      });
    });

});/* ---------- GlobalFormEnhancer (namespaced, safe) ---------- */
window.GlobalFormConfig = window.GlobalFormConfig || {};
window.GlobalFormConfig.statesCities = window.GlobalFormConfig.statesCities || {
  "Maharashtra": ["Mumbai","Pune","Nagpur","Nashik"],
  "Karnataka": ["Bengaluru","Mysore","Mangalore"],
  "Delhi": ["New Delhi"],
  "Uttar Pradesh": ["Lucknow","Kanpur","Noida"],
  "Other": ["Other City"]
};

(function(window, document){
  const enhancer = window.GlobalFormEnhancer || {};
  window.GlobalFormEnhancer = enhancer;

  function safeQuery(selector, ctx=document){ return Array.from((ctx || document).querySelectorAll(selector)); }

  function _findFirstInvalid(form){
    try { const nativeInvalid = form.querySelector(':invalid'); if(nativeInvalid) return nativeInvalid; } catch(e){}
    const elems = safeQuery('input, select, textarea', form);
    for(const el of elems){
      if(el.type === 'file') continue;
      if(el.parentElement && el.parentElement.querySelector('.msc-err-txt')) return el;
      if(el.hasAttribute('required')){
        const v = el.value; if(v === "" || v === null || v === undefined) return el;
      }
    }
    return null;
  }

  function _showTabForElement(el){
    if(!el) return;
    const pane = el.closest('.tab-pane');
    if(!pane || !pane.id){ el.scrollIntoView({behavior:'smooth', block:'center'}); try{el.focus({preventScroll:false});}catch(e){el.focus && el.focus();} return; }
    let tabButton = document.querySelector('[data-bs-target="#' + pane.id + '"], [href="#' + pane.id + '"]');
    if(tabButton){
      try { if(window.bootstrap && bootstrap.Tab) new bootstrap.Tab(tabButton).show(); else tabButton.click(); } catch(e){ tabButton.click(); }
      tabButton.classList.add('tab-highlight');
      setTimeout(()=> tabButton.classList.remove('tab-highlight'), 2600);
    } else pane.scrollIntoView({behavior:'smooth', block:'center'});
    setTimeout(()=> { try{ el.focus(); } catch(e){ el.focus && el.focus(); } }, 220);
  }

  function _appendError(el, msg){
    if(!el || !el.parentElement) return;
    if(el.parentElement.querySelector('.msc-err-txt')) return;
    const small = document.createElement('small'); small.className = 'pt-1 fw-bold msc-err-txt text-danger'; small.textContent = msg || '*This field is required.'; el.parentElement.appendChild(small);
  }
  function _removeError(el){ if(!el || !el.parentElement) return; const err = el.parentElement.querySelector('.msc-err-txt'); if(err) err.remove(); }

  function _internalValidateForm(form){
    let ok = true; let firstInvalid = null;
    const inputs = safeQuery('input, select, textarea', form);
    for(const el of inputs){
      if(el.type === 'file') continue;
      const val = el.value; let invalid = false;
      if(el.hasAttribute('required')){ if(val === "" || val === null || val === undefined){ invalid = true; _appendError(el, '*This field is required.'); } }
      if(!invalid && val !== null && val !== ''){ if(el.hasAttribute('max') && !isNaN(Number(val)) && Number(val) > Number(el.getAttribute('max'))){ invalid = true; _appendError(el, '*Invalid value.'); } if(el.hasAttribute('min') && !isNaN(Number(val)) && Number(val) < Number(el.getAttribute('min'))){ invalid = true; _appendError(el, '*Invalid value.'); } if(el.hasAttribute('maxlength') && val.length > Number(el.getAttribute('maxlength'))){ invalid = true; _appendError(el, '*Invalid value.'); } }
      if(!invalid) _removeError(el); else { ok = false; if(!firstInvalid) firstInvalid = el; }
    }
    enhancer.updateTabBadges && enhancer.updateTabBadges(form);
    if(!ok && firstInvalid) _showTabForElement(firstInvalid);
    return ok;
  }

  enhancer.validateForm = function(form){
    if(!form) return true;
    if(typeof window.validateIdForm === 'function'){
      if(form.id){
        const selector = '#' + form.id + ' input[required], #' + form.id + ' select[required], #' + form.id + ' textarea[required]';
        const result = validateIdForm(selector);
        enhancer.updateTabBadges && enhancer.updateTabBadges(form);
        if(!result){ const firstInvalid = _findFirstInvalid(form); if(firstInvalid) _showTabForElement(firstInvalid); }
        return result;
      } else {
        const uid = 'data-enhancer-uid'; const val = 'f_' + Math.random().toString(36).slice(2,9); form.setAttribute(uid, val);
        const selector = 'form[' + uid + '] input[required], form[' + uid + '] select[required], form[' + uid + '] textarea[required]';
        const result = validateIdForm(selector); form.removeAttribute(uid);
        enhancer.updateTabBadges && enhancer.updateTabBadges(form);
        if(!result){ const firstInvalid = _findFirstInvalid(form); if(firstInvalid) _showTabForElement(firstInvalid); }
        return result;
      }
    } else {
      return _internalValidateForm(form);
    }
  };

  enhancer.updateTabBadges = function(form){
    if(!form) return;
    const panes = safeQuery('.tab-pane', form);
    for(const pane of panes){
      let count = 0;
      const inputs = safeQuery('input, select, textarea', pane);
      for(const inp of inputs){ if(inp.type === 'file') continue; const hasErr = inp.parentElement && inp.parentElement.querySelector('.msc-err-txt'); const isEmptyRequired = inp.hasAttribute('required') && (inp.value === "" || inp.value === null || inp.value === undefined); if(hasErr || isEmptyRequired) count++; }
      const paneId = pane.id; if(!paneId) continue;
      let tabButton = form.querySelector('[data-bs-target="#' + paneId + '"], [href="#' + paneId + '"]');
      if(!tabButton) tabButton = document.querySelector('[data-bs-target="#' + paneId + '"], [href="#' + paneId + '"]');
      if(!tabButton) continue;
      let badge = tabButton.querySelector('.tab-badge'); if(!badge){ badge = document.createElement('span'); badge.className = 'tab-badge d-none'; tabButton.appendChild(badge); }
      if(count > 0){ badge.textContent = count; badge.classList.remove('d-none'); } else { badge.textContent = ''; badge.classList.add('d-none'); }
    }
  };

  enhancer.attachLogoPreview = function(form){
    const fileInputs = safeQuery('input[type="file"][data-logo-preview="true"]', form);
    for(const input of fileInputs){
      const targetSelector = input.getAttribute('data-logo-target');
      const preview = targetSelector ? document.querySelector(targetSelector) : null;
      input.addEventListener('change', function(e){
        const f = e.target.files && e.target.files[0]; if(!f) return; const reader = new FileReader(); reader.onload = function(ev){ if(preview) preview.src = ev.target.result; }; reader.readAsDataURL(f);
      });
    }
  };

  enhancer.initSelect2Tags = function(form){
    if(!(window.jQuery && jQuery().select2)) return;
    const selects = safeQuery('select[data-select2-tags="true"]', form);
    selects.forEach(s => { const $s = jQuery(s); const initial = $s.data('initial') || []; $s.select2({ tags: true, tokenSeparators: [',',';'], data: (initial || []).map(v => ({ id: v, text: v })) }); });
  };

  enhancer.attachStateCity = function(form){
    const stateSelect = form.querySelector('select[data-state-select="true"]');
    const citySelect = form.querySelector('select[data-city-select="true"]');
    if(!stateSelect || !citySelect) return;
    function populateStates(){ stateSelect.innerHTML = '<option value="">Select state</option>'; Object.keys(window.GlobalFormConfig.statesCities).forEach(s => { const opt = document.createElement('option'); opt.value = s; opt.textContent = s; if(stateSelect.getAttribute('data-selected') === s || stateSelect.value === s) opt.selected = true; stateSelect.appendChild(opt); }); stateSelect.dispatchEvent(new Event('change')); }
    function populateCitiesFor(state){ citySelect.innerHTML = '<option value="">Select city</option>'; const list = window.GlobalFormConfig.statesCities[state] || []; list.forEach(c => { const opt = document.createElement('option'); opt.value = c; opt.textContent = c; if(citySelect.getAttribute('data-selected') === c || citySelect.value === c) opt.selected = true; citySelect.appendChild(opt); }); }
    stateSelect.addEventListener('change', function(){ populateCitiesFor(this.value); });
    populateStates();
  };

  enhancer.attachJsonHelpers = function(form){
    const tAs = safeQuery('textarea[data-json-helper="true"]', form);
    tAs.forEach(ta => {
      const id = ta.id || ('json_' + Math.random().toString(36).slice(2,9)); if(!ta.id) ta.id = id;
      const container = document.createElement('div'); container.className = 'json-helper-actions';
      container.innerHTML = `<button type="button" class="btn btn-sm btn-outline-primary js-validate">Validate JSON</button><button type="button" class="btn btn-sm btn-outline-secondary js-pretty">Pretty Print</button><button type="button" class="btn btn-sm btn-outline-success js-export">Export</button><div class="json-msg mt-2"></div>`;
      ta.insertAdjacentElement('afterend', container);
      container.querySelector('.js-validate').addEventListener('click', function(){ try { const parsed = JSON.parse(ta.value.trim() || '[]'); container.querySelector('.json-msg').innerHTML = `<div class="alert alert-success py-1 mb-0">Valid JSON — ${Array.isArray(parsed) ? parsed.length + ' items' : typeof parsed}.</div>`; } catch(e){ container.querySelector('.json-msg').innerHTML = `<div class="alert alert-danger py-1 mb-0">JSON Error: ${e.message}</div>`; } });
      container.querySelector('.js-pretty').addEventListener('click', function(){ try { const parsed = JSON.parse(ta.value.trim() || '[]'); ta.value = JSON.stringify(parsed, null, 2); container.querySelector('.json-msg').innerHTML = `<div class="alert alert-success py-1 mb-0">Pretty printed.</div>`; } catch(e){ container.querySelector('.json-msg').innerHTML = `<div class="alert alert-danger py-1 mb-0">JSON Error: ${e.message}</div>`; } });
      container.querySelector('.js-export').addEventListener('click', function(){ const blob = new Blob([ta.value], { type: 'application/json' }); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = (ta.id || 'export') + '.json'; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url); });
    });
  };

  enhancer.enhanceForm = function(form){
    if(!form || form._enhanced) return; form._enhanced = true;
    enhancer.attachLogoPreview(form); enhancer.attachStateCity(form); enhancer.initSelect2Tags(form); enhancer.attachJsonHelpers(form);
    form.addEventListener('submit', function(e){ const ok = enhancer.validateForm(form); if(!ok){ e.preventDefault(); e.stopPropagation(); } }, { passive: false });
    enhancer.updateTabBadges(form);
  };

  enhancer.autoInit = function(){ safeQuery('form[data-enhance="true"]').forEach(f => enhancer.enhanceForm(f)); };

  window.GlobalFormEnhancer = enhancer;
  document.addEventListener('DOMContentLoaded', function(){ enhancer.autoInit(); });

  if(typeof window.validateForm === 'undefined'){
    window.validateForm = function(form){ return window.GlobalFormEnhancer && typeof window.GlobalFormEnhancer.validateForm === 'function' ? window.GlobalFormEnhancer.validateForm(form) : true; };
  } else {
    if(typeof window.GlobalFormEnhancer.validateForm === 'undefined'){
      window.GlobalFormEnhancer.validateForm = function(form){ try { return window.validateForm(form); } catch(e){ return true; } };
    }
  }
})(window, document);

