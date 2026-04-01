
$(document).ready(function () {
    $(function() {
  // Wrap each msc-smart-table in a container for the search box
  $('table.msc-smart-table').each(function () {
    let $table = $(this);
    let $tbody = $table.find('tbody');

    // 1. Wrap and Add Controls (Search + Limit + Pagination Container)
    let $container = $(this).wrap('<div class="msc-smart-table-container"></div>').parent();
    
    // Top Bar: Search + Limit Select
    let $topControls = $('<div class="msc-top-controls"></div>');
    let $limitLabel = $('<label>Show <select class="msc-limit-select form-control d-inline-block" style="width:auto"><option value="5">5</option><option value="10" selected>10</option><option value="25">25</option><option value="50">50</option></select> entries</label>');
    let $searchInput = $('<input type="text" class="msc-smart-table-search form-control" placeholder="Search..." autocomplete="off">');
    
    $topControls.append($limitLabel).append($searchInput);
    $container.prepend($topControls);

    // Bottom Bar: Pagination Buttons
    let $pagination = $('<div class="msc-pagination"></div>');
    $container.append($pagination);

    // State Variables
    let currentPage = 1;
    let rowsPerPage = 10;

    // --- Core Function: Update Table Display ---
    function updateTable() {
        let $rows = $tbody.find('tr').not('.msc-action-row'); // Ignore generated action rows
        let searchTerm = $searchInput.val().toLowerCase();

        // 1. Filter Rows (Search Logic)
        let $visibleRows = $rows.filter(function() {
            let text = $(this).text().toLowerCase();
            let matches = text.indexOf(searchTerm) > -1;
            $(this).toggle(matches); // Physically hide non-matches for now
            return matches;
        });

        // 2. Calculate Pagination
        let totalRows = $visibleRows.length;
        let totalPages = Math.ceil(totalRows / rowsPerPage);
        
        // Prevent being on page 5 if only 1 page exists after search
        if (currentPage > totalPages) currentPage = 1; 
        if (currentPage < 1) currentPage = 1;

        let start = (currentPage - 1) * rowsPerPage;
        let end = start + rowsPerPage;

        // 3. Slice and Show/Hide based on Page
        $rows.hide(); // Hide all initially
        
        // Remove any open action rows when repaginating/searching
        $('.msc-action-row').remove();
        $rows.removeClass('msc-active');

        // Show only the specific slice of filtered rows
        $visibleRows.slice(start, end).show();

        // 4. Render Pagination Buttons
        renderPaginationControls(totalPages, totalRows);
    }

    // --- Helper: Render Pagination Buttons ---
    function renderPaginationControls(totalPages, totalRows) {
        $pagination.empty();
        
        if (totalPages <= 1 && totalRows > 0) return; // No pagination needed
        if (totalRows === 0) {
            $pagination.html('<span class="text-muted">No records found</span>');
            return;
        }

        let html = '<ul class="pagination mb-0">';
        
        // Prev
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><a href="#" class="page-link msc-prev">Previous</a></li>`;

        // Numbers (Logic to show simplified range if many pages)
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);

        if (startPage > 1) html += '<li class="page-item disabled"><span class="page-link">...</span></li>';

        for (let i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${i === currentPage ? 'active' : ''}"><a href="#" class="page-link msc-page-num" data-page="${i}">${i}</a></li>`;
        }

        if (endPage < totalPages) html += '<li class="page-item disabled"><span class="page-link">...</span></li>';

        // Next
        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}"><a href="#" class="page-link msc-next">Next</a></li>`;
        html += '</ul>';

        // Info text
        let startInfo = ((currentPage - 1) * rowsPerPage) + 1;
        let endInfo = Math.min(currentPage * rowsPerPage, totalRows);
        html += `<div class="mt-2 text-muted small">Showing ${startInfo} to ${endInfo} of ${totalRows} entries</div>`;

        $pagination.html(html);
    }

    // --- Event Listeners ---

    // 1. Search Input
    $searchInput.on('keyup', function () {
        currentPage = 1; // Reset to page 1 on search
        updateTable();
    });

    // 2. Limit Change
    $limitLabel.find('select').on('change', function() {
        rowsPerPage = parseInt($(this).val());
        currentPage = 1;
        updateTable();
    });

    // 3. Pagination Clicks
    $pagination.on('click', '.msc-page-num', function(e) {
        e.preventDefault();
        currentPage = parseInt($(this).data('page'));
        updateTable();
    });

    $pagination.on('click', '.msc-prev', function(e) {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            updateTable();
        }
    });

    $pagination.on('click', '.msc-next', function(e) {
        e.preventDefault();
        // Recalculate max pages just to be safe
        let visibleCount = $tbody.find('tr').not('.msc-action-row').filter(':visible').length; 
        // Note: Logic allows clicking next, updateTable handles bounds check
        currentPage++;
        updateTable();
    });

    // --- Existing Features (Modified) ---

    // Sorting
    $table.on('click', 'thead th', function () {
        let $th = $(this);
        let $rows = $tbody.find('tr').not('.msc-action-row');
        let index = $th.index();
        let asc = !$th.hasClass('sorted-asc');

        $table.find('th').removeClass('sorted-asc sorted-desc');
        $th.addClass(asc ? 'sorted-asc' : 'sorted-desc');

        $rows.get().sort((a, b) => {
            let A = $(a).find('td').eq(index).text().trim();
            let B = $(b).find('td').eq(index).text().trim();
            let numA = parseFloat(A.replace(/[^0-9.\-]/g, ''));
            let numB = parseFloat(B.replace(/[^0-9.\-]/g, ''));
            let bothNumeric = !isNaN(numA) && !isNaN(numB);
            if (bothNumeric) return asc ? numA - numB : numB - numA;
            else return asc ? A.localeCompare(B) : B.localeCompare(A);
        }).forEach((row) => $tbody.append(row));

        // IMPORTANT: Re-run pagination after sort to show correct slice
        updateTable();
    });

    // Add 3-dot button logic (Unchanged mostly)
    $table.find('tbody tr:not(.empty)').each(function () {
        if ($(this).find('td:last button.msc-action-btn').length > 3) { // Ensure check doesn't duplicate
             let $lastTd = $(this).find('td:last');
             // Only add if not already added
             if($lastTd.find('.fa-ellipsis-h').length === 0) {
                $lastTd.data('action-html', $lastTd.html()); 
                $lastTd.html('<button class="msc-action-btn btn btn-warning"><i class="fas fa-ellipsis-h"></i> Actions</button>');
             }
        }
    });

    // Toggle expand (Unchanged logic)
    $table.on('click', '.msc-action-btn', function (e) {
        e.stopPropagation();
        let $btn = $(this);
        let $row = $btn.closest('tr');

        if ($row.hasClass('msc-active')) {
            $table.find('.msc-action-row').remove();
            $table.find('tbody tr').removeClass('msc-active');
            return;
        }
        $table.find('.msc-action-row').remove();
        $table.find('tbody tr').removeClass('msc-active');

        $row.addClass('msc-active');
        let buttonsHtml = $row.find('td:last').data('action-html');
        let colCount = $row.find('td').length;

        let $actionRow = $(`
            <tr class="msc-action-row">
                <td colspan="${colCount}">
                    <div class="msc-action-content">${buttonsHtml}</div>
                </td>
            </tr>
        `).hide();

        $row.after($actionRow);
        $actionRow.slideDown(200);
    });

    // Click outside to close (Unchanged)
    $(document).on('click', function () {
        $table.find('.msc-action-row').remove();
        $table.find('tbody tr').removeClass('msc-active');
    });

    // Initial Run
    updateTable();
});
});
    $(".multi_msc_selector").change(function(e){
        $("#"+$(this).attr("data-msc-target")).append(`<h5 class="badge bg-warning me-1">${$(this).val()} <button type="button" class='btn mscRemoveSelectedOption btn-close btn-sm p-0 ms-1'></button> <input type="hidden" name="${$(this).attr("data-msc-name")}[]" value="${$(this).val()}"></h5>`)
        $($(this).children("option[value='"+$(this).val()+"']")).attr("disabled", true)
        $($(this).children("option")[0]).removeAttr("selected", true)
        $($(this).children("option")[0]).attr("selected", true)
    })
    $(document).on("click", ".mscRemoveSelectedOption", function(){
        const val = $(this).parent().children("input[type=hidden]").val();
        $(this).parent().hide(200);
        $("select[data-msc-target='"+$(this).parent().parent().attr("id")+"'").children("option").each(function(){
            if ($(this).attr("value") == val) {
                $(this).removeAttr("disabled");
            }
        });
        setTimeout(() => {
            $(this).parent().remove()
        }, 210);
    })
    $("#mainForm input[required][type!=file], #mainForm select[required], #mainForm textarea[required]").each(function(){
        if ($(this).attr("required")) {
            $("label[for="+$(this).attr("id")+"]").append("<span class='text-danger'>*</span>")
        }
    });
    $(".msc-ord-form input[required][type!=file], .msc-ord-form select[required], .msc-ord-form textarea[required]").each(function(){
        if ($(this).attr("required")) {
            $("label[for="+$(this).attr("id")+"]").append("<span class='text-danger'>*</span>")
        }
    });
    $(".msc-ord-form").attr("novalidate", true);
    $(".msc-ord-form").submit(function(e){
    let submitStatus = true;
    
    e.preventDefault();
    const callbackStr = $(this).attr("data-callback");
    // console.log(callbackStr);
    const currentForm = $(this); // Reference to current form
    const formType = currentForm.data("form-type");
    
    if (formType == "EDIT") {
        if(validateForm(currentForm.find("input[required][type!=file], select[required], textarea[required]"))){
            let formData = new FormData(this);
            let imageFile = currentForm.find('input[type=file][required]').length; // Get the selected file
            
            for (let index = 0; index < currentForm.find('input[type=file][required]').length; index++) {
                const element = currentForm.find('input[type=file][required]')[index].files[0];
                if (!element) {
                    // submitStatus = false;
                    // alert("Please upload image.")
                    // break;
                    continue;
                }
                formData.append('image', element);
            }
            
            if (submitStatus === true) {
                $.ajax({
                    type: currentForm.attr("method"),
                    url: currentForm.attr("action"),
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    beforeSend: function(){
                        submitStatus = false;
                    },
                    success: function (response) {
                        response = JSON.parse(response);
                        mscToast({
                            "msg":response.msg,
                            "color":response.color,
                            "icon":response.icon,
                        });
                        submitStatus = true;
                        if (formType == "ADD") {
                            currentForm.trigger("reset");
                            currentForm.find("label img").each(function(){
                                $(this).attr("src", $(this).attr("src-def"));
                            });
                        }
                        setTimeout(() => {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        }, 1500);
                        callBackStringToFunction(callbackStr);
                    },
                    error: function(err){
                        const res = JSON.parse(err.responseJSON.message);
                        submitStatus = true;
                        mscToast({
                            msg:res.msg,
                            color:res.color,
                            icon:res.icon,
                        });
                    }
                });   
            }
        }     
    }else{
        if(validateForm(currentForm.find("input[required], select[required], textarea[required]"))){
            let formData = new FormData(this);
            let imageFile = currentForm.find('input[type=file][required]').length; // Get the selected file
            
            for (let index = 0; index < currentForm.find('input[type=file][required]').length; index++) {
                const element = currentForm.find('input[type=file][required]')[index].files[0];
                if (!element) {
                    submitStatus = false;
                    alert("Please upload image.")
                    break;
                }
                formData.append('image', element);
            }
            
            if (submitStatus === true) {
                $.ajax({
                    type: currentForm.attr("method"),
                    url: currentForm.attr("action"),
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    beforeSend: function(){
                        submitStatus = false;
                    },
                    success: function (response) {
                        response = JSON.parse(response);
                        mscToast({
                            "msg":response.msg,
                            "color":response.color,
                            "icon":response.icon,
                        });
                        submitStatus = true;
                        if (formType == "ADD") {
                            currentForm.trigger("reset");
                        }
                        setTimeout(() => {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        }, 1500);
                        callBackStringToFunction(callbackStr);
                    },
                    error: function(err){
                        try {
                            const res = JSON.parse(err.responseJSON.message);
                            submitStatus = true;
                            mscToast({
                                msg:res.msg,
                                color:res.color,
                                icon:res.icon,
                            });
                        } catch (error) {
                            mscToast({
                                msg:"Something went wrong.",
                                color:"danger",
                                icon:"exclamation-circle",
                            });
                        }
                    }
                });   
            }
        }
    }
    });



    $("#mainForm").attr("novalidate", true);
    $("#mainForm").submit(function(e){
        let submitStatus = true;
        const callbackStr = $(this).attr("data-callback");
        e.preventDefault();
        const formType = $(this).data("form-type");
        if (formType == "EDIT") {
            if(validateIdForm("#mainForm input[required][type!=file], #mainForm select[required], #mainForm textarea[required]")){
                let formData = new FormData(this);
                let imageFile = $('#mainForm input[type=file][required]').length; // Get the selected file
                for (let index = 0; index < $('#mainForm input[type=file][required]').length; index++) {
                    const element = $('#mainForm input[type=file][required]')[index].files[0];
                    if (!element) {
                        // submitStatus = false;
                        // alert("Please upload image.")
                        // break;
                        continue;
                    }
                    formData.append('image', element);
                }
                
                if (submitStatus === true) {
                $.ajax({
                    type: $(this).attr("method"),
                    url: $(this).attr("action"),
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    beforeSend: function(){
                        submitStatus = false;
                    },
                    success: function (response) {
                        response = JSON.parse(response);
                        mscToast({
                            "msg":response.msg,
                            "color":response.color,
                            "icon":response.icon,
                        });
                        submitStatus = true;
                        if (formType == "ADD") {
                            $("#mainForm").trigger("reset");
                            $("#mainForm label img").each(function(){
                                $(this).attr("src", $(this).attr("src-def"));
                            });
                        }
                        setTimeout(() => {
                                if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        }, 1500);
                        callBackStringToFunction(callbackStr);
                    },
                    error: function(err){
                        const res = JSON.parse(err.responseJSON.message);
                        submitStatus = true;
                        mscToast({
                          msg:res.msg,
                          color:res.color,
                          icon:res.icon,
                        });
                      }
                });   
             }
            }     
        }else{
            if(validateIdForm("#mainForm input[required], #mainForm select[required], #mainForm textarea[required]")){
                let formData = new FormData(this);
                let imageFile = $('#mainForm input[type=file][required]').length; // Get the selected file
                for (let index = 0; index < $('#mainForm input[type=file][required]').length; index++) {
                    const element = $('#mainForm input[type=file][required]')[index].files[0];
                    if (!element) {
                        submitStatus = false;
                        alert("Please upload image.")
                        break;
                    }
                    formData.append('image', element);
                }
                
                if (submitStatus === true) {
                $.ajax({
                    type: $(this).attr("method"),
                    url: $(this).attr("action"),
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    beforeSend: function(){
                        submitStatus = false;
                    },
                    success: function (response) {
                        response = JSON.parse(response);
                        mscToast({
                            "msg":response.msg,
                            "color":response.color,
                            "icon":response.icon,
                        });
                        submitStatus = true;
                        if (formType == "ADD") {
                            $("#mainForm").trigger("reset");
                        }
                        setTimeout(() => {
                                if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        }, 1500);
                        callBackStringToFunction(callbackStr);
                    },
                    error: function(err){
                        const res = JSON.parse(err.responseJSON.message);
                        submitStatus = true;
                        mscToast({
                          msg:res.msg,
                          color:res.color,
                          icon:res.icon,
                        });
                      }
                });   
             }
            }
        }
       
    })
    function validateIdForm(selector){
    let sendState = true;
    let firstInvalidElement = null;

    $(selector).each(function(){

        let isInvalid = false;
        let val = $(this).val();

        if (val == "" || val == null || val == undefined) {
            isInvalid = true;
            if ($(this).parent().children(`.msc-err-txt`).length === 0) {
                $(this).parent().append(`<small class='pt-1 fw-bold msc-err-txt text-danger'>*This field is required.</small>`);
            }
        } 
        else if (
            ($(this).attr("max") && Number(val) > Number($(this).attr("max"))) ||
            ($(this).attr("maxlength") && val.length > Number($(this).attr("maxlength"))) ||
            ($(this).attr("min") && Number(val) < Number($(this).attr("min")))
        ) {
            isInvalid = true;
            if ($(this).parent().children(`.msc-err-txt`).length === 0) {
                $(this).parent().append(`<small class='pt-1 fw-bold msc-err-txt text-danger'>*Invalid value.</small>`);
            }
        } 
        else {
            $(this).parent().children(`.msc-err-txt`).remove();
        }

        if(isInvalid){
            sendState = false;

            // Store ONLY the first invalid element
            if (!firstInvalidElement) {
                firstInvalidElement = this;
            }
        }
    });

    // === NEW FEATURE: highlight tab if invalid field is inside a hidden tab ===
    if (!sendState && firstInvalidElement) {

        const $invalidEl = $(firstInvalidElement);

        // find parent .tab-pane
        const pane = $invalidEl.closest('.tab-pane');

        if (pane.length && pane.attr('id')) {
            const paneId = pane.attr('id');

            // find the tab button that controls this pane
            const tabButton = document.querySelector('[data-bs-target="#' + paneId + '"]');

            if (tabButton) {
                // switch tab using Bootstrap API
                try {
                    const tabObj = new bootstrap.Tab(tabButton);
                    tabObj.show();
                } catch (e) {
                    tabButton.click(); // fallback
                }

                // add subtle highlight
                tabButton.classList.add('tab-highlight');

                // remove highlight after small timeout
                setTimeout(() => {
                    tabButton.classList.remove('tab-highlight');
                }, 2500);
            }
        }

        // focus the first invalid field
        setTimeout(() => {
            firstInvalidElement.focus();
        }, 200);
    }

    return sendState;
}

    $(".nav-link.menu-dropdown").parent().children(".collapse").removeClass("show");
    $(".nav-link.active").removeClass("active");
    $(".nav-item.active").removeClass("active");
    $(".nav-item").each(function(){
        if ($(this).children(".nav-link").attr("href") == window.location.href) {
            $(this).addClass("active");
            if ($(this).children(".nav-link").hasClass("dropdown-anchor")) {
                $(this).parent().parent().addClass("show").parent().addClass("active")
            }
        }
    })
   $(document).on("click",".MscDeleteRowBtn",function(e){
        e.preventDefault();
        $("#deleteConfirmationModal #confirmDelete").attr("data-rem-uri", $(this).attr("href"))
        $("#deleteConfirmationModal #confirmDelete").attr("data-callback", $(this).attr("data-callback"))
        // console.log($(this).attr("href"))
        // console.log($("#deleteConfirmationModal #confirmDelete").attr("data-rem-uri"));
        $("#deleteConfirmationModal").modal("show")
   })
   $(document).on("click","#deleteConfirmationModal #confirmDelete",function(){
    const callbackStr = $(this).attr('data-callback');
    // console.log($(this).data("rem-uri"));
    $.ajax({
        type: "POST",
        url: $(this).attr("data-rem-uri"),
        data: {
            "_token":$("meta[name=auth_token]").attr("content"),
        },
        beforeSend:()=>{
            $("#deleteConfirmationModal #confirmDelete").html("<i class='fas fa-spinner mscSpinner'></i>").attr("disabled", true);
        },
        success: function (response) {
            $("#deleteConfirmationModal #confirmDelete").html("Confirm").removeAttr("disabled");
            response = JSON.parse(response);
            mscToast({
                "msg":response.msg,
                "color":response.color,
                "icon":response.icon,
            });
            $("#deleteConfirmationModal").modal("hide");
            $(".MscDeleteRowBtn[data-link='"+$("#deleteConfirmationModal #confirmDelete").attr("data-rem-uri")+"']").closest("tr").remove();
            callBackStringToFunction(callbackStr);
        },
        error: (err)=>{
            try {
                const res = JSON.parse(err.responseJSON.message);
            mscToast({
              msg:res.msg ?? "Something went wrong",
              color:res.color ?? "danger",
              icon:res.icon ?? "exclamation-triangle",
            });
            $("#deleteConfirmationModal").modal("hide");
            } catch (error) {
                mscToast({
                    msg:"Something went wrong",
                    color: "danger",
                    icon: "exclamation-triangle",
                  });
                }
            $("#deleteConfirmationModal").modal("hide");
            $(".MscDeleteRowBtn[data-link='"+$("#deleteConfirmationModal #confirmDelete").attr("data-rem-uri")+"']").closest("tr").remove();
        },
    }); 
   })
   $("#adhaar_number").keyup(function (e) { 
    if ($(this).val().length > 0 && $(this).val().replaceAll("-","").length % 4 == 0 && e.keyCode != 8) {
        $(this).val($(this).val()+"-");
        $(this).val($(this).val().replaceAll("--","-"));
    }
        if ($(this).val().replaceAll("-","").length >= 12 && e.keyCode != 8) {
            e.preventDefault();
            $(this).val($(this).val().slice(0,14));
            return false;
        }
   });
