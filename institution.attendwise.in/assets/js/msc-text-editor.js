$(document).ready(function () {
    $(".msc-text-editor").each(_initTextEditor);
    $(document).on("click", ".msc-text-editor-toolbar-btn", function(){
        if ($(this).data("command") == "sourceCode") {
            if($(".msc-text-editor-content-box").hasClass("sourceCoded")){
                $(".msc-text-editor-content-box").removeClass("sourceCoded");
                $(".msc-text-editor-content-box").html($(".msc-text-editor-content-box").text());
                $(this).removeClass("active");
            }else{
                $(".msc-text-editor-content-box").addClass("sourceCoded");
                $(this).addClass("active");
                $(".msc-text-editor-content-box").text($(".msc-text-editor-content-box").html());
            }
            return;
        }
        // $(this).toggleClass("active");
        // if (document.queryCommandEnabled($(this).data("command")) == true) {
        //     $(this).addClass("active");
        // }else{
        //     $(this).removeClass("active");
        // }

        document.execCommand($(this).data("command"), false);
        console.log(document.queryCommandState($(this).data("command")))
    });
    $(document).on("input", ".msc-text-editor-content-box", function(){
        document.execCommand("fontSize", false, $(this).parent().find(".msc-text-editor-toolbar-font-size-trigger").val())
        document.execCommand("foreColor", false, $(this).parent().find(".msc-text-editor-toolbar-font-color-trigger").val())
        document.execCommand("backColor", false, $(this).parent().find(".msc-text-editor-toolbar-background-color-trigger").val())
        $(".msc-text-editor[data-id="+$(this).parent().attr("id")+"]").val($(this).html())
    })
});

function _initTextEditor(){
    $(this).attr("hidden", true)
    const parent = $(this).parent();
    const editor_uid = Math.floor(Math.random()*100000)
    $(this).attr("data-id", editor_uid)
    const tools = [
        {
            "name":"Bold",
            "type":"button",
            "icon":"fas fa-bold",
            "command":"bold",
        },
        {
            "name":"Italic",
            "type":"button",
            "icon":"fas fa-italic",
            "command":"italic",
        },
        {
            "name":"Underline",
            "type":"button",
            "icon":"fas fa-underline",
            "command":"underline",
        },
        {
            "name":"Source",
            "type":"button",
            "icon":"fas fa-code",
            "command":"sourceCode",
        },
        {
            "name":"Align Left",
            "type":"button",
            "icon":"fas fa-align-left",
            "command":"justifyLeft",
        },
        {
            "name":"Align Center",
            "type":"button",
            "icon":"fas fa-align-center",
            "command":"justifyCenter",
        },
        {
            "name":"Align Right",
            "type":"button",
            "icon":"fas fa-align-right",
            "command":"justifyRight",
        },
        {
            "name":"Align Justify",
            "type":"button",
            "icon":"fas fa-align-justify",
            "command":"justifyJustify",
        },
    ]
    parent.append(`
        <div id='${editor_uid}' class="msc-text-editor-view-box" >
            <div class="msc-text-editor-toolbar">
                ${tools.map(e => "<button type='button' class='btn msc-text-editor-toolbar-btn' data-command='"+e.command+"'><i class='"+e.icon+"'></i></button>").join("")}
                <select width='5%' class="msc-text-editor-toolbar-font-size-trigger" data-command='"+e.command+"'>
                    <option value='1'>Extra Extra Small</option>
                    <option value='2'>Extra Small</option>
                    <option value='3'>Small</option>
                    <option value='4' selected>Medium</option>
                    <option value='5'>Large</option>
                    <option value='6'>Extra Large</option>
                    <option value='7'>Extra Extra Large</option>
                </select>
                <label for='msc-text-editor-toolbar-font-color-trigger' class='btn msc-text-editor-toolbar-btn'><i class='fas fa-palette'></i></label>
                <input id='msc-text-editor-toolbar-font-color-trigger' type='color' class="msc-text-editor-toolbar-font-color-trigger">
                <label for='msc-text-editor-toolbar-background-color-trigger' class='btn msc-text-editor-toolbar-btn'><i class='fas fa-fill'></i></label>
                <input value='#ffffff' id='msc-text-editor-toolbar-background-color-trigger' type='color' class="msc-text-editor-toolbar-background-color-trigger">
            </div>  
            <div class="msc-text-editor-content-box" contenteditable="true"></div>
        </div>`);
    const editor = $("#"+editor_uid);
    
}