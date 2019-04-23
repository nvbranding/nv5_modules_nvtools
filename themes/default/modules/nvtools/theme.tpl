<!-- BEGIN: main -->
<style type="text/css">
    .error {
        color: #a50000;
    }
</style>
<link rel="stylesheet" href="{NV_BASE_SITEURL}modules/nvtools/js/colorpicker/css/colorpicker.css" type="text/css" />
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/nvtools/js/colorpicker/colorpicker.js">
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.js">
</script>
<form id="nvtools_theme" action="{NV_BASE_SITEURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <table class="tab1">
        <caption>
            {LANG.theme_info}
        </caption>
        <col style="width:30%;"/>
        <tbody>
            <tr>
                <td>
                    {LANG.theme}
                </td>
                <td>
                    <input type="text" name="theme" style="width:250px;" value="{THEME_INFO.theme}" class="required"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.info_name}
                </td>
                <td>
                    <input type="text" name="info_name" style="width:250px;" value="{THEME_INFO.info_name}" class="required"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.info_author}
                </td>
                <td>
                    <input type="text" name="info_author" style="width:250px;" value="{THEME_INFO.info_author}" class="required"/>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.info_website}
                </td>
                <td>
                    <input type="text" name="info_website" style="width:250px;" value="{THEME_INFO.info_website}" class="required url"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.info_description}
                </td>
                <td>
                    <input type="text" name="info_description" style="width:250px;" value="{THEME_INFO.info_description}" />
                </td>
            </tr>
        </tbody>
    </table>
    <table class="tab1">
        <caption>
            {LANG.setting_css}
        </caption>
        <col style="width:30%;"/><col style="width:30%;"/><col style="width:25%;"/><col style="width:15%;"/>
        <tbody>
            <tr>
                <td>
                    {LANG.doctype}
                </td>
                <td>
                    <select name="doctype">
                        <!-- BEGIN: doctype -->
                        <option value="{DOCTYPE.value}" {DOCTYPE.selected}>{DOCTYPE.text}  </option>
                        <!-- END: doctype -->
                    </select>
                </td>
                <td>
                    {LANG.css_reset}
                </td>
                <td>
                    <select name="css_reset">
                        <!-- BEGIN: css_reset -->
                        <option value="{CSS_RESET.value}" {CSS_RESET.selected}>{CSS_RESET.text}  </option>
                        <!-- END: css_reset -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.pageWidthType}
                </td>
                <td>
                    <select name="pagetype">
                        <!-- BEGIN: pagetype --><option value="{PAGETYPE.value}" {PAGETYPE.selected}>{PAGETYPE.text}  </option>
                        <!-- END: pagetype -->
                    </select>
                </td>
                <td>
                    <div id="langPageWidth">
                        {LANG.pageWidthValue}
                    </div>
                </td>
                <td>
                    <input type="text" name="pageWidthValue" style="width:40px;text-align:right;" value="{THEME_CSS.pageWidthValue}" class="digits"/>&nbsp;px
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.bodyBgColor}
                </td>
                <td>
                    #<input type="text" name="bodyBgColor" style="width:60px;" value="{THEME_CSS.bodyBgColor}" class="colorpic required"/>
                </td>
                <td>
                    {LANG.textColor}
                </td>
                <td>
                    #<input type="text" name="textColor" style="width:60px;" value="{THEME_CSS.textColor}" class="colorpic required"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.aColor}
                </td>
                <td>
                    #<input type="text" name="aColor" style="width:60px;" value="{THEME_CSS.aColor}" class="colorpic required"/>
                </td>
                <td>
                    {LANG.aHoverColor}
                </td>
                <td>
                    #<input type="text" name="aHoverColor" style="width:60px;" value="{THEME_CSS.aHoverColor}" class="colorpic required"/>
                </td>
            </tr>
        </tbody>        
        <tbody>
            <tr>
                <td>
                    {LANG.horizontalMenuHeight}
                </td>
                <td>
                    <input type="text" name="horizontalMenuHeight" style="width:40px;text-align:right;" value="{THEME_CSS.horizontalMenuHeight}" class="digits required" />&nbsp;px - ({LANG.horizontalMenuHeight_note})
                </td>
                <td>
                    {LANG.horizontalMenuBgColor}
                </td>
                <td>
                    #<input type="text" name="horizontalMenuBgColor" style="width:60px;" value="{THEME_CSS.horizontalMenuBgColor}" class="colorpic required"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.headerHeight}
                </td>
                <td>
                    <input type="text" name="headerHeight" style="width:40px; text-align:right;" value="{THEME_CSS.headerHeight}" class="digits required" />&nbsp;px
                </td>
                <td>
                    {LANG.headerBgColor}
                </td>
                <td>
                    #<input type="text" name="headerBgColor" style="width:60px;" value="{THEME_CSS.headerBgColor}" class="colorpic required"/>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.footerHeight}
                </td>
                <td>
                    <input type="text" name="footerHeight" style="width:40px;text-align:right;" value="{THEME_CSS.footerHeight}" class="digits required" />&nbsp;px
                </td>
                <td>
                    {LANG.footerBgColor}
                </td>
                <td>
                    #<input type="text" name="footerBgColor" style="width:60px;" value="{THEME_CSS.footerBgColor}" class="colorpic required"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.leftSidebarWidth}
                </td>
                <td>
                    <input type="text" name="leftSidebarWidth" style="width:40px;text-align:right;" value="{THEME_CSS.leftSidebarWidth}" class="digits required" />&nbsp;px
                </td>
                <td>
                    {LANG.leftSidebarBgColor}
                </td>
                <td>
                    #<input type="text" name="leftSidebarBgColor" style="width:60px;" value="{THEME_CSS.leftSidebarBgColor}" class="colorpic required"/>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.rightSidebarWidth}
                </td>
                <td>
                    <input type="text" name="rightSidebarWidth" style="width:40px;text-align:right;" value="{THEME_CSS.rightSidebarWidth}" class="digits required" />&nbsp;px
                </td>
                <td>
                    {LANG.rightSidebarBgColor}
                </td>
                <td>
                    #<input type="text" name="rightSidebarBgColor" style="width:60px;" value="{THEME_CSS.rightSidebarBgColor}" class="colorpic required"/>
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.additional}
                </td>
                <td colspan="3">
                    <!-- BEGIN: additional -->
                     <label><input type="radio" name="additional" value="{ADDITIONAL.value}" {ADDITIONAL.checked} /> {ADDITIONAL.text}</label>
                    <!-- END: additional -->
                </td>
            </tr>
        </tbody>        
    </table>
    <table class="tab1">
        <caption>
            {LANG.layoutsetting}
        </caption>
        <col style="width:10%;"/><col style="width:30%;"/><col style="width:30%;"/><col style="width:30%;"/>
        <thead align="center">
            <tr>
                <td>
                    {LANG.number}
                </td>
                <td>
                    {LANG.layoutname}
                </td>
                <td>
                    {LANG.position_name}
                </td>
                <td>
                    {LANG.layoutdefault}
                </td>
            </tr>
        </thead>
        <!-- BEGIN: layout -->
        <tbody {LAYOUT.class}>
            <tr>
                <td align="center">
                    {LAYOUT.number}
                </td>
                <td>
                    {LAYOUT.text} 
                </td>
                <td>
                    <!-- BEGIN: layoutcheck -->
                    <label>
                        <input type="radio" name="layout[{LAYOUT.value}]" value="{LAYOUTCHECK.value}" {LAYOUTCHECK.checked} />
                        {LAYOUTCHECK.text}

                    </label>&nbsp;<!-- END: layoutcheck -->
                </td>
                <td>
                    <input type="radio" name="layoutdefault" value="{LAYOUT.value}" {LAYOUT.layoutdefault} />
                </td>
            </tr>
        </tbody><!-- END: layout -->
    </table>
    <table class="tab1" id="additem_position">
        <caption>
            {LANG.positions}
        </caption>
        <col style="width:10%;"/><col style="width:30%;"/><col style="width:30%;"/><col style="width:30%;"/>
        <thead align="center">
            <tr>
                <td>
                    {LANG.number}
                </td>
                <td>
                    {LANG.position_tag}
                </td>
                <td>
                    {LANG.position_name}
                </td>
                <td>
                    {LANG.position_name_vi}
                </td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="4">
                    <input type="button" value="{LANG.additem_position}" onclick="theme_additem_position();"/>
                </td>
            </tr>
        </tfoot><!-- BEGIN: positions -->
        <tbody {DATA_POSITION.class}>
            <tr>
                <td align="center">
                    {DATA_POSITION.number}
                </td>
                <td>
                    <input type="text" name="position_tag[{DATA_POSITION.number}]" style="width:220px;" value="{DATA_POSITION.tag}" />
                </td>
                <td>
                    <input type="text" name="position_name[{DATA_POSITION.number}]" style="width:220px;" value="{DATA_POSITION.name}" />
                </td>
                <td>
                    <input type="text" name="position_name_vi[{DATA_POSITION.number}]" style="width:220px;" value="{DATA_POSITION.name_vi}" />
                </td>
            </tr>
        </tbody><!-- END: positions -->
    </table>
    <div style="text-align: center">
        <input name="savedata" type="hidden" value="1" /><input name="submit1" type="submit" value="{LANG.download}" />
    </div>