//    $(".sidebar").mouseover(function(){
    // $("body").removeClass("sidebar-icon-only")
//    })
//    $(".sidebar").mouseleave(function(){
//     $("body").addClass("sidebar-icon-only")
//    })
    
    /* ---------- Fixed GlobalDialog (replace your previous GlobalDialog implementation) ---------- */
window.GlobalDialog = (function(){
  // modal/toast are present in your blade. Guard if not.
  const modalEl = document.getElementById('globalDialogModal');
  const toastEl = document.getElementById('globalDialogToast');
  const toastBody = toastEl ? toastEl.querySelector('#globalDialogToastBody') : null;

  const hasBootstrap = typeof bootstrap !== 'undefined' && bootstrap.Modal;
  let bsModal = null;
  function ensureModal(){ if(modalEl && !bsModal && hasBootstrap) bsModal = new bootstrap.Modal(modalEl, { backdrop:'static', keyboard:false }); }

  function escapeHtml(str){ if(!str) return ''; return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }

  function showModal({ title='Confirm', message='', okText='OK', cancelText='Cancel', showCancel=true, variantOk='primary', prompt=false, defaultValue='' } = {}){
    return new Promise((resolve, reject) => {
      if(!modalEl){
        // graceful fallback to native confirm/alert
        try {
          if(prompt){
            const val = window.prompt(message, defaultValue);
            resolve(val === null ? null : val);
          } else {
            const ok = window.confirm(message);
            resolve(ok);
          }
        } catch(e){
          reject(e);
        }
        return;
      }

      ensureModal();

      // set title/body/footer content
      const titleEl = modalEl.querySelector('#globalDialogTitle');
      const bodyEl = modalEl.querySelector('#globalDialogBody');
      titleEl.textContent = title;
      bodyEl.innerHTML = '';

      // Create content for prompt or plain text
      if(prompt){
        const wrapper = document.createElement('div');
        wrapper.innerHTML = `<div class="mb-2">${message}</div><input type="text" class="form-control" id="globalDialogPromptInput" />`;
        wrapper.querySelector('#globalDialogPromptInput').value = escapeHtml(defaultValue);
        bodyEl.appendChild(wrapper);
      } else {
        bodyEl.innerHTML = message;
      }

      // Get current buttons fresh each time (they may have been replaced earlier)
      // We will replace them with clones to strip old event listeners safely.
      const footer = modalEl.querySelector('#globalDialogFooter');
      let okRef = footer.querySelector('#globalDialogOk');
      let cancelRef = footer.querySelector('#globalDialogCancel');

      // If okRef/cancelRef missing, create them
      if(!okRef){
        okRef = document.createElement('button');
        okRef.id = 'globalDialogOk';
        okRef.type = 'button';
        okRef.className = 'btn btn-' + (variantOk || 'primary');
        okRef.textContent = okText;
        footer.appendChild(okRef);
      }
      if(!cancelRef){
        cancelRef = document.createElement('button');
        cancelRef.id = 'globalDialogCancel';
        cancelRef.type = 'button';
        cancelRef.className = 'btn btn-secondary';
        cancelRef.textContent = cancelText;
        footer.insertBefore(cancelRef, okRef);
      }

      // prepare clones -> replacing the nodes removes old listeners reliably
      const okClone = okRef.cloneNode(true);
      const cancelClone = cancelRef.cloneNode(true);

      // Replace in DOM (do this only if parent exists)
      if(okRef.parentNode) okRef.parentNode.replaceChild(okClone, okRef);
      if(cancelRef.parentNode) cancelRef.parentNode.replaceChild(cancelClone, cancelRef);

      // Grab fresh refs (the clones)
      okRef = footer.querySelector('#globalDialogOk');
      cancelRef = footer.querySelector('#globalDialogCancel');

      // set texts and visibility
      okRef.textContent = okText;
      okRef.className = 'btn btn-' + (variantOk || 'primary');
      cancelRef.textContent = cancelText;
      cancelRef.style.display = showCancel ? '' : 'none';

      // Helper to close and resolve
      function closeAndResolve(result){
        if(hasBootstrap && bsModal) bsModal.hide();
        else { modalEl.classList.remove('show'); modalEl.style.display = 'none'; }
        resolve(result);
      }

      // Attach handlers (use once: true to auto-remove)
      okRef.addEventListener('click', function(){
        if(prompt){
          const input = modalEl.querySelector('#globalDialogPromptInput');
          closeAndResolve(input ? input.value : null);
        } else closeAndResolve(true);
      }, { once: true });

      cancelRef.addEventListener('click', function(){
        closeAndResolve(prompt ? null : false);
      }, { once: true });

      // Show modal
      if(hasBootstrap && bsModal) bsModal.show();
      else {
        modalEl.style.display = 'block';
        modalEl.classList.add('show');
      }
    });
  }

  return {
    confirm(options){ return showModal(Object.assign({ title:'Confirm', message:'Are you sure?', okText:'Yes', cancelText:'No', showCancel:true, variantOk:'primary' }, options)); },
    alert(options){ return showModal(Object.assign({ title:'Notice', message:'', okText:'OK', showCancel:false, variantOk:'primary' }, options)); },
    prompt(options){ return showModal(Object.assign({ title:'Input', message:'', prompt:true, defaultValue:'', okText:'OK', cancelText:'Cancel' }, options)); },
    toast({ message = '', duration = 3500 } = {}) {
      if(!toastEl) { console.log('Toast:', message); return; }
      const body = toastEl.querySelector('.toast-body') || toastBody;
      if(body) body.textContent = message;
      if(window.bootstrap && bootstrap.Toast){
        toastEl.style.display = '';
        const t = new bootstrap.Toast(toastEl, { delay: duration });
        t.show();
      } else {
        toastEl.style.display = 'block';
        setTimeout(()=> { toastEl.style.display = 'none'; }, duration);
      }
    }
  };
})();
   
});


