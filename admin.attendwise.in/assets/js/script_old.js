$(document).ready(function () {
    $("#mainForm").attr("novalidate", true);
    $("#mainForm").submit(function(e){
        let submitStatus = true;
        e.preventDefault();
        if(validateForm("#mainForm input[required], #mainForm select[required], #mainForm textarea[required]")){
            const formType = $(this).data("form-type");
         if (submitStatus === true) {
            $.ajax({
                type: $(this).attr("method"),
                url: $(this).attr("action"),
                data: $(this).serialize(),
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
    })
    function validateForm(selector){
        let sendState = true;
        $(selector).each(function(){
            if ($(this).val().trim() == "") {
                if ($(this).parent().children(`.msc-err-txt`).length === 0) {
                    $(this).parent().append(`<small class=' pt-1 fw-bold msc-err-txt text-danger'>*This field is required.</small>`);
                }
                sendState = false;
            }else{
                $(this).parent().children(`.msc-err-txt`).remove();
            }
        });
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
   $(".MscDeleteRowBtn").click(function(e){
        e.preventDefault();
        $("#deleteConfirmationModal").modal("show")
        $("#deleteConfirmationModal #confirmDelete").attr("data-rem-uri", $(this).attr("href"))
   })
   $("#deleteConfirmationModal #confirmDelete").click(function(){
    $.ajax({
        type: "POST",
        url: $(this).data("rem-uri"),
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
            $(".MscDeleteRowBtn[href='"+$("#deleteConfirmationModal #confirmDelete").data("rem-uri")+"']").parent().parent().remove();
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
            $(".MscDeleteRowBtn[href='"+$("#deleteConfirmationModal #confirmDelete").data("rem-uri")+"']").parent().parent().remove();
        },
    }); 
   })
   $("#adhaar_number").keyup(function (e) { 
    if ($(this).val().length > 0 && $(this).val().replaceAll("-","").length % 4 == 0) {
        $(this).val($(this).val()+"-");
        $(this).val($(this).val().replaceAll("--","-"));
    }
        if ($(this).val().replaceAll("-","").length >= 12 && e.keyCode != 8) {
            e.preventDefault();
            $(this).val($(this).val().slice(0,14));
            return false;
        }
   });
});