</form>
<script type="text/javascript">
    //<![CDATA[
    var items_positions = '{ITEMS_POSITIONS}';
    $("input[name=layoutdefault]").click(function(){
        layoutdefault = $(this).val();
        $('input[name="layout[' + layoutdefault + ']"]')[0].checked = true;
    });
    $("select[name=pagetype]").change(function(){
        pagetype = $(this).val();
        if (pagetype == "pagefull") {
            $("#langPageWidth").text('{LANG.pageWidthMax}');
            $("input[name=pageWidthValue]").val({THEME_CSS.max_width});
        }
        else {
            $("#langPageWidth").text('{LANG.pageWidthValue}');
            pageWidthValue=$("input[name=pageWidthValue]").val();
            if(pageWidthValue >= {THEME_CSS.max_width}){
                $("input[name=pageWidthValue]").val({THEME_CSS.pageWidthValue});
            }
        }
    });
    
    (function($){
    	pagetype = $("select[name=pagetype]").val();
        if (pagetype == "pagefull") {
            $("#langPageWidth").text('{LANG.pageWidthMax}');
        }
        else {
            $("#langPageWidth").text('{LANG.pageWidthValue}');
        }
        $("#nvtools_theme").validate();
        $('.colorpic').ColorPicker({
            onSubmit: function(hsb, hex, rgb, el){
                $(el).val(hex);
                $(el).ColorPickerHide();
            },
            onBeforeShow: function(){
                $(this).ColorPickerSetColor(this.value);
            }
        }).bind('keyup', function(){
            $(this).ColorPickerSetColor(this.value);
        });
    })(jQuery)
    //]]>    
</script>
<!-- END: main -->
