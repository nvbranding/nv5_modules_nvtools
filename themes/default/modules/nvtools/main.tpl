<!-- BEGIN: main -->
<form id="nvtools_module" action="{NV_BASE_SITEURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.system_tools}</div>
        <table  class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <td style="width:250px;">
                        {LANG.module_name}
                    </td>
                    <td>
                        <input type="text" name="module_name" style="width:150px;" value="{DATA_SYSTEM.module_name}" class="form-control"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.version}
                    </td>
                    <td>
                        <input type="text" name="version1" value="{DATA_SYSTEM.version1}" style="width:40px; text-align: center;" class="form-control"/>.<input type="text" name="version2" value="{DATA_SYSTEM.version2}" style="width:40px; text-align: center;" class="form-control"/>.<input type="text" name="version3" value="{DATA_SYSTEM.version3}" style="width:50px; text-align: center;" class="form-control"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.author_name}
                    </td>
                    <td>
                        <input type="text" name="author_name" value="{DATA_SYSTEM.author_name}" style="width:400px;" class="form-control"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.author_email}
                    </td>
                    <td>
                        <input type="email" name="author_email" value="{DATA_SYSTEM.author_email}" style="width:400px;" class="form-control"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.note}
                    </td>
                    <td>
                        <input type="text" name="note" value="{DATA_SYSTEM.note}" style="width:400px;" class="form-control"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.uploads}
                    </td>
                    <td>
                        <input type="text" name="uploads" value="{DATA_SYSTEM.uploads}" style="width:400px;" class="form-control"/>
    					<span class="help-block help-block-top">{LANG.note_uploads}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.files}
                    </td>
                    <td>
                        <input type="text" name="files" value="{DATA_SYSTEM.files}" style="width:400px;" class="form-control"/>
    					<span class="help-block help-block-top">{LANG.note_files}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.is_sysmod}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="is_sysmod" value="1" {DATA_SYSTEM.is_sysmodcheckbox}/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.virtual}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="virtual" value="1" {DATA_SYSTEM.virtualcheckbox}/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.is_rss}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="is_rss" value="1" {DATA_SYSTEM.is_rsscheckbox}/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.is_sitemap}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="is_sitemap" value="1" {DATA_SYSTEM.is_sitemapcheckbox}/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.is_quicksearch}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="is_quicksearch" value="1" {DATA_SYSTEM.is_quicksearchcheckbox}/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.is_genmenu}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="is_genmenu" value="1" {DATA_SYSTEM.is_genmenucheckbox}/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.is_comment}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="is_comment" value="1" {DATA_SYSTEM.is_commentcheckbox}/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.is_notification}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="is_notification" value="1" {DATA_SYSTEM.is_notificationcheckbox}/>
                    </td>
                </tr>
                <tr>
                    <td>
                        {LANG.is_langen}
                    </td>
                    <td>
                        <input type="checkbox" class="noStyle" name="is_langen" value="1" {DATA_SYSTEM.is_langencheckbox}/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.admin_tools}</div>
        <table id="adminitems" class="table table-striped table-bordered table-hover">
            <thead class="text-center">
                <tr>
                    <th style="width:10%">
                        {LANG.number}
    				</th>
                    <th style="width:20%">
                    	{LANG.functionname}
                    </th>
                    <th style="width:30%">
                    	{LANG.title_en}
                    </th>
                    <th>
                    	{LANG.title_vi}
                    </th>
                    <th class="text-center">
                        {LANG.file_ajax}
                    </th>
                </tr>
            </thead>
           	<tbody>
    			<!-- BEGIN: admin -->
	            <tr>
	                <td class="text-center">
	                    {DATA_ADMIN.number}
	                </td>
	                <td>
	                    <input type="text" name="adminfile[{DATA_ADMIN.number}]" class="form-control" value="{DATA_ADMIN.file}" />
	                </td>
	                <td>
	                    <input type="text" name="admintitle[{DATA_ADMIN.number}]" class="form-control" value="{DATA_ADMIN.title}" />
	                </td>
	                <td>
	                    <input type="text" name="admintitlevi[{DATA_ADMIN.number}]" class="form-control" value="{DATA_ADMIN.titlevi}" />
	                </td>
	                <td class="text-center">
	                    <input type="checkbox" name="adminajax[{DATA_ADMIN.number}]" value="1" {DATA_ADMIN.checkbox} />
	                </td>
	            </tr>
    			<!-- END: admin -->
    		</tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <input type="button" value="{LANG.additem_admin}" onclick="nv_additem_admin();" class="btn btn-default"/>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.site_tools}</div>
        <table id="siteitems" class="table table-striped table-bordered table-hover">
            <col style="width:10%"/>
            <col style="width:20%"/>
            <col style="width:30%"/>
            <thead class="text-center">
                <tr>
                    <th>
                        {LANG.number}
    				</th>
                    <th>
                    	{LANG.functionname}
                    </th>
                    <th>
                    	{LANG.title_en}
                    </th>
                    <th>
                    	{LANG.title_vi}
                    </th>
                    <th class="text-center">
                        {LANG.file_ajax}
                    </th>
                </tr>
            </thead>
            <tbody>
        		<!-- BEGIN: site -->
                <tr>
                    <td class="text-center">
                        {DATA_SITE.number}
                    </td>
                    <td>
                        <input type="text" name="sitefile[{DATA_SITE.number}]" class="form-control" value="{DATA_SITE.file}" />
                    </td>
                    <td>
                        <input type="text" name="sitetitle[{DATA_SITE.number}]" class="form-control" value="{DATA_SITE.title}" />
                    </td>
                    <td>
                        <input type="text" name="sitetitlevi[{DATA_SITE.number}]" class="form-control" value="{DATA_SITE.titlevi}" />
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="siteajax[{DATA_SITE.number}]" value="1" {DATA_SITE.checkbox} />
                    </td>
                </tr>
        		<!-- END: site -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <input type="button" value="{LANG.additem_site}" onclick="nv_additem_site();" class="btn btn-default"/>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.sql_tools}</div>
        <table id="sqlitems" class="table table-striped table-bordered table-hover">
            <col style="width:10%"/>
            <col style="width:20%"/>
            <thead class="text-center">
                <tr>
                    <th>
                        {LANG.number}
                    </th>
                    <th>
                        {LANG.table_name}
                    </th>
                    <th>
                        {LANG.code_sql}
                    </th>
                </tr>
            </thead>
    		<tbody>
                <tr>
                	<td colspan="3">{LANG.note_sql}</td>
                </tr>
                <!-- BEGIN: sql -->
                <tr>
                    <td class="text-center">
                        {DATA_SQL.number}
                    </td>
                    <td>
                        <input type="text" name="tablename[{DATA_SQL.number}]" value="{DATA_SQL.table}" class="form-control"/>
    					<textarea id="idsqltablehidden_{DATA_SQL.number}" name="sqltablehidden[{DATA_SQL.number}]" class="hidden"></textarea>
                    </td>
                    <td>
                        <textarea id="idsqltable_{DATA_SQL.number}" class="form-control" rows="9">{DATA_SQL.sql}</textarea>
                    </td>
                </tr>
                <!-- END: sql -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <input type="button" value="{LANG.additem_sql}" onclick="nv_additem_sql();" class="btn btn-default"/>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="text-center form-group">
        <input name="savedata" type="hidden" value="1" />
        <input class="btn btn-primary" id="downloadmod" type="button" value="{LANG.download}" />
        <input class="btn btn-default" id="submitmod" type="button" value="{LANG.action}" />
    </div>
</form>
<script type="text/javascript">
//<![CDATA[
var items_admin = '{ITEMS_ADMIN}';
var items_site = '{ITEMS_SITE}';
var items_sql = '{ITEMS_SQL}';
$.base64.utf8encode = true;
$("#nvtools_module").submit(function(event) {
    for (var i = 1; i <= items_sql; i++) {
        sql = $("#idsqltable_" + i).val();
        if (sql != '') {
            sql = $.base64.encode(sql);
        }
        $("#idsqltablehidden_" + i).val(sql);
    };
    sql = $("#idsqltablehidden_" + 1).val();
});
$(document).ready(function() {
    $('#downloadmod').click(function() {
        $('#hiddensubmit').remove();
        $('#nvtools_module').append('<input name="download" id="hiddendownload" type="hidden" value="download" />').submit();
    });
    $('#submitmod').click(function() {
        $('#hiddendownload').remove();
        $('#nvtools_module').append('<input name="submit" id="hiddensubmit" type="hidden" value="submit" />').submit();
    });
});
//]]>
</script>
<!-- END: main -